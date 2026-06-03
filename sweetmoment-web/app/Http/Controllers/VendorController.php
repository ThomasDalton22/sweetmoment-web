<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\wedding;
use App\Models\message;
use App\Models\party;
use App\Models\Portfolio;
use App\Models\vendor_offers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class VendorController extends Controller
{
    public function profile(){

        $user = auth()->user();

        return view('vendor.profile.index', compact('user'));
    }

    public function editprofile($id){
        $user = User::findOrFail($id);

        return view('vendor.profile.edit', compact('user'));
    }
    public function updateprofile(Request $request, $id) {
        $user = User::findOrFail($id);

    
        $user->name = $request->name;
        $user->address = $request->address;
        $user->email = $request->email;
    
        // Debugging: pastikan password diubah
        if ($request->filled('password')) {

            $user->password = Hash::make($request->password);
        }
    
        $user->save();
        
        return redirect()->route('vendor.profile');
    }
    public function chat(){

        
        $users = User::where('role', 'user')->get();
 
        return view('vendor.chat.chatlist', compact('users'));
    }

    public function chatbox($id)
    {

        $user = User::findOrFail($id);

        $messages = Message::where(function($query) use ($user) {
            $query->where('from_user_id', auth()->id())
                ->where('to_user_id', $user->id);
        })
        ->orWhere(function($query) use ($user) {
            $query->where('from_user_id', $user->id)
                ->where('to_user_id', auth()->id());
        })
        ->orderBy('created_at', 'asc') 
        ->get();

        return view('vendor.chat.chatbox', compact('user', 'messages'));
    }

    public function sendMessage(Request $request, $id){
        $request->validate([
            'message' => 'nullable|string|max:255',  
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,zip|max:10240',
        ]);

        $receiver = User::findOrFail($id);

        $filePath = null;
        if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public/uploads/messages', $fileName); 
        }

        $messageContent = $request->message ?? '';

        $message = Message::create([
            'from_user_id' => Auth::id(), 
            'to_user_id' => $receiver->id, 
            'message' => $messageContent, 
            'file' => $filePath, 
        ]);


        $messages = Message::where(function($query) use ($receiver) {
            $query->where('from_user_id', auth()->id())
                  ->where('to_user_id', $receiver->id);
        })->orWhere(function($query) use ($receiver) {
            $query->where('from_user_id', $receiver->id)
                  ->where('to_user_id', auth()->id());
        })->orderBy('created_at', 'asc') 
          ->get();

          return redirect()->route('vendor.chatbox', $receiver->id)->with([
            'messages' => $messages, 
            'user' => $receiver, 
        ]);
    }


    public function home(){
       
    $weddings = Wedding::with('user')->get(); 
    $parties = Party::with('user')->get(); 

    return view('vendor.home', compact('weddings', 'parties'));

    }

    public function download_wo(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'budget' => 'required|numeric',
            'catatan' => 'required|string',
            'user_id' => 'required|exists:users,id',  
        ]);

        $wedding = Wedding::where('user_id', $validated['user_id']) 
            ->where('budget', $validated['budget']) 
            ->where('catatan', $validated['catatan']) 
            ->first(); 

    
        if (!$wedding) {
            return redirect()->back()->with('error', 'Wedding offer not found.');
        }

        $pdf = PDF::loadView('vendor.filepdf_wo', compact('wedding'));  

        return $pdf->download('wedding_offer_' . time() . '.pdf');
        }


        public function download_po(Request $request)
        {
            // Validasi input form
            $validated = $request->validate([
                'date' => 'required|date',
                'budget' => 'required|numeric',
                'catatan' => 'required|string',
                'user_id' => 'required|exists:users,id',  
            ]);
    
    
            $party = party::where('user_id', $validated['user_id']) 
                ->where('budget', $validated['budget']) 
                ->where('catatan', $validated['catatan']) 
                ->first(); 
    
        
            if (!$party) {
                return redirect()->back()->with('error', 'Party offer not found.');
            }
    
            $pdf = PDF::loadView('vendor.filepdf_po', compact('party'));  
    
            return $pdf->download('party_offer_' . time() . '.pdf');
            }

    
    public function customer()
    {
        $vendor_offers = vendor_offers::where('user_id', auth()->id())->get();
        return view('vendor.customer.index', compact('vendor_offers'));
    }
            

    public function add_customer(){
        return view('vendor.customer.add');
    }

    public function store_customer(Request $request)
    {
        $this->validate($request, [
            'jenispenawaran' => 'required',
            'budget' => 'required',
            'catatan' => 'required', 
        ]);

        $post = new vendor_offers();
        $post->jenispenawaran = $request->jenispenawaran;
        $post->budget = $request->budget;
        $post->catatan = $request->catatan;
        $post->user_id = auth()->id();  
        $post->save();

        return redirect()->route('vendor.customer')->with('success', 'Penawaran Berhasil Diajukan');
    }

    public function delete_customer($id){
        $vendor_offers = vendor_offers::find($id);
        $vendor_offers->delete();

        return redirect()->route('vendor.customer')->with('success', 'Penawaran Berhasil Dihapus');
    }

    public function portfolio_vendor()
    {
        $portfolios = Portfolio::all();
        return view('vendor.portfolio.index', compact('portfolios'));
    }

    public function portfolio_add()
    {
        return view('vendor.portfolio.add');
    }

    public function store_portfolio(Request $request)
    {
    $this->validate($request, [
        'description' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg', 
    ]);

    $post = new Portfolio();
    $post->description = $request->description;
    $post->vendor = auth()->user()->name;
    $image_name = null;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $image_name = time() . $image->getClientOriginalName();
        $image->move(public_path('uploads/posts/portfolio'), $image_name);
    }
    $image_path = $image_name ? 'uploads/posts/portfolio/' . $image_name : '';

    $post->image = $image_path;
    $post->save();



    return redirect()->route('vendor.portfolio');
    }

    public function portfolio_delete($id)
    {
        $portfolios = Portfolio::find($id);
        $portfolios->delete();
        return redirect()->route('vendor.portfolio');
    }


}
