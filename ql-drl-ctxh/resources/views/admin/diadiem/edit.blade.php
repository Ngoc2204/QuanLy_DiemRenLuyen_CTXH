@extends('layouts.admin')

@section('title', 'Chỉnh sửa Địa điểm')

@section('content')
<div class="container-fluid">
    <h4 class="my-4">Chỉnh sửa: {{ $diadiem->TenDiaDiem }}</h4>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Thông tin Địa điểm</h5>
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

            <form action="{{ route('admin.diadiem.update', ['diadiem' => $diadiem->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="TenDiaDiem" class="form-label">Tên Địa điểm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="TenDiaDiem" name="TenDiaDiem" value="{{ old('TenDiaDiem', $diadiem->TenDiaDiem) }}" required>
                </div>

                <div class="mb-3">
                    <label for="DiaChi" class="form-label">Địa chỉ</label>
                    <textarea class="form-control" id="DiaChi" name="DiaChi" rows="2">{{ old('DiaChi', $diadiem->DiaChi) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="GiaTien" class="form-label">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="GiaTien" name="GiaTien" value="{{ old('GiaTien', $diadiem->GiaTien) }}" required min="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="TrangThai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select" id="TrangThai" name="TrangThai">
                                <option value="KhaDung" {{ old('TrangThai', $diadiem->TrangThai) == 'KhaDung' ? 'selected' : '' }}>Khả dụng</option>
                                <option value="TamNgung" {{ old('TrangThai', $diadiem->TrangThai) == 'TamNgung' ? 'selected' : '' }}>Tạm ngưng</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.diadiem.index') }}" class="btn btn-secondary">Hủy</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection