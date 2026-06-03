<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\News;
use App\Models\VendorCategory;
use App\Models\VendorProfile;
use App\Models\VendorPackage;
use App\Models\VendorPortfolioImage;
use App\Models\VendorAvailability;
use App\Models\Order;
use App\Models\Review;
use App\Models\Testimony;
use App\Models\Notification;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $stats = [
                'total_users' => User::count(),
                'total_vendors' => VendorProfile::count(),
                'total_orders' => Order::count(),
                'total_revenue' => Order::where('status', 'Paid')->sum('total_price'),
                'pending_orders' => Order::where('status', 'Unpaid')->count(),
                'active_banners' => Banner::where('is_active', 1)->count(),
            ];
        } else {
            // Vendor stats
            $vendorProfile = VendorProfile::where('user_id', $user->id)->first();
            $stats = [
                'total_packages' => $vendorProfile ? VendorPackage::where('vendor_profile_id', $vendorProfile->id)->count() : 0,
                'total_orders' => $vendorProfile ? Order::whereHas('vendorPackage', function ($q) use ($vendorProfile) {
                    $q->where('vendor_profile_id', $vendorProfile->id);
                })->count() : 0,
                'pending_orders' => $vendorProfile ? Order::whereHas('vendorPackage', function ($q) use ($vendorProfile) {
                    $q->where('vendor_profile_id', $vendorProfile->id);
                })->where('status', 'Unpaid')->count() : 0,
                'total_revenue' => $vendorProfile ? Order::whereHas('vendorPackage', function ($q) use ($vendorProfile) {
                    $q->where('vendor_profile_id', $vendorProfile->id);
                })->where('status', 'Paid')->sum('total_price') : 0,
                'profile_rating' => $vendorProfile->rating ?? 0,
                'total_reviews' => $vendorProfile->total_reviews ?? 0,
            ];
        }

        return view('admin.dashboard', compact('stats'));
    }

    // Helper method to check if user is admin
    private function isAdmin()
    {
        return Auth::user()->role === 'admin';
    }

    // Helper method to get vendor profile
    private function getVendorProfile()
    {
        return VendorProfile::where('user_id', Auth::id())->first();
    }

    // Vendor Profile Management (Admin & Vendor)
    public function vendorProfile()
    {
        $vendorProfile = null;
        $categories = VendorCategory::all();

        if ($this->isAdmin()) {
            // Admin can view all vendor profiles or create new ones
            return view('admin.vendor-profile.index', compact('categories'));
        } else {
            // Vendor can only view/edit their own profile
            $vendorProfile = $this->getVendorProfile();
            return view('admin.vendor-profile.manage', compact('vendorProfile', 'categories'));
        }
    }

    public function vendorProfileData()
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $profiles = VendorProfile::with(['user', 'vendorCategory'])
            ->select(['id', 'user_id', 'vendor_category_id', 'business_name', 'location', 'status', 'is_verified', 'rating', 'created_at']);

        return DataTables::of($profiles)
            ->addColumn('action', function ($profile) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-info" onclick="viewVendorProfile(' . $profile->id . ')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editVendorProfile(' . $profile->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteVendorProfile(' . $profile->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('user_id', function ($profile) {
                return $profile->user ? $profile->user->name : '-';
            })
            ->editColumn('vendor_category_id', function ($profile) {
                return $profile->vendorCategory ? $profile->vendorCategory->name : '-';
            })
            ->editColumn('status', function ($profile) {
                $badges = [
                    'active' => 'success',
                    'inactive' => 'danger',
                    'pending' => 'warning'
                ];
                return '<span class="badge bg-' . $badges[$profile->status] . '">' . $profile->status . '</span>';
            })
            ->editColumn('is_verified', function ($profile) {
                return $profile->is_verified ? '<span class="badge bg-success">Verified</span>' : '<span class="badge bg-secondary">Not Verified</span>';
            })
            ->editColumn('created_at', function ($profile) {
                return $profile->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'status', 'is_verified'])
            ->make(true);
    }

    public function vendorProfileStore(Request $request)
    {
        $validated = $request->validate([
            'user_id' => $this->isAdmin() ? 'required|exists:users,id' : 'nullable',
            'vendor_category_id' => 'required|exists:vendor_categories,id',
            'business_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_range_min' => 'nullable|numeric|min:0',
            'price_range_max' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        if (!$this->isAdmin()) {
            $validated['user_id'] = Auth::id();
            $validated['status'] = 'pending';
        }

        VendorProfile::create($validated);

        return response()->json(['success' => true, 'message' => 'Vendor profile created successfully']);
    }

    public function vendorProfileUpdate(Request $request, $id)
    {
        $profile = VendorProfile::findOrFail($id);

        // Check permissions
        if (!$this->isAdmin() && $profile->user_id !== Auth::id()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $validated = $request->validate([
            'vendor_category_id' => 'required|exists:vendor_categories,id',
            'business_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_range_min' => 'nullable|numeric|min:0',
            'price_range_max' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        // Only admin can update status and verification
        if ($this->isAdmin()) {
            $validated['status'] = $request->input('status', $profile->status);
            $validated['is_verified'] = $request->boolean('is_verified');
        }

        $profile->update($validated);

        return response()->json(['success' => true, 'message' => 'Vendor profile updated successfully']);
    }

    // Vendor Packages Management
    public function vendorPackages()
    {
        return view('admin.vendor-packages.index');
    }

    public function vendorPackagesData()
    {
        $query = VendorPackage::with('vendorProfile');

        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile) {
                return DataTables::of(collect([]))->make(true);
            }
            $query->where('vendor_profile_id', $vendorProfile->id);
        }

        $packages = $query->select(['id', 'vendor_profile_id', 'name', 'price', 'is_active', 'created_at']);

        return DataTables::of($packages)
            ->addColumn('action', function ($package) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-info" onclick="viewPackage(' . $package->id . ')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editPackage(' . $package->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deletePackage(' . $package->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('vendor_profile_id', function ($package) {
                return $package->vendorProfile ? $package->vendorProfile->business_name : '-';
            })
            ->editColumn('price', function ($package) {
                return 'Rp ' . number_format($package->price, 0, ',', '.');
            })
            ->editColumn('is_active', function ($package) {
                return $package->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('created_at', function ($package) {
                return $package->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'is_active'])
            ->make(true);
    }

    public function vendorPackageStore(Request $request)
    {
        $validated = $request->validate([
            'vendor_profile_id' => $this->isAdmin() ? 'required|exists:vendor_profiles,id' : 'nullable',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile) {
                return response()->json(['error' => 'Vendor profile not found'], 404);
            }
            $validated['vendor_profile_id'] = $vendorProfile->id;
        }

        VendorPackage::create($validated);

        return response()->json(['success' => true, 'message' => 'Package created successfully']);
    }

    public function vendorPackageUpdate(Request $request, $id)
    {
        $package = VendorPackage::findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $package->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $package->update($validated);

        return response()->json(['success' => true, 'message' => 'Package updated successfully']);
    }

    public function vendorPackageDestroy($id)
    {
        $package = VendorPackage::findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $package->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        $package->delete();

        return response()->json(['success' => true, 'message' => 'Package deleted successfully']);
    }

    // Portfolio Images Management
    public function portfolioImages()
    {
        return view('admin.portfolio-images.index');
    }

    public function portfolioImagesData()
    {
        $query = VendorPortfolioImage::with('vendorProfile');

        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile) {
                return DataTables::of(collect([]))->make(true);
            }
            $query->where('vendor_profile_id', $vendorProfile->id);
        }

        $images = $query->select(['id', 'vendor_profile_id', 'image', 'caption', 'is_featured', 'created_at']);

        return DataTables::of($images)
            ->addColumn('action', function ($image) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-info" onclick="viewImage(' . $image->id . ')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editImage(' . $image->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteImage(' . $image->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('image', function ($image) {
                return '<img src="' . asset('storage/' . $image->image) . '" width="50" height="30" class="img-thumbnail">';
            })
            ->editColumn('vendor_profile_id', function ($image) {
                return $image->vendorProfile ? $image->vendorProfile->business_name : '-';
            })
            ->editColumn('is_featured', function ($image) {
                return $image->is_featured ? '<span class="badge bg-warning">Featured</span>' : '<span class="badge bg-secondary">Regular</span>';
            })
            ->editColumn('created_at', function ($image) {
                return $image->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'image', 'is_featured'])
            ->make(true);
    }

    // News Management (Admin Only) 
    public function news()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }
        return view('admin.news.index');
    }

    public function users()
    {
        if (!$this->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }
        return view('admin.users.index');
    }



    public function usersData()
    {
        $users = User::select(['id', 'name', 'email', 'role', 'phone', 'created_at']);

        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-info" onclick="viewUser(' . $user->id . ')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editUser(' . $user->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(' . $user->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('d M Y');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,vendor,user',
            'gender' => 'required|in:Laki-Laki,Perempuan',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['google_id'] = Str::random(10);

        User::create($validated);

        return response()->json(['success' => true, 'message' => 'User created successfully']);
    }

    public function userShow($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|string|unique:users,username,' . $id,
            'role' => 'required|in:admin,vendor,user',
            'gender' => 'required|in:Laki-Laki,Perempuan',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $user->update($validated);

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    // Banners Management
    public function banners()
    {
        return view('admin.banners.index');
    }

    public function bannersData()
    {
        $banners = Banner::select(['id', 'title', 'subtitle', 'image', 'position', 'is_active', 'order', 'created_at']);

        return DataTables::of($banners)
            ->addColumn('action', function ($banner) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-info" onclick="viewBanner(' . $banner->id . ')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editBanner(' . $banner->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteBanner(' . $banner->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('image', function ($banner) {
                return '<img src="' . asset('storage/' . $banner->image) . '" width="50" height="30" class="img-thumbnail">';
            })
            ->editColumn('is_active', function ($banner) {
                return $banner->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('created_at', function ($banner) {
                return $banner->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'image', 'is_active'])
            ->make(true);
    }

    public function bannerStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'position' => 'required|in:hero,middle,bottom',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // Create banners directory if it doesn't exist
            $bannersDir = public_path('storage/banners');
            if (!file_exists($bannersDir)) {
                mkdir($bannersDir, 0755, true);
            }

            // Generate unique filename
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move file directly to public/storage/banners
            $image->move($bannersDir, $filename);

            // Store relative path in database
            $validated['image'] = 'banners/' . $filename;
        }

        Banner::create($validated);

        return response()->json(['success' => true, 'message' => 'Banner created successfully']);
    }

    public function bannerShow($id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    public function bannerUpdate(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'position' => 'required|in:hero,middle,bottom',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($banner->image) {
                $oldImagePath = public_path('storage/' . $banner->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Create banners directory if it doesn't exist
            $bannersDir = public_path('storage/banners');
            if (!file_exists($bannersDir)) {
                mkdir($bannersDir, 0755, true);
            }

            // Generate unique filename
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move file directly to public/storage/banners
            $destinationPath = $bannersDir . '/' . $filename;
            $image->move($bannersDir, $filename);

            // Store relative path in database
            $validated['image'] = 'banners/' . $filename;
        }

        $banner->update($validated);

        return response()->json(['success' => true, 'message' => 'Banner updated successfully']);
    }

    public function bannerDestroy($id)
    {
        $banner = Banner::findOrFail($id);

        // if ($banner->image) {
        //     Storage::disk('public')->delete($banner->image);
        // }

        $banner->delete();

        return response()->json(['success' => true, 'message' => 'Banner deleted successfully']);
    }

    public function newsData()
    {
        $news = News::select(['id', 'title', 'description', 'image', 'likes', 'dislikes', 'created_at']);

        return DataTables::of($news)
            ->addColumn('action', function ($article) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-info" onclick="viewNews(' . $article->id . ')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editNews(' . $article->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteNews(' . $article->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('image', function ($article) {
                return '<img src="' . asset('storage/' . $article->image) . '" width="50" height="30" class="img-thumbnail">';
            })
            ->editColumn('description', function ($article) {
                return Str::limit($article->description, 50);
            })
            ->editColumn('created_at', function ($article) {
                return $article->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    public function newsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Create news directory if it doesn't exist
            $newsDir = public_path('storage/news');
            if (!file_exists($newsDir)) {
                mkdir($newsDir, 0755, true);
            }

            // Generate unique filename
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move file directly to public/storage/news
            $image->move($newsDir, $filename);

            // Store relative path in database
            $validated['image'] = 'news/' . $filename;
        }

        News::create($validated);

        return response()->json(['success' => true, 'message' => 'News created successfully']);
    }

    public function newsShow($id)
    {
        $news = News::findOrFail($id);
        return response()->json($news);
    }

    public function newsUpdate(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image) {
                $oldImagePath = public_path('storage/' . $news->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Create news directory if it doesn't exist
            $newsDir = public_path('storage/news');
            if (!file_exists($newsDir)) {
                mkdir($newsDir, 0755, true);
            }

            // Generate unique filename
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move file directly to public/storage/news
            $image->move($newsDir, $filename);

            // Store relative path in database
            $validated['image'] = 'news/' . $filename;
        }

        $news->update($validated);

        return response()->json(['success' => true, 'message' => 'News updated successfully']);
    }

    public function newsDestroy($id)
    {
        $news = News::findOrFail($id);

        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return response()->json(['success' => true, 'message' => 'News deleted successfully']);
    }

    // Vendor Categories Management
    public function vendorCategories()
    {
        return view('admin.vendor-categories.index');
    }

    public function vendorCategoriesData()
    {
        $categories = VendorCategory::select(['id', 'name', 'slug', 'icon', 'created_at']);

        return DataTables::of($categories)
            ->addColumn('action', function ($category) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-warning" onclick="editCategory(' . $category->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteCategory(' . $category->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('icon', function ($category) {
                return $category->icon ? '<i class="' . $category->icon . '"></i>' : '-';
            })
            ->editColumn('created_at', function ($category) {
                return $category->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'icon'])
            ->make(true);
    }

    public function vendorCategoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        VendorCategory::create($validated);

        return response()->json(['success' => true, 'message' => 'Category created successfully']);
    }

    public function vendorCategoryShow($id)
    {
        $category = VendorCategory::findOrFail($id);
        return response()->json($category);
    }

    public function vendorCategoryUpdate(Request $request, $id)
    {
        $category = VendorCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return response()->json(['success' => true, 'message' => 'Category updated successfully']);
    }

    public function vendorCategoryDestroy($id)
    {
        $category = VendorCategory::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }

    // Orders Management
    public function orders()
    {
        return view('admin.orders.index');
    }

    public function ordersData()
    {
        $orders = Order::with(['user', 'vendorPackage.vendorProfile'])
            ->select(['id', 'user_id', 'vendor_package_id', 'name', 'total_price', 'status', 'event_date', 'created_at']);

        $vendor_profile_id = $this->isAdmin() ? null : $this->getVendorProfile()?->id;

        if (!$this->isAdmin()) {
            $orders->whereHas('vendorPackage', function ($query) use ($vendor_profile_id) {
                $query->where('vendor_profile_id', $vendor_profile_id);
            });
        }

        return DataTables::of($orders)
            ->addColumn('action', function ($order) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-info" onclick="viewOrder(' . $order->id . ')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editOrder(' . $order->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteOrder(' . $order->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('user_id', function ($order) {
                return $order->user ? $order->user->name : '-';
            })
            ->editColumn('vendor_package_id', function ($order) {
                return $order->vendorPackage ? $order->vendorPackage->name : '-';
            })
            ->editColumn('total_price', function ($order) {
                return 'Rp ' . number_format($order->total_price, 0, ',', '.');
            })
            ->editColumn('status', function ($order) {
                return $order->status == 'Paid' ?
                    '<span class="badge bg-success">Paid</span>' :
                    '<span class="badge bg-warning">Unpaid</span>';
            })
            ->editColumn('event_date', function ($order) {
                return $order->event_date ? $order->event_date->format('d M Y') : '-';
            })
            ->editColumn('created_at', function ($order) {
                return $order->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function orderShow($id)
    {
        $order = Order::with(['user', 'vendorPackage.vendorProfile'])->findOrFail($id);
        return response()->json($order);
    }

    public function orderUpdate(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:Unpaid,Paid',
            'payment_status' => 'nullable|string',
        ]);

        $order->update($validated);

        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }

    public function orderDestroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
    }

    // Testimonies Management
    public function testimonies()
    {
        return view('admin.testimonies.index');
    }

    public function testimoniesData()
    {
        $testimonies = Testimony::select(['id', 'user', 'testimony', 'rating', 'created_at']);

        return DataTables::of($testimonies)
            ->addColumn('action', function ($testimony) {
                return '<div class="btn-group">
                    <button class="btn btn-sm btn-info" onclick="viewTestimony(' . $testimony->id . ')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="editTestimony(' . $testimony->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteTestimony(' . $testimony->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->editColumn('testimony', function ($testimony) {
                return Str::limit($testimony->testimony, 50);
            })
            ->editColumn('rating', function ($testimony) {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= $i <= $testimony->rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-muted"></i>';
                }
                return $stars;
            })
            ->editColumn('created_at', function ($testimony) {
                return $testimony->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'rating'])
            ->make(true);
    }

    public function testimonyStore(Request $request)
    {
        $validated = $request->validate([
            'user' => 'required|string|max:255',
            'testimony' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Testimony::create($validated);

        return response()->json(['success' => true, 'message' => 'Testimony created successfully']);
    }

    public function testimonyShow($id)
    {
        $testimony = Testimony::findOrFail($id);
        return response()->json($testimony);
    }

    public function testimonyUpdate(Request $request, $id)
    {
        $testimony = Testimony::findOrFail($id);

        $validated = $request->validate([
            'user' => 'required|string|max:255',
            'testimony' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $testimony->update($validated);

        return response()->json(['success' => true, 'message' => 'Testimony updated successfully']);
    }

    public function testimonyDestroy($id)
    {
        $testimony = Testimony::findOrFail($id);
        $testimony->delete();

        return response()->json(['success' => true, 'message' => 'Testimony deleted successfully']);
    }

    // Vendor Profile Methods (continued from the main controller)
    public function vendorProfileShow($id)
    {
        $profile = VendorProfile::with(['user', 'vendorCategory'])->findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin() && $profile->user_id !== Auth::id()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        return response()->json($profile);
    }

    public function vendorProfileDestroy($id)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $profile = VendorProfile::findOrFail($id);
        $profile->delete();

        return response()->json(['success' => true, 'message' => 'Vendor profile deleted successfully']);
    }

    // Portfolio Images Methods
    public function portfolioImageStore(Request $request)
    {
        $validated = $request->validate([
            'vendor_profile_id' => $this->isAdmin() ? 'required|exists:vendor_profiles,id' : 'nullable',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'caption' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ]);

        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile) {
                return response()->json(['error' => 'Vendor profile not found'], 404);
            }
            $validated['vendor_profile_id'] = $vendorProfile->id;
        }

        if ($request->hasFile('image')) {
            // Create portfolio directory if it doesn't exist
            $portfolioDir = public_path('storage/portfolio');
            if (!file_exists($portfolioDir)) {
                mkdir($portfolioDir, 0755, true);
            }

            // Generate unique filename
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move file directly to public/storage/portfolio
            $image->move($portfolioDir, $filename);

            // Store relative path in database
            $validated['image'] = 'portfolio/' . $filename;
        }

        VendorPortfolioImage::create($validated);

        return response()->json(['success' => true, 'message' => 'Portfolio image added successfully']);
    }

    public function portfolioImageShow($id)
    {
        $image = VendorPortfolioImage::with('vendorProfile')->findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $image->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        return response()->json($image);
    }

    public function portfolioImageUpdate(Request $request, $id)
    {
        $image = VendorPortfolioImage::findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $image->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'caption' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($image->image) {
                $oldImagePath = public_path('storage/' . $image->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Create portfolio directory if it doesn't exist
            $portfolioDir = public_path('storage/portfolio');
            if (!file_exists($portfolioDir)) {
                mkdir($portfolioDir, 0755, true);
            }

            // Generate unique filename
            $imageFile = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();

            // Move file directly to public/storage/portfolio
            $imageFile->move($portfolioDir, $filename);

            // Store relative path in database
            $validated['image'] = 'portfolio/' . $filename;
        }

        $image->update($validated);

        return response()->json(['success' => true, 'message' => 'Portfolio image updated successfully']);
    }

    public function portfolioImageDestroy($id)
    {
        $image = VendorPortfolioImage::findOrFail($id);

        // Check permissions for vendors
        // if (!$this->isAdmin()) {
        //     $vendorProfile = $this->getVendorProfile();
        //     if (!$vendorProfile || $image->vendor_profile_id !== $vendorProfile->id) {
        //         return response()->json(['error' => 'Access denied'], 403);
        //     }
        // }

        if ($image->image) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return response()->json(['success' => true, 'message' => 'Portfolio image deleted successfully']);
    }

    // Vendor Packages Methods (show method)
    public function vendorPackageShow($id)
    {
        $package = VendorPackage::with('vendorProfile')->findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $package->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        return response()->json($package);
    }

    // Vendor Availability Methods
    public function vendorAvailabilityData()
    {
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile) {
                return DataTables::of(collect([]))->make(true);
            }
            $query = VendorAvailability::where('vendor_profile_id', $vendorProfile->id);
        } else {
            $query = VendorAvailability::with('vendorProfile');
        }

        $availability = $query->select(['id', 'vendor_profile_id', 'date', 'is_available', 'notes', 'created_at']);

        return DataTables::of($availability)
            ->addColumn('action', function ($item) {
                return '<div class="btn-group">
                <button class="btn btn-sm btn-warning" onclick="editAvailability(' . $item->id . ')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteAvailability(' . $item->id . ')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>';
            })
            ->editColumn('date', function ($item) {
                return $item->date->format('d M Y');
            })
            ->editColumn('is_available', function ($item) {
                return $item->is_available ?
                    '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Available</span>' :
                    '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Unavailable</span>';
            })
            ->editColumn('notes', function ($item) {
                return $item->notes ? Str::limit($item->notes, 50) : '-';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'is_available'])
            ->make(true);
    }

    public function vendorAvailabilityStore(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'is_available' => 'required|boolean',
            'notes' => 'nullable|string|max:255',
        ]);

        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile) {
                return response()->json(['error' => 'Vendor profile not found'], 404);
            }
            $validated['vendor_profile_id'] = $vendorProfile->id;
        }

        // Check if availability already exists for this date
        $existing = VendorAvailability::where('vendor_profile_id', $validated['vendor_profile_id'])
            ->where('date', $validated['date'])
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Availability for this date already exists'], 422);
        }

        VendorAvailability::create($validated);

        return response()->json(['success' => true, 'message' => 'Availability set successfully']);
    }

    public function vendorAvailabilityShow($id)
    {
        $availability = VendorAvailability::findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $availability->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        return response()->json($availability);
    }

    public function vendorAvailabilityUpdate(Request $request, $id)
    {
        $availability = VendorAvailability::findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $availability->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'is_available' => 'required|boolean',
            'notes' => 'nullable|string|max:255',
        ]);

        $availability->update($validated);

        return response()->json(['success' => true, 'message' => 'Availability updated successfully']);
    }

    public function vendorAvailabilityDestroy($id)
    {
        $availability = VendorAvailability::findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $availability->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        $availability->delete();

        return response()->json(['success' => true, 'message' => 'Availability deleted successfully']);
    }

    // Reviews Management
    public function reviews()
    {
        return view('admin.reviews.index');
    }

    public function reviewsData()
    {
        $query = Review::with(['user', 'vendorProfile']);

        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile) {
                return DataTables::of(collect([]))->make(true);
            }
            $query->where('vendor_profile_id', $vendorProfile->id);
        }

        $reviews = $query->select(['id', 'user_id', 'vendor_profile_id', 'rating', 'review', 'created_at']);

        return DataTables::of($reviews)
            ->addColumn('action', function ($review) {
                $actions = '<div class="btn-group">
                <button class="btn btn-sm btn-info" onclick="viewReview(' . $review->id . ')">
                    <i class="fas fa-eye"></i>
                </button>';

                if ($this->isAdmin()) {
                    $actions .= '<button class="btn btn-sm btn-danger" onclick="deleteReview(' . $review->id . ')">
                    <i class="fas fa-trash"></i>
                </button>';
                }

                $actions .= '</div>';
                return $actions;
            })
            ->editColumn('user_id', function ($review) {
                return $review->user ? $review->user->name : '-';
            })
            ->editColumn('vendor_profile_id', function ($review) {
                return $review->vendorProfile ? $review->vendorProfile->business_name : '-';
            })
            ->editColumn('rating', function ($review) {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= $i <= $review->rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-muted"></i>';
                }
                return $stars;
            })
            ->editColumn('review', function ($review) {
                return $review->review ? Str::limit($review->review, 50) : '-';
            })
            ->editColumn('created_at', function ($review) {
                return $review->created_at->format('d M Y');
            })
            ->rawColumns(['action', 'rating'])
            ->make(true);
    }

    public function reviewShow($id)
    {
        $review = Review::with(['user', 'vendorProfile'])->findOrFail($id);

        // Check permissions for vendors
        if (!$this->isAdmin()) {
            $vendorProfile = $this->getVendorProfile();
            if (!$vendorProfile || $review->vendor_profile_id !== $vendorProfile->id) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        return response()->json($review);
    }

    public function reviewDestroy($id)
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['success' => true, 'message' => 'Review deleted successfully']);
    }

    // Add these methods to your AdminController.php

    // Vendor Availability Management (Vendor Only)
    public function vendorAvailability()
    {
        // Only vendors can access this
        if ($this->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'This feature is for vendors only.');
        }

        return view('admin.vendor-availability.index');
    }

    // Bulk Availability Operations (Vendor Only)
    public function vendorAvailabilityBulkStore(Request $request)
    {
        $vendorProfile = $this->getVendorProfile();
        if (!$vendorProfile) {
            return response()->json(['error' => 'Vendor profile not found'], 404);
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_available' => 'required|boolean',
            'notes' => 'nullable|string|max:255',
            'skip_weekends' => 'boolean',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $created = 0;
        $updated = 0;

        while ($startDate->lte($endDate)) {
            // Skip weekends if requested
            if ($validated['skip_weekends'] && $startDate->isWeekend()) {
                $startDate->addDay();
                continue;
            }

            $dateStr = $startDate->format('Y-m-d');

            // Check if availability already exists
            $existing = VendorAvailability::where('vendor_profile_id', $vendorProfile->id)
                ->where('date', $dateStr)
                ->first();

            if ($existing) {
                $existing->update([
                    'is_available' => $validated['is_available'],
                    'notes' => $validated['notes']
                ]);
                $updated++;
            } else {
                VendorAvailability::create([
                    'vendor_profile_id' => $vendorProfile->id,
                    'date' => $dateStr,
                    'is_available' => $validated['is_available'],
                    'notes' => $validated['notes']
                ]);
                $created++;
            }

            $startDate->addDay();
        }

        return response()->json([
            'success' => true,
            'message' => "Bulk operation completed. Created: {$created}, Updated: {$updated}"
        ]);
    }

    // Get Availability Stats (Vendor Only)
    public function vendorAvailabilityStats()
    {
        $vendorProfile = $this->getVendorProfile();
        if (!$vendorProfile) {
            return response()->json(['error' => 'Vendor profile not found'], 404);
        }

        $today = now()->format('Y-m-d');

        // Get future availability counts
        $availableCount = VendorAvailability::where('vendor_profile_id', $vendorProfile->id)
            ->where('date', '>=', $today)
            ->where('is_available', true)
            ->count();

        $unavailableCount = VendorAvailability::where('vendor_profile_id', $vendorProfile->id)
            ->where('date', '>=', $today)
            ->where('is_available', false)
            ->count();

        // Get next available date
        $nextAvailable = VendorAvailability::where('vendor_profile_id', $vendorProfile->id)
            ->where('date', '>=', $today)
            ->where('is_available', true)
            ->orderBy('date', 'asc')
            ->first();

        // Get upcoming unavailable periods
        $upcomingUnavailable = VendorAvailability::where('vendor_profile_id', $vendorProfile->id)
            ->where('date', '>=', $today)
            ->where('is_available', false)
            ->orderBy('date', 'asc')
            ->limit(5)
            ->get();

        return response()->json([
            'available_count' => $availableCount,
            'unavailable_count' => $unavailableCount,
            'next_available' => $nextAvailable ? $nextAvailable->date : null,
            'upcoming_unavailable' => $upcomingUnavailable
        ]);
    }

    // Check Date Availability (Public method - can be used by booking system)
    public function checkVendorAvailability(Request $request, $vendorId)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $availability = VendorAvailability::where('vendor_profile_id', $vendorId)
            ->where('date', $request->date)
            ->first();

        // If no record exists, assume available (default behavior)
        $isAvailable = $availability ? $availability->is_available : true;

        return response()->json([
            'date' => $request->date,
            'is_available' => $isAvailable,
            'notes' => $availability ? $availability->notes : null
        ]);
    }

    // Get Available Dates for a Vendor (Public method - for booking calendar)
    public function getVendorAvailableDates($vendorId, Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $availableDates = VendorAvailability::where('vendor_profile_id', $vendorId)
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->where('is_available', true)
            ->orderBy('date')
            ->pluck('date');

        return response()->json([
            'available_dates' => $availableDates,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
    }

    // Weekend Bulk Operations Helper
    public function vendorAvailabilityBulkWeekends(Request $request)
    {
        $vendorProfile = $this->getVendorProfile();
        if (!$vendorProfile) {
            return response()->json(['error' => 'Vendor profile not found'], 404);
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_available' => 'required|boolean',
            'notes' => 'nullable|string|max:255',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $processed = 0;

        while ($startDate->lte($endDate)) {
            // Only process weekends (Saturday = 6, Sunday = 0)
            if ($startDate->isWeekend()) {
                $dateStr = $startDate->format('Y-m-d');

                // Update or create weekend availability
                VendorAvailability::updateOrCreate(
                    [
                        'vendor_profile_id' => $vendorProfile->id,
                        'date' => $dateStr
                    ],
                    [
                        'is_available' => $validated['is_available'],
                        'notes' => $validated['notes'] ?: 'Weekend availability'
                    ]
                );
                $processed++;
            }

            $startDate->addDay();
        }

        return response()->json([
            'success' => true,
            'message' => "Weekend availability updated for {$processed} days"
        ]);
    }
}
