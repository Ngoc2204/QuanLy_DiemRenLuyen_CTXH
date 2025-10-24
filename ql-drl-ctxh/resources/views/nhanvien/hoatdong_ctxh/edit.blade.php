@extends('layouts.nhanvien')

@section('title', 'Sửa Hoạt động CTXH')
@section('page_title', 'Chỉnh sửa Hoạt động CTXH')

@php
    // Breadcrumbs
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.hoatdong_ctxh.index'), 'title' => 'Hoạt động CTXH'],
        ['url' => '#', 'title' => 'Chỉnh sửa'],
    ];
@endphp

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="mb-0">
            <i class="fa-solid fa-pen-to-square me-2"></i>
            Chỉnh sửa Hoạt động: {{ $hoatdong_ctxh->TenHoatDong }}
        </h5>
    </div>
    <div class="card-body p-4">
        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fa-solid fa-exclamation-circle me-2 mt-1"></i>
                    <div class="flex-grow-1">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('nhanvien.hoatdong_ctxh.update', $hoatdong_ctxh->MaHoatDong) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')

            {{-- Thông tin cơ bản --}}
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-primary mb-0">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Thông tin cơ bản
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MaHoatDong" class="form-label">
                                <i class="fa-solid fa-hashtag me-1 text-muted"></i>
                                Mã Hoạt động
                            </label>
                            <input type="text" 
                                   class="form-control bg-light" 
                                   id="MaHoatDong" 
                                   value="{{ $hoatdong_ctxh->MaHoatDong }}" 
                                   disabled 
                                   readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="LoaiHoatDong" class="form-label">
                                <i class="fa-solid fa-tag me-1 text-muted"></i>
                                Loại Hoạt động 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="LoaiHoatDong" 
                                   name="LoaiHoatDong" 
                                   value="{{ old('LoaiHoatDong', $hoatdong_ctxh->LoaiHoatDong) }}" 
                                   required 
                                   list="loaihd-list"
                                   placeholder="Chọn hoặc nhập loại hoạt động">
                            <datalist id="loaihd-list">
                                <option value="Tình nguyện">
                                <option value="Học thuật">
                                <option value="Văn hóa - Văn nghệ">
                                <option value="Thể dục - Thể thao">
                                <option value="Khác">
                            </datalist>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="TenHoatDong" class="form-label">
                                <i class="fa-solid fa-heading me-1 text-muted"></i>
                                Tên Hoạt động 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="TenHoatDong" 
                                   name="TenHoatDong" 
                                   value="{{ old('TenHoatDong', $hoatdong_ctxh->TenHoatDong) }}" 
                                   required
                                   placeholder="Nhập tên hoạt động">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MoTa" class="form-label">
                                <i class="fa-solid fa-align-left me-1 text-muted"></i>
                                Mô tả
                            </label>
                            <textarea class="form-control" 
                                      id="MoTa" 
                                      name="MoTa" 
                                      rows="4"
                                      placeholder="Nhập mô tả chi tiết về hoạt động...">{{ old('MoTa', $hoatdong_ctxh->MoTa) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="DiaDiem" class="form-label">
                                <i class="fa-solid fa-location-dot me-1 text-muted"></i>
                                Địa điểm
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="DiaDiem" 
                                   name="DiaDiem" 
                                   value="{{ old('DiaDiem', $hoatdong_ctxh->DiaDiem) }}"
                                   placeholder="Nhập địa điểm tổ chức">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thời gian --}}
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-success mb-0">
                        <i class="fa-solid fa-calendar-days me-2"></i>
                        Thời gian tổ chức
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ThoiGianBatDau" class="form-label">
                                <i class="fa-solid fa-calendar-plus me-1 text-success"></i>
                                Thời gian Bắt đầu 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" 
                                   class="form-control" 
                                   id="ThoiGianBatDau" 
                                   name="ThoiGianBatDau" 
                                   value="{{ old('ThoiGianBatDau', $hoatdong_ctxh->ThoiGianBatDau ? $hoatdong_ctxh->ThoiGianBatDau->format('Y-m-d\TH:i') : '') }}" 
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ThoiGianKetThuc" class="form-label">
                                <i class="fa-solid fa-calendar-xmark me-1 text-danger"></i>
                                Thời gian Kết thúc 
                                <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" 
                                   class="form-control" 
                                   id="ThoiGianKetThuc" 
                                   name="ThoiGianKetThuc" 
                                   value="{{ old('ThoiGianKetThuc', $hoatdong_ctxh->ThoiGianKetThuc ? $hoatdong_ctxh->ThoiGianKetThuc->format('Y-m-d\TH:i') : '') }}" 
                                   required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="ThoiHanHuy" class="form-label">
                                <i class="fa-solid fa-clock-rotate-left me-1 text-warning"></i>
                                Thời hạn Hủy đăng ký
                            </label>
                            <input type="datetime-local" 
                                   class="form-control" 
                                   id="ThoiHanHuy" 
                                   name="ThoiHanHuy" 
                                   value="{{ old('ThoiHanHuy', $hoatdong_ctxh->ThoiHanHuy ? $hoatdong_ctxh->ThoiHanHuy->format('Y-m-d\TH:i') : '') }}">
                            <small class="form-text text-muted">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Để trống nếu không cho phép sinh viên hủy đăng ký.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Điểm số và Số lượng --}}
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-warning mb-0">
                        <i class="fa-solid fa-chart-simple me-2"></i>
                        Điểm số & Số lượng
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Diem" class="form-label">
                                <i class="fa-solid fa-star me-1 text-warning"></i>
                                Điểm CTXH 
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control" 
                                       id="Diem" 
                                       name="Diem" 
                                       value="{{ old('Diem', $hoatdong_ctxh->Diem) }}" 
                                       min="0"
                                       step="0.01"
                                       required
                                       placeholder="0">
                                <span class="input-group-text">điểm</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="SoLuong" class="form-label">
                                <i class="fa-solid fa-users me-1 text-primary"></i>
                                Số lượng tối đa 
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control" 
                                       id="SoLuong" 
                                       name="SoLuong" 
                                       value="{{ old('SoLuong', $hoatdong_ctxh->SoLuong) }}" 
                                       min="1" 
                                       required
                                       placeholder="0">
                                <span class="input-group-text">SV</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="current_registered" class="form-label">
                                <i class="fa-solid fa-user-check me-1 text-success"></i>
                                Đã đăng ký
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control bg-light" 
                                       id="current_registered" 
                                       value="{{ $hoatdong_ctxh->sinhVienDangKy->count() }}" 
                                       disabled 
                                       readonly>
                                <span class="input-group-text">SV</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MaQuyDinhDiem" class="form-label">
                                <i class="fa-solid fa-clipboard-list me-1 text-info"></i>
                                Quy định điểm liên quan 
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="MaQuyDinhDiem" name="MaQuyDinhDiem" required>
                                <option value="">-- Chọn quy định điểm --</option>
                                @foreach($quyDinhDiems as $maDiem => $tenCongViec)
                                    <option value="{{ $maDiem }}" {{ old('MaQuyDinhDiem', $hoatdong_ctxh->MaQuyDinhDiem) == $maDiem ? 'selected' : '' }}>
                                        {{ $maDiem }} - {{ $tenCongViec }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Warning Box --}}
            <div class="alert alert-info border-0 shadow-sm mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-lightbulb fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading mb-2">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Lưu ý khi chỉnh sửa
                        </h6>
                        <ul class="mb-0 small">
                            <li>Các thay đổi sẽ ảnh hưởng đến thông tin hiển thị với sinh viên</li>
                            <li>Nếu giảm số lượng tối đa, cần kiểm tra số lượng sinh viên đã đăng ký</li>
                            <li>Thay đổi thời gian có thể ảnh hưởng đến lịch đăng ký của sinh viên</li>
                            <li>Trường có dấu <span class="text-danger">*</span> là bắt buộc</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="{{ route('nhanvien.hoatdong_ctxh.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>
                <div>
                    <button type="reset" class="btn btn-outline-warning me-2">
                        <i class="fa-solid fa-rotate-left me-2"></i>Đặt lại
                    </button>
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="fa-solid fa-save me-2"></i>Lưu thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.form-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.section-header h6 {
    font-weight: 600;
}

