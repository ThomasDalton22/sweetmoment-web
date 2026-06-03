@extends('layouts.user')
@section('container')

<div class="page-content page-container" id="page-content">
        <div class="padding">
          <div class="row container d-flex justify-content-center">
            <div class="col-md-6">
              <div class="card card-bordered">
                <div class="card-header">
                  <h4 class="card-title"><strong>{{ $vendor->name }}</strong></h4>
                </div>

                <!-- Container untuk chat -->
                <div id="chat-content" class="ps-container ps-theme-default ps-active-y" style="overflow-y: scroll; height: 400px;">
                  <!-- Pesan yang ada -->
                  @foreach($messages as $message)
                    <div class="media media-chat 
                        @if($message->from_user_id == auth()->id()) 
                            media-chat-reverse 
                        @else 
                            media-chat 
                        @endif">
                        <img class="avatar" src="{{ $message->sender->avatar ?? 'https://img.icons8.com/color/36/000000/administrator-male.png' }}" alt="...">
                        <div class="media-body">
                            <!-- Tampilkan pesan teks jika ada -->
                            @if($message->message)
                                <p>{{ $message->message }} <span class="message-time">{{ $message->created_at->timezone('Asia/Jakarta')->format('H:i') }}</span></p>
                            @endif

                            <!-- Tampilkan file jika ada -->
                            @if($message->file)
                                <div class="file-attachment">
                                    @php
                                        $fileExtension = pathinfo($message->file, PATHINFO_EXTENSION);
                                        $fileUrl = Storage::url('uploads/messages/' . basename($message->file));
                                    @endphp
                                    
                                    {{-- Jika file adalah gambar --}}
                                    @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) 
                                        <img src="{{ $fileUrl }}" alt="File" style="max-width: 200px;">
                                        <span class="message-time">{{ $message->created_at->timezone('Asia/Jakarta')->format('H:i') }}</span>
                                    {{-- Jika file adalah PDF --}}
                                    @elseif($fileExtension == 'pdf') 
                                        <a href="{{ $fileUrl }}" target="_blank">
                                            {{ basename($message->file) }} <span class="message-time">{{ $message->created_at->timezone('Asia/Jakarta')->format('H:i') }}</span>
                                        </a>

                                    {{-- Untuk file selain gambar dan PDF, tampilkan sebagai link download --}}
                                    @else
                                        <a href="{{ $fileUrl }}" target="_blank">Download File</a>
                                        <span class="message-time">{{ $message->created_at->timezone('Asia/Jakarta')->format('H:i') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                  @endforeach
                </div>

                <!-- Form untuk mengirim pesan -->
                <form action="{{ route('user.sendMessage', $vendor->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf  
                  <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 0px; right: 2px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 2px;"></div></div></div>
                    <div class="publisher bt-1 border-light">
                      <img class="avatar avatar-xs" src="https://img.icons8.com/color/36/000000/administrator-male.png" alt="...">
                      <input class="publisher-input" type="text" placeholder="Write something" name="message">
                      <input type="file" name="file">
                    </div>
                    <div class="publisher bt-1 border-light">
                        <button class="publisher-btn text-info" type="submit" style="width: 100%;">Send</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection