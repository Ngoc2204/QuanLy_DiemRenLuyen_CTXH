@extends('layouts.admin') {{-- Giả sử layout Admin của bạn là 'layouts.admin' --}}

@section('title', 'Tạo Đợt (Địa chỉ đỏ) mới')

@section('content')
<div class="container-fluid">
    <h4 class="my-4">Tạo Đợt (Địa chỉ đỏ) mới</h4>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Thông tin Đợt</h5>
        </div>
        <div class="card-body">
            
            <!-- Hiển thị lỗi Validate -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.dotdiachido.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="TenDot" class="form-label">Tên Đợt <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="TenDot" name="TenDot" value="{{ old('TenDot') }}" placeholder="VD: Chiến dịch Địa chỉ đỏ T11/2025" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="NgayBatDau" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="NgayBatDau" name="NgayBatDau" value="{{ old('NgayBatDau') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="mb-3">
                            <label for="NgayKetThuc" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="NgayKetThuc" name="NgayKetThuc" value="{{ old('NgayKetThuc') }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="TrangThai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select class="form-select" id="TrangThai" name="TrangThai">
                        <option value="SapDienRa" {{ old('TrangThai') == 'SapDienRa' ? 'selected' : '' }}>Sắp diễn ra</option>
                        <option value="DangDienRa" {{ old('TrangThai') == 'DangDienRa' ? 'selected' : '' }}>Đang diễn ra</option>
                        <option value="DaKetThuc" {{ old('TrangThai') == 'DaKetThuc' ? 'selected' : '' }}>Đã kết thúc</option>
                    </select>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Lưu Đợt</button>
                    <a href="{{ route('admin.dotdiachido.index') }}" class="btn btn-secondary">Hủy</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection