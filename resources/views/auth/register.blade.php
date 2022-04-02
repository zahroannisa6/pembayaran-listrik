@extends('layouts.auth')
@section('title', 'Register')
@push('styles')
  <style>
    body{
      background-color: #28b7ca;
    }
  </style>
@endpush
@section('content')
<div class="wrapper-auth-form d-flex justify-content-center align-items-center">
  <div class="auth-form container d-flex justify-content-center align-items-center">
    <div class="auth-form container h-100 d-flex justify-content-center align-items-center">
      <div div class="card p-3">
        <div class="card-body">
          <form action="{{ route('register') }}" method="POST" class="form-row align-items-center">
            @csrf
            <div class="col-12 col-md-6">
              <h1>Buat Akun</h1>
              <strong>Sudah menjadi pengguna?</strong> <a href="{{route('login')}}" class="text-decoration-none">Masuk</a>
              <div class="form-group mt-4 mb-3">
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Nama Lengkap" value="{{ old('email') }}">
                @error('nama')
                  <span class="invalid-feedback text-danger">{{ $message }}</span>    
                @enderror
              </div>
              <div class="form-group mb-3">
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Username" value="{{ old('username') }}">
                @error('username')
                  <span class="invalid-feedback text-danger">{{ $message }}</span>    
                @enderror
              </div>
              <div class="form-group mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email" value="{{ old('email') }}">
                @error('email')
                  <span class="invalid-feedback text-danger">{{ $message }}</span>    
                @enderror
              </div>
              <div class="form-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" autocomplete="current-password">
                @error('password')
                  <span class="invalid-feedback text-danger">{{ $message }}</span>    
                @enderror
              </div>
              <div class="form-group mb-3">
                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="passwordConfirmation" placeholder="Konfirmasi Password">
                @error('password_confirmation')
                  <span class="invalid-feedback text-danger">{{$message}}</span>    
                @enderror
              </div>
              <button class="btn btn-primary-custom btn-block" type="submit">Daftar</button>
            </div>
            <div class="col-md-6 text-center d-none d-md-flex">
              <img src="{{ asset('assets/img/ilustrasi/ilustrasi-daftar.png') }}" width="420" height="380" class="img-fluid">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection