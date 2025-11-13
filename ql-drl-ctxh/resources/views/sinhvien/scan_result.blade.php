@extends('layouts.sinhvien')
@section('title', 'Kết quả điểm danh')

@section('content')
<div class="container py-4">
  @php
    $status = $status ?? 'error';
    $message = $message ?? 'Lỗi không xác định.';
    $map = [
      'success' => ['alert-success','<i class="fas fa-check-circle me-2"></i>'],
      'warning' => ['alert-warning','<i class="fas fa-exclamation-triangle me-2"></i>'],
      'error'   => ['alert-danger','<i class="fas fa-times-circle me-2"></i>'],
    ];
    [$cls,$icon] = $map[$status] ?? $map['error'];
  @endphp

  <div class="alert {{ $cls }} d-flex align-items-center" role="alert">
    {!! $icon !!} <div>{!! $message !!}</div>
  </div>

  <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Quay lại
  </a>
</div>
@endsection
