<?php
// Updated SessionController.php with enhanced functionality

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\News;
use App\Models\Testimony;
use App\Models\VendorProfile;
use App\Models\VendorCategory;
use App\Models\VendorPackage;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Banner;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\FacadesLog;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function index()
    {
        $testimony = Testimony::latest()->take(5)->get();
        $news = News::latest()->take(3)->get();
        $vendors = VendorProfile::with(['user', 'category', 'packages'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('rating', 'desc')
            ->take(8)
            ->get();
        $categories = VendorCategory::all();
        $banners = Banner::where('is_active', true)->orderBy('order')->get();

        return view('index', compact('news', 'testimony', 'vendors', 'categories', 'banners'));
    }

    public function auth()
    {
        return view('auth');
    }

    public function getVendors(Request $request)
    {
        $query = VendorProfile::with(['user', 'category', 'packages', 'portfolioImages'])
            ->where('status', 'active');

        // Apply filters
        if ($request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->location) {
            $query->where('location',  $request->location);
        }

        if ($request->price_min || $request->price_max) {
            if ($request->price_min) {
                $query->where('price_range_min', '>=', $request->price_min);
            }
            if ($request->price_max) {
                $query->where('price_range_max', '<=', $request->price_max);
            }
        }

        if ($request->rating) {
            $query->where('rating', '>=', $request->rating);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('search_tags', 'like', '%' . $request->search . '%');
            });
        }

        // Apply sorting
        switch ($request->sort_by) {
            case 'name':
                $query->orderBy('business_name');
                break;
            case 'price_low':
                $query->orderBy('price_range_min');
                break;
            case 'price_high':
                $query->orderByDesc('price_range_max');
                break;
            case 'rating':
            default:
                $query->orderByDesc('rating');
                break;
        }

        $vendors = $query->paginate(12);

        return response()->json($vendors);
    }

    public function getVendorDetail($id)
    {
        $vendor = VendorProfile::with([
            'user',
            'category',
            'packages' => function ($q) {
                $q->where('is_active', true);
            },
            'portfolioImages',
            'reviews' => function ($q) {
                $q->with('user')->latest()->take(10);
            }
        ])->findOrFail($id);

        return response()->json($vendor);
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'vendor_package_id' => 'required|exists:vendor_packages,id',
            'quantity' => 'integer|min:1|max:10',
            'event_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:500'
        ]);

        $package = VendorPackage::findOrFail($request->vendor_package_id);

        $cartItem = CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'vendor_package_id' => $request->vendor_package_id
            ],
            [
                'quantity' => $request->quantity ?? 1,
                'event_date' => $request->event_date,
                'notes' => $request->notes
            ]
        );

        $cartCount = CartItem::where('user_id', Auth::id())->count();

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_count' => $cartCount
        ]);
    }

    public function getCart()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $cartItems = CartItem::with([
            'vendorPackage.vendorProfile.user',
            'vendorPackage.vendorProfile.category'
        ])
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->vendorPackage->price * $item->quantity;
        });

        return response()->json([
            'items' => $cartItems,
            'total' => $total,
            'count' => $cartItems->count()
        ]);
    }

    public function updateCartItem(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated successfully']);
    }

    public function removeFromCart($id)
    {
        CartItem::where('user_id', Auth::id())->where('id', $id)->delete();

        $cartCount = CartItem::where('user_id', Auth::id())->count();

        return response()->json([
            'message' => 'Item removed from cart',
            'cart_count' => $cartCount
        ]);
    }

    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'event_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:1000'
        ]);

        $cartItems = CartItem::with('vendorPackage')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $orders = [];
        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $orderTotal = $item->vendorPackage->price * $item->quantity;
            $totalAmount += $orderTotal;

            $orders[] = Order::create([
                'user_id' => Auth::id(),
                'vendor_package_id' => $item->vendor_package_id,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'qty' => $item->quantity,
                'total_price' => $orderTotal,
                'event_date' => $request->event_date,
                'notes' => $request->notes,
                'status' => 'Unpaid'
            ]);
        }

        // Clear cart after successful order
        CartItem::where('user_id', Auth::id())->delete();

        return response()->json([
            'message' => 'Orders created successfully',
            'orders' => $orders,
            'total_amount' => $totalAmount
        ]);
    }

    public function getOrders()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $orders = Order::with([
            'vendorPackage.vendorProfile.user',
            'vendorPackage.vendorProfile.category'
        ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function getOrderDetails($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $order = Order::with([
            'vendorPackage.vendorProfile.user',
            'vendorPackage.vendorProfile.category',
            'vendorPackage.vendorProfile.portfolioImages',
            'review'
        ])->findOrFail($id);



        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = [
            'order' => $order,
            'message' => 'Order details retrieved successfully',
            'success' => true,
        ];

        return response()->json($data);
    }

    public function downloadInvoice($orderId)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Please login first'], 401);
            }

            // Get order with relationships
            $order = Order::with([
                'vendorPackage.vendorProfile.user',
                'vendorPackage.vendorProfile.category',
                'user'
            ])->findOrFail($orderId);

            // Check authorization
            if ($order->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Check if order is paid
            if ($order->status !== 'Paid') {
                return response()->json([
                    'error' => 'Invoice is only available for paid orders'
                ], 400);
            }

            // Prepare invoice data
            $invoiceData = [
                'order' => $order,
                'invoice_number' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'invoice_date' => $order->updated_at->format('d F Y'),
                'company_name' => 'Sweet Moments',
                'company_address' => 'Wedding Services Platform',
                'company_phone' => '+62 812-3456-7890',
                'company_email' => 'info@sweetmoments.com',
            ];

            // Generate PDF
            $pdf = Pdf::loadView('invoices.template', $invoiceData);

            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');

            // Download PDF
            $fileName = 'invoice-' . $order->id . '-' . date('Ymd') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('Invoice generation error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to generate invoice',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function streamInvoice($orderId)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Please login first'], 401);
            }

            $order = Order::with([
                'vendorPackage.vendorProfile.user',
                'vendorPackage.vendorProfile.category',
                'user'
            ])->findOrFail($orderId);

            if ($order->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if ($order->status !== 'Paid') {
                return response()->json([
                    'error' => 'Invoice is only available for paid orders'
                ], 400);
            }

            $invoiceData = [
                'order' => $order,
                'invoice_number' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'invoice_date' => $order->updated_at->format('d F Y'),
                'company_name' => 'Sweet Moments',
                'company_address' => 'Wedding Services Platform',
                'company_phone' => '+62 812-3456-7890',
                'company_email' => 'info@sweetmoments.com',
            ];

            $pdf = Pdf::loadView('invoices.template', $invoiceData);
            $pdf->setPaper('a4', 'portrait');

            // Stream PDF in browser
            return $pdf->stream('invoice-' . $order->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Invoice stream error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to generate invoice',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCategories()
    {
        $categories = VendorCategory::withCount('vendorProfiles')->get();
        return response()->json($categories);
    }

    public function getTestimonials()
    {
        $testimonials = Testimony::latest()->get();
        return response()->json($testimonials);
    }

    public function getNews()
    {
        $news = News::latest()->paginate(9);
        return response()->json($news);
    }

    public function getUserProfile()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $user = Auth::user()->load(['vendorProfile.category', 'favorites.vendorProfile']);
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Laki-Laki,Perempuan'
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'phone', 'address', 'gender']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    // ... (keep your existing auth methods: news, react, redirect, callback, signin, signup, logout)

    public function news($id)
    {

        $testimony = Testimony::latest()->take(5)->get();
        $news = News::latest()->take(3)->get();
        $vendors = VendorProfile::with(['user', 'category', 'packages'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('rating', 'desc')
            ->take(8)
            ->get();
        $categories = VendorCategory::all();
        $banners = Banner::where('is_active', true)->orderBy('order')->get();
        $news = News::findOrFail($id);

        return view('news', compact('news', 'testimony', 'vendors', 'categories', 'banners', 'news'));
    }

    public function react(Request $request, $id)
    {
        $request->validate([
            'reaction' => 'required|in:like,dislike',
        ]);

        $news = News::findOrFail($id);

        if ($request->reaction === 'like') {
            $news->increment('likes');
        } elseif ($request->reaction === 'dislike') {
            $news->increment('dislikes');
        }

        return response()->json([
            'success' => true,
            'message' => 'Reaksi berhasil ditambahkan.',
            'likes' => $news->likes,
            'dislikes' => $news->dislikes,
        ]);
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $socialUser = Socialite::driver('google')->user();

        $registeredUser = User::where("google_id", $socialUser->id)->first();

        if (!$registeredUser) {
            $user = User::updateOrCreate([
                'google_id' => $socialUser->id,
            ], [
                'name' => $socialUser->name ?? 'Nama Default',
                'email' => $socialUser->email ?? 'default@example.com',
                'password' => Hash::make('password_default'),
                'gender' => 'Laki-Laki',
                'role' => 'user',
                'address' => 'Alamat belum diisi',
                'username' => strtolower(str_replace(' ', '_', $socialUser->name ?? 'user_default')),
            ]);

            Auth::login($user);
        } else {
            Auth::login($registeredUser);
        }

        if (Auth::user()->role == 'admin') {
            return redirect('/admin');
        } elseif (Auth::user()->role == 'vendor') {
            return redirect('/vendor/home');
        } else {
            return redirect('/user/home');
        }
    }

    public function signin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role == 'admin') {
                return redirect('admin');
            } elseif (Auth::user()->role == 'user') {
                // return redirect('user/home');
                return redirect('/');
            } elseif (Auth::user()->role == 'vendor') {
                // return redirect('vendor/home');
                return redirect('/');
            }
        } else {
            return redirect()->back()
                ->withErrors(['login_error' => 'Username dan Password tidak valid'])
                ->withInput();
        }
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required',
            'role' => 'required',
            'address' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berupa format yang valid.',
            'email.unique' => 'Email sudah terdaftar',
            'username.unique' => 'Username sudah terdaftar',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'role.required' => 'Role wajib dipilih.',
            'address.required' => 'Alamat wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar, silakan gunakan username lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus memiliki minimal 6 karakter.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender ?? 'Laki-Laki',
            'role' => $request->role ?? 'User',
            'address' => $request->address,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'google_id' => "",
        ];

        $user = User::create($data);

        $this->sendWelcomeNotification($user->id);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function applyAsVendor(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'vendor_category_id' => 'required|exists:vendor_categories,id',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'price_range_min' => 'nullable|numeric|min:0',
            'price_range_max' => 'nullable|numeric|min:0'
        ]);

        // Check if user already has a vendor profile
        if (Auth::user()->vendorProfile) {
            return response()->json(['error' => 'You already have a vendor profile'], 400);
        }

        $vendorProfile = VendorProfile::create([
            'user_id' => Auth::id(),
            'vendor_category_id' => $request->vendor_category_id,
            'business_name' => $request->business_name,
            'description' => $request->description,
            'location' => $request->location,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
            'website' => $request->website,
            'price_range_min' => $request->price_range_min ?? 0,
            'price_range_max' => $request->price_range_max ?? 0,
            'rating' => 0,
            'total_reviews' => 0,
            'is_verified' => false,
            'is_featured' => false,
            'status' => 'pending'
        ]);

        // Update user role to vendor
        Auth::user()->update(['role' => 'vendor']);

        // Create notification for admin
        Notification::create([
            'user_id' => 1, // Assuming admin user ID is 1
            'type' => 'vendor_application',
            'title' => 'New Vendor Application',
            'message' => "New vendor application from {$request->business_name}",
            'data' => json_encode(['vendor_profile_id' => $vendorProfile->id]),
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vendor application submitted successfully',
            'vendor_profile' => $vendorProfile
        ]);
    }


    public function createNotification($userId, $type, $title, $message, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data ? json_encode($data) : null,
            'is_read' => false
        ]);
    }

    public function sendWelcomeNotification($userId)
    {
        $this->createNotification(
            $userId,
            'welcome',
            'Welcome to Sweet Moments!',
            'Thank you for joining our platform. Start exploring amazing wedding vendors.',
            ['action' => 'explore_vendors']
        );
    }

    public function getFavorites()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $favorites = Favorite::with('vendorProfile.category')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($favorites);
    }

    public function addFavorite(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'vendor_profile_id' => 'required|exists:vendor_profiles,id'
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'vendor_profile_id' => $request->vendor_profile_id
        ]);

        return response()->json([
            'message' => 'Added to favorites',
            'favorite' => $favorite
        ]);
    }

    public function removeFavorite($vendorId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Favorite::where('user_id', Auth::id())
            ->where('vendor_profile_id', $vendorId)
            ->delete();

        return response()->json(['message' => 'Removed from favorites']);
    }

    // Notifications Methods
    public function getNotifications()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    public function markNotificationRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['error' => 'Notification not found'], 404);
    }

    public function markAllNotificationsRead()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    // Messages Methods
    public function getMessages()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $messages = Message::with('fromUser')
            ->where('to_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load('toUser')
        ]);
    }

    public function markMessageRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $message = Message::where('to_user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($message) {
            $message->update(['is_read' => true]);
            return response()->json(['message' => 'Message marked as read']);
        }

        return response()->json(['error' => 'Message not found'], 404);
    }
    // Add these methods to your SessionController class

    public function submitReview(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'vendor_profile_id' => 'required|exists:vendor_profiles,id',
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000',
                'recommend' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if the order belongs to the authenticated user
            $order = Order::where('id', $request->order_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or unauthorized'
                ], 404);
            }

            // Check if order is paid (only paid orders can be reviewed)
            if ($order->status !== 'Paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only paid orders can be reviewed'
                ], 400);
            }

            // Check if review already exists for this order
            $existingReview = Review::where('order_id', $request->order_id)
                ->where('user_id', Auth::id())
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this order'
                ], 400);
            }

            // Create the review
            $review = Review::create([
                'user_id' => Auth::id(),
                'vendor_profile_id' => $request->vendor_profile_id,
                'order_id' => $request->order_id,
                'rating' => $request->rating,
                'review' => $request->review
            ]);

            // Update vendor profile rating and total reviews
            $this->updateVendorRating($request->vendor_profile_id);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the review'
            ], 500);
        }
    }

    public function getOrderReview($orderId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        try {
            // Check if the order belongs to the authenticated user
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or unauthorized'
                ], 404);
            }

            // Get the review for this order
            $review = Review::with(['user', 'vendorProfile'])
                ->where('order_id', $orderId)
                ->where('user_id', Auth::id())
                ->first();

            return response()->json([
                'success' => true,
                'data' => $review,
                'has_review' => $review ? true : false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the review'
            ], 500);
        }
    }

    public function updateReview(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000',
                'recommend' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find the review and check ownership
            $review = Review::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or unauthorized'
                ], 404);
            }

            // Update the review
            $review->update([
                'rating' => $request->rating,
                'review' => $request->review
            ]);

            // Update vendor profile rating and total reviews
            $this->updateVendorRating($review->vendor_profile_id);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the review'
            ], 500);
        }
    }

    // Helper method to update vendor rating
    private function updateVendorRating($vendorProfileId)
    {
        try {
            $reviews = Review::where('vendor_profile_id', $vendorProfileId)->get();

            $totalReviews = $reviews->count();
            $averageRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;

            // Update vendor profile
            VendorProfile::where('id', $vendorProfileId)->update([
                'rating' => round($averageRating, 2),
                'total_reviews' => $totalReviews
            ]);
        } catch (\Exception $e) {
            // Log the error but don't fail the main operation
            Log::error('Failed to update vendor rating: ' . $e->getMessage());
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('');
    }
}
