@extends('layouts.nhanvien')

@section('title', 'Tạo Hàng Loạt Hoạt động ĐCĐ')
@section('page_title', 'Tạo Suất Hàng Loạt (Địa chỉ đỏ)')

@php
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.hoatdong_ctxh.index'), 'title' => 'Hoạt động CTXH'],
        ['url' => '#', 'title' => 'Tạo Hàng Loạt ĐCĐ'],
    ];
@endphp

@push('styles')
<style>
    .form-section{background:#f8f9fa;padding:1.5rem;border-radius:12px;border:1px solid #e9ecef}
    .form-label{font-weight:600;color:#495057}
    .form-label i{width:20px;text-align:center}
    .form-control,.form-select{border-radius:8px}
    .btn{border-radius:8px;font-weight:500}
    .btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border:none}
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header py-3" style="background:linear-gradient(135deg,#f5576c 0%,#764ba2 100%)">
        <h5 class="mb-0 text-white">
            <i class="fa-solid fa-map-location-dot me-2"></i>
            Tạo Hàng Loạt Suất Hoạt Động (Địa Chỉ Đỏ)
        </h5>
    </div>

    <div class="card-body p-4">
        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Có lỗi xảy ra:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        <form action="{{ route('nhanvien.store_batch') }}" method="POST" novalidate>
            @csrf

            {{-- 1. Thông tin chung --}}
            <div class="form-section mb-4">
                <h6 class="text-primary mb-3">1. Thông tin chung (áp dụng cho mọi suất)</h6>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="TenHoatDongGoc" class="form-label">
                            Tên Hoạt động Gốc (sẽ tự thêm ngày) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="TenHoatDongGoc"
                            name="TenHoatDongGoc"
                            class="form-control @error('TenHoatDongGoc') is-invalid @enderror"
                            value="{{ old('TenHoatDongGoc') }}"
                            placeholder="Ví dụ: Tham quan Dinh Độc Lập"
                            required
                        >
                        @error('TenHoatDongGoc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="dot_id" class="form-label">Thuộc Đợt <span class="text-danger">*</span></label>
                        <select
                            id="dot_id"
                            name="dot_id"
                            class="form-select @error('dot_id') is-invalid @enderror"
                            required
                            aria-required="true"
                        >
                            <option value="">-- Chọn Đợt --</option>
                            @foreach($dots as $dot)
                                <option value="{{ $dot->id }}" {{ old('dot_id') == $dot->id ? 'selected' : '' }}>
                                    {{ $dot->TenDot }}
                                    [{{ \Carbon\Carbon::parse($dot->NgayBatDau)->format('d/m') }}
                                     → {{ \Carbon\Carbon::parse($dot->NgayKetThuc)->format('d/m/Y') }}]
                                </option>
                            @endforeach
                        </select>
                        @error('dot_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="diadiem_id" class="form-label">Địa điểm tổ chức <span class="text-danger">*</span></label>
                        <select
                            id="diadiem_id"
                            name="diadiem_id"
                            class="form-select @error('diadiem_id') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Chọn Địa điểm --</option>
                            @foreach($diadiems as $diadiem)
                                <option value="{{ $diadiem->id }}" {{ old('diadiem_id') == $diadiem->id ? 'selected' : '' }}>
                                    {{ $diadiem->TenDiaDiem }}
                                </option>
                            @endforeach
                        </select>
                        @error('diadiem_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="MaQuyDinhDiem" class="form-label">Quy định điểm <span class="text-danger">*</span></label>
                        <select
                            id="MaQuyDinhDiem"
                            name="MaQuyDinhDiem"
                            class="form-select @error('MaQuyDinhDiem') is-invalid @enderror"
                            required
                        >
                            <option value="">-- Chọn quy định điểm --</option>
                            @foreach($quyDinhDiems as $maDiem => $tenCongViec)
                                <option value="{{ $maDiem }}" {{ old('MaQuyDinhDiem') == $maDiem ? 'selected' : '' }}>
                                    {{ $maDiem }} - {{ $tenCongViec }}
                                </option>
                            @endforeach
                        </select>
                        @error('MaQuyDinhDiem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="DiaDiemCuThe" class="form-label">Địa điểm cụ thể (ghi chú) <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            id="DiaDiemCuThe"
                            name="DiaDiemCuThe"
                            class="form-control @error('DiaDiemCuThe') is-invalid @enderror"
                            value="{{ old('DiaDiemCuThe', 'Tập trung tại cổng chính') }}"
                            required
                        >
                        @error('DiaDiemCuThe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 2. Thông tin suất --}}
            <div class="form-section mb-4">
                <h6 class="text-success mb-3">2. Thông tin suất hàng loạt (tạo lặp lại theo ngày)</h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="NgayBatDauSuat" class="form-label">Tạo suất từ ngày <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            id="NgayBatDauSuat"
                            name="NgayBatDauSuat"
                            class="form-control @error('NgayBatDauSuat') is-invalid @enderror"
                            value="{{ old('NgayBatDauSuat') }}"
                            required
                        >
                        @error('NgayBatDauSuat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="NgayKetThucSuat" class="form-label">Đến hết ngày <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            id="NgayKetThucSuat"
                            name="NgayKetThucSuat"
                            class="form-control @error('NgayKetThucSuat') is-invalid @enderror"
                            value="{{ old('NgayKetThucSuat') }}"
                            required
                        >
                        @error('NgayKetThucSuat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="GioBatDau" class="form-label">Giờ bắt đầu (hàng ngày) <span class="text-danger">*</span></label>
                        <input
                            type="time"
                            id="GioBatDau"
                            name="GioBatDau"
                            class="form-control @error('GioBatDau') is-invalid @enderror"
                            value="{{ old('GioBatDau') }}"
                            required
                        >
                        @error('GioBatDau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="GioKetThuc" class="form-label">Giờ kết thúc (hàng ngày) <span class="text-danger">*</span></label>
                        <input
                            type="time"
                            id="GioKetThuc"
                            name="GioKetThuc"
                            class="form-control @error('GioKetThuc') is-invalid @enderror"
                            value="{{ old('GioKetThuc') }}"
                            required
                        >
                        @error('GioKetThuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="SoLuongMoiNgay" class="form-label">Số lượng (mỗi ngày) <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            id="SoLuongMoiNgay"
                            name="SoLuongMoiNgay"
                            class="form-control @error('SoLuongMoiNgay') is-invalid @enderror"
                            value="{{ old('SoLuongMoiNgay') }}"
                            min="1"
                            required
                            placeholder="0"
                        >
                        @error('SoLuongMoiNgay')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="{{ route('nhanvien.hoatdong_ctxh.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fa-solid fa-layer-group me-2"></i>Tạo Hàng Loạt
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
