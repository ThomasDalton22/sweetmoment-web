@extends('layouts.user')
@section('container')

    <section class="profile" id="profile">
        <form action="{{ route('user.profile.update', $user->id) }}" class="form-berita" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row gy-4" style="display: flex; gap:1rem">
              <div class="col-10" style="margin: auto;">
                  <label for="name">Nama</label>
                  <input type="text" name="name" value="{{$user->name}}" class="form-control">
              </div>
              <div class="col-10" style="margin: auto;">
                  <label for="email">Email</label>
                  <input type="text" name="email" value="{{$user->email}}" class="form-control">
              </div>
              <div class="col-10" style="margin: auto;">
                  <label for="address">Alamat</label>
                  <input type="text" name="address" value="{{$user->address}}" class="form-control">
              </div>
              <div class="col-10" style="margin: auto; margin-bottom:2rem">
                  <label for="password">Password</label>
                  <input type="password" name="password" class="form-control">
              </div>
              <button class="btn-editprofile" type="submit" style="margin: auto; width: 20%; border-radius:20px">Edit</button>
            </div>
        </form>
    </section>

@endsection