<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\wedding;
use App\Models\message;
use App\Models\party;
use App\Models\Portfolio;
use App\Models\Testimony;
use App\Models\vendor_offers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use FontLib\Table\Type\name;

class UserController extends Controller
{

    public function user_testimony(){

        return view('user.testimony');
    }


    public function update_testimony(Request $request)
    {
        // Validasi input
        $request->validate([
            'testimony' => 'required|string',
            'rating' => 'required|integer|min:1|max:5', 
        ]);
    
        $testimony = Testimony::where('user', auth()->user()->name)->first();
    
        if ($testimony) {
            // Update jika testimoni sudah ada
            $testimony->testimony = $request->testimony;
            $testimony->rating = $request->rating;
        } else {
            // Buat testimoni baru jika belum ada
            $testimony = new Testimony();
            $testimony->user = auth()->user()->name;
            $testimony->testimony = $request->testimony;
            $testimony->rating = $request->rating;
        }
    
        // Simpan ke database
        $testimony->save();
    
        return redirect()->route('user.home')->with('success', 'Testimony berhasil disimpan!');
    }
    


    public function profile(){

        $user = auth()->user();

        return view('user.profile.index', compact('user'));
    }

    public function editprofile($id){
        $user = User::findOrFail($id);

        return view('user.profile.edit', compact('user'));
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
        
        return redirect()->route('user.profile')->with('success', 'Data telah diperbaharui.');
    }



    public function chat(){

        
        $vendors = User::where('role', 'vendor')->get();
 
        return view('user.chat.chatlist', compact('vendors'));
    }
    

    public function chatbox($id)
    {

        $vendor = User::findOrFail($id);

        $messages = Message::where(function($query) use ($vendor) {
            $query->where('from_user_id', auth()->id())
                ->where('to_user_id', $vendor->id);
        })
        ->orWhere(function($query) use ($vendor) {
            $query->where('from_user_id', $vendor->id)
                ->where('to_user_id', auth()->id());
        })
        ->orderBy('created_at', 'asc') 
        ->get();

        return view('user.chat.chatbox', compact('vendor', 'messages'));
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
        })->orderBy('created_at', 'asc') // Urutkan berdasarkan waktu
          ->get();


          return redirect()->route('user.chatbox', $receiver->id)->with([
            'messages' => $messages, 
            'user' => $receiver, 
        ]);
    }



    public function home(){
        $vendor_offers = vendor_offers::with('user')->get();

        return view('user.home', compact('vendor_offers'));
    }

    public function download_vo(Request $request)
    {
    // Validasi input form
    $validated = $request->validate([
        'jenispenawaran' => 'required',
        'budget' => 'required|numeric',
        'catatan' => 'required|string',
        'user_id' => 'required|exists:users,id',  
    ]);


    $vendor_offer = vendor_offers::where('user_id', $validated['user_id']) 
        ->where('budget', $validated['budget']) 
        ->where('catatan', $validated['catatan']) 
        ->where('jenispenawaran', $validated['jenispenawaran']) 
        ->first(); 

 
    if (!$vendor_offer) {
        return redirect()->back()->with('error', 'Vendor offer not found.');
    }

    $pdf = PDF::loadView('user.filepdf', compact('vendor_offer'));  

    return $pdf->download('vendor_offer_' . time() . '.pdf');

    }

    public function weddingorganizer(){
        return view('user.wo.index');
    }

    public function submit_wo(Request $request){

        $validated = $request->validate([
            'date' => 'required|date',
            'budget' => 'required|numeric',
            'catatan' => 'required|string',
        ]);

        $wedding = wedding::create([
            'date' => $validated['date'],
            'budget' => $validated['budget'],
            'catatan' => $validated['catatan'],
            'user_id' => auth()->id(),  
        ]);

        return redirect()->route('user.weddingorganizer')->with('success', 'Pengajuan WO berhasil dilakukan!');
    }

    
    public function partyorganizer(){
        return view('user.po.index');
    }

    public function submit_po(Request $request)
    {
        // Validasi input yang diterima
        $validated = $request->validate([
            'date' => 'required|date',
            'budget' => 'required|numeric',
            'catatan' => 'required|string',
        ]);

        // Menyimpan data pengajuan ke database
        $party = party::create([
            'date' => $validated['date'],
            'budget' => $validated['budget'],
            'catatan' => $validated['catatan'],
            'user_id' => auth()->id(),  
        ]);

        return redirect()->route('user.partyorganizer')->with('success', 'Pengajuan PO berhasil dilakukan!');
    }


    public function riwayat(){
        return view('user.riwayat.index');
    }

    public function porto_vendor($vendorName)
    {
        
        $vendor = User::where('name', $vendorName)->firstOrFail();
        $portfolios = Portfolio::where('vendor', $vendorName)->get();
        return view('user.portovendor', compact('vendor', 'portfolios'));
    }

}