.section-header hr {
    border-top: 2px solid currentColor;
    opacity: 0.2;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.625rem 0.875rem;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-control:disabled,
.form-control[readonly] {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    font-weight: 500;
}

.btn {
    border-radius: 8px;
    padding: 0.625rem 1.25rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #653a8b 100%);
}

.alert {
    border-radius: 12px;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.text-muted {
    color: #6c757d !important;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* Animation for form validation */
.form-control:invalid:not(:placeholder-shown) {
    border-color: #dc3545;
}

.form-control:valid:not(:placeholder-shown) {
    border-color: #28a745;
}

/* Datalist styling */
input[list]::-webkit-calendar-picker-indicator {
    opacity: 0.6;
}

/* Number input buttons */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    opacity: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('editForm');
    const soLuongInput = document.getElementById('SoLuong');
    const currentRegistered = {{ $hoatdong_ctxh->sinhVienDangKy->count() }};

    // Check số lượng khi thay đổi
    soLuongInput.addEventListener('change', function() {
        const newValue = parseInt(this.value);
        if (newValue < currentRegistered) {
            if (!confirm(`Số lượng mới (${newValue}) nhỏ hơn số lượng đã đăng ký (${currentRegistered}). Bạn có chắc chắn muốn tiếp tục?`)) {
                this.value = currentRegistered;
            }
        }
    });

    // Validate thời gian
    const thoiGianBatDau = document.getElementById('ThoiGianBatDau');
    const thoiGianKetThuc = document.getElementById('ThoiGianKetThuc');
    const thoiHanHuy = document.getElementById('ThoiHanHuy');

    function validateDates() {
        const start = new Date(thoiGianBatDau.value);
        const end = new Date(thoiGianKetThuc.value);
        const cancel = thoiHanHuy.value ? new Date(thoiHanHuy.value) : null;

        if (end <= start) {
            thoiGianKetThuc.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu');
        } else {
            thoiGianKetThuc.setCustomValidity('');
        }

        if (cancel && cancel >= start) {
            thoiHanHuy.setCustomValidity('Thời hạn hủy phải trước thời gian bắt đầu');
        } else {
            thoiHanHuy.setCustomValidity('');
        }
    }

    thoiGianBatDau.addEventListener('change', validateDates);
    thoiGianKetThuc.addEventListener('change', validateDates);
    thoiHanHuy.addEventListener('change', validateDates);

    // Confirm before submit
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endsection