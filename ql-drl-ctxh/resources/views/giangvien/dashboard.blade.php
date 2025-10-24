@extends('layouts.app')

@section('title','GiangVien')
@section('content')
  <h2>Chào {{ auth()->user()->TenDangNhap }} ({{ auth()->user()->VaiTro }})</h2>
  <p>Đây là trang tổng quan dành cho Giảng viên.</p>
  <form method="POST" action="{{ route('logout') }}">@csrf <button>Đăng xuất</button></form>
@endsection
