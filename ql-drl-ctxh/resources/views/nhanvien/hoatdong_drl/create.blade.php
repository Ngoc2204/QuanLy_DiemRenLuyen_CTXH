@extends('layouts.nhanvien')

{{-- Đổi Title --}}
@section('title', 'Thêm Hoạt động DRL')
@section('page_title', 'Thêm mới Hoạt động DRL')

@php
// Đổi route breadcrumb
$breadcrumbs = [
['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
['url' => route('nhanvien.hoatdong_drl.index'), 'title' => 'Hoạt động DRL'],
['url' => '#', 'title' => 'Thêm mới'],
];
@endphp

@push('styles')
{{-- Giữ nguyên CSS --}}
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
        display: flex;
        /* Align icon and text */
        align-items: center;
    }

    .form-label i {
        width: 20px;
        /* Fixed width for icons */
        text-align: center;
        /* Center icon */
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

    .form-group {
        margin-bottom: 1rem;
        /* Add consistent spacing */
    }
</style>
@endpush


@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="mb-0">
            {{-- Đổi Icon --}}
            <i class="fa-solid fa-clipboard-check me-2"></i>
            Thêm mới Hoạt động DRL
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

        {{-- Đổi route --}}
        <form action="{{ route('nhanvien.hoatdong_drl.store') }}" method="POST" id="createForm" novalidate>
            @csrf

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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="TenHoatDong" class="form-label">
                                <i class="fa-solid fa-heading me-1 text-muted"></i>
                                Tên Hoạt động <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="TenHoatDong"
                                name="TenHoatDong"
                                value="{{ old('TenHoatDong') }}"
                                required
                                placeholder="Nhập tên hoạt động">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="LoaiHoatDong" class="form-label">
                                <i class="fa-solid fa-tag me-1 text-muted"></i>
                                Loại Hoạt động <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="LoaiHoatDong"
                                name="LoaiHoatDong"
                                value="{{ old('LoaiHoatDong', 'Khác') }}"
                                required
                                list="loaihd-list-drl" {{-- Đổi ID datalist --}}
                                placeholder="Chọn hoặc nhập loại hoạt động">
                            <datalist id="loaihd-list-drl"> {{-- Đổi ID datalist --}}
                                <option value="Học tập">
                                <option value="Nghiên cứu khoa học">
                                <option value="Văn hóa - Văn nghệ">
                                <option value="Thể dục - Thể thao">
                                <option value="Kỹ năng">
                                <option value="Cộng đồng">
                                <option value="Khác">
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="DiaDiem" class="form-label">
                                <i class="fa-solid fa-location-dot me-1 text-muted"></i>
                                Địa điểm <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control"
                                id="DiaDiem"
                                name="DiaDiem"
                                value="{{ old('DiaDiem') }}"
                                placeholder="Nhập địa điểm tổ chức"
                                required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MaGV" class="form-label">
                                <i class="fa-solid fa-user-tie me-1 text-muted"></i>
                                Giảng viên phụ trách
                                </label>
                            <select class="form-select" id="MaGV" name="MaGV">
                                <option value="">-- Chọn giảng viên (Không bắt buộc) --</option>
                                {{-- Biến $giangViens được truyền từ Controller --}}
                                @foreach($giangViens ?? [] as $maGV => $tenGV)
                                <option value="{{ $maGV }}" {{ old('MaGV') == $maGV ? 'selected' : '' }}>
                                    {{ $tenGV }} ({{ $maGV }})
                                    </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MoTa" class="form-label">
                                <i class="fa-solid fa-align-left me-1 text-muted"></i>
                                Mô tả <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control"
                                id="MoTa"
                                name="MoTa"
                                rows="4"
                                placeholder="Nhập mô tả chi tiết về hoạt động..." required>{{ old('MoTa') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thời gian & Học Kỳ --}}
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-success mb-0">
                        <i class="fa-solid fa-calendar-days me-2"></i>
                        Thời gian & Học kỳ
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    {{-- Thêm Học Kỳ --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MaHocKy" class="form-label">
                                <i class="fa-solid fa-graduation-cap me-1 text-info"></i>
                                Học kỳ áp dụng <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="MaHocKy" name="MaHocKy" required>
                                <option value="">-- Chọn học kỳ --</option>
                                {{-- Giả sử Controller truyền biến $hocKys --}}
                                @foreach($hocKys ?? [] as $maHK => $tenHK)
                                <option value="{{ $maHK }}" {{ old('MaHocKy') == $maHK ? 'selected' : '' }}>
                                    {{ $tenHK }} ({{ $maHK }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ThoiGianBatDau" class="form-label">
                                <i class="fa-solid fa-calendar-plus me-1 text-success"></i>
                                Thời gian Bắt đầu <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local"
                                class="form-control"
                                id="ThoiGianBatDau"
                                name="ThoiGianBatDau"
                                value="{{ old('ThoiGianBatDau') }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ThoiGianKetThuc" class="form-label">
                                <i class="fa-solid fa-calendar-xmark me-1 text-danger"></i>
                                Thời gian Kết thúc <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local"
                                class="form-control"
                                id="ThoiGianKetThuc"
                                name="ThoiGianKetThuc"
                                value="{{ old('ThoiGianKetThuc') }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="ThoiHanHuy" class="form-label">
                                <i class="fa-solid fa-clock-rotate-left me-1 text-warning"></i>
                                Thời hạn Hủy đăng ký <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local"
                                class="form-control"
                                id="ThoiHanHuy"
                                name="ThoiHanHuy"
                                value="{{ old('ThoiHanHuy') }}"
                                required>
                            
                        </div>
                    </div>
                </div>
            </div>

            {{-- Số lượng & Quy định điểm --}}
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-warning mb-0">
                        <i class="fa-solid fa-users-gear me-2"></i>
                        Số lượng & Quy định điểm
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6"> {{-- Chỉ cần cột số lượng --}}
                        <div class="form-group">
                            <label for="SoLuong" class="form-label">
                                <i class="fa-solid fa-users me-1 text-primary"></i>
                                Số lượng tối đa <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control"
                                    id="SoLuong"
                                    name="SoLuong"
                                    value="{{ old('SoLuong') }}"
                                    min="1"
                                    required
                                    placeholder="0">
                                <span class="input-group-text">SV</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"> {{-- Quy định điểm --}}
                        <div class="form-group">
                            <label for="MaQuyDinhDiem" class="form-label">
                                <i class="fa-solid fa-clipboard-list me-1 text-info"></i>
                                Quy định điểm liên quan <span class="text-danger">*</span>
                            </label>
                            {{-- Đổi biến $quyDinhDiems --}}
                            <select class="form-select" id="MaQuyDinhDiem" name="MaQuyDinhDiem" required>
                                <option value="">-- Chọn quy định điểm DRL --</option>
                                {{-- Giả sử Controller truyền biến $quyDinhDiems --}}
                                @foreach($quyDinhDiems ?? [] as $maDiem => $tenCongViec)
                                <option value="{{ $maDiem }}" {{ old('MaQuyDinhDiem') == $maDiem ? 'selected' : '' }}>
                                    {{ $maDiem }} - {{ $tenCongViec }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Bỏ cột Điểm --}}
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
                            Lưu ý khi tạo mới
                        </h6>
                        <ul class="mb-0 small">
                            <li>Điền đầy đủ thông tin vào các trường có dấu <span class="text-danger">*</span></li>
                            <li>Hoạt động DRL cần được gán vào một Học kỳ cụ thể.</li>
                            <li>Điểm rèn luyện sẽ được lấy tự động từ Quy định điểm đã chọn.</li>
                            <li>Thời gian kết thúc phải diễn ra sau thời gian bắt đầu.</li>
                            <li>Thời hạn hủy (nếu có) phải trước thời gian bắt đầu.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                {{-- Đổi route --}}
                <a href="{{ route('nhanvien.hoatdong_drl.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>
                <div>
                    <button type="reset" class="btn btn-outline-warning me-2">
                        <i class="fa-solid fa-rotate-left me-2"></i>Đặt lại
                    </button>
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="fa-solid fa-save me-2"></i>Lưu Hoạt động
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Giữ nguyên JS validation --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createForm');
        const thoiGianBatDau = document.getElementById('ThoiGianBatDau');
        const thoiGianKetThuc = document.getElementById('ThoiGianKetThuc');
        const thoiHanHuy = document.getElementById('ThoiHanHuy');

        function validateDates() {
            const start = thoiGianBatDau.value ? new Date(thoiGianBatDau.value) : null;
            const end = thoiGianKetThuc.value ? new Date(thoiGianKetThuc.value) : null;
            const cancel = thoiHanHuy.value ? new Date(thoiHanHuy.value) : null;

            thoiGianKetThuc.setCustomValidity('');
            thoiHanHuy.setCustomValidity('');

            if (start && end && end <= start) {
                thoiGianKetThuc.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu.');
            }
            if (start && cancel && cancel >= start) {
                thoiHanHuy.setCustomValidity('Thời hạn hủy phải trước thời gian bắt đầu.');
            }
            thoiGianKetThuc.reportValidity();
            thoiHanHuy.reportValidity();
        }

        thoiGianBatDau.addEventListener('change', validateDates);
        thoiGianKetThuc.addEventListener('change', validateDates);
        thoiHanHuy.addEventListener('change', validateDates);

        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                validateDates();
            }
            form.classList.add('was-validated');
        }, false);

        const resetButton = form.querySelector('button[type="reset"]');
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                form.classList.remove('was-validated');
                thoiGianKetThuc.setCustomValidity('');
                thoiHanHuy.setCustomValidity('');
            });
        }
    });
</script>
@endpush