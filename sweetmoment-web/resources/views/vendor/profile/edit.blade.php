@extends('layouts.vendor')
@section('container')

<section class="profile" id="profile">
        <form action="{{ route('vendor.profile.update', $user->id) }}" class="form-berita" method="post" enctype="multipart/form-data">

            @csrf
            <div class="row gy-4">
              <div class="col-12">
                  <label for="name">Nama</label>
                  <input type="text" name="name" value="{{$user->name}}" class="form-control">
              </div>
              <div class="col-12">
                  <label for="email">Email</label>
                  <input type="text" name="email" value="{{$user->email}}" class="form-control">
              </div>
              <div class="col-12">
                  <label for="address">Alamat</label>
                  <input type="text" name="address" value="{{$user->address}}" class="form-control">
              </div>
              <div class="col-12">
                  <label for="password">Password</label>
                  <input type="password" name="password" class="form-control">
              </div>

              <button class="btn-editprofile" type="submit">Edit</button>
            </div>
        </form>


        </section>

@endsection