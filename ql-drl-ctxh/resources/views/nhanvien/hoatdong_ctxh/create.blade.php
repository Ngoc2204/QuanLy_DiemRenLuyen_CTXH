@extends('layouts.nhanvien')

@section('title', 'Thêm Hoạt động CTXH')
@section('page_title', 'Thêm mới Hoạt động CTXH')

@php
    // Breadcrumbs
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.hoatdong_ctxh.index'), 'title' => 'Hoạt động CTXH'],
        ['url' => '#', 'title' => 'Thêm mới'],
    ];
@endphp

@push('styles')
<style>
    .form-section { background: #f8f9fa; padding: 1.5rem; border-radius: 12px; border: 1px solid #e9ecef; }
    .section-header h6 { font-weight: 600; }
    .section-header hr { border-top: 2px solid currentColor; opacity: 0.2; }
    .form-label { font-weight: 600; color: #495057; margin-bottom: 0.5rem; display: flex; align-items: center; }
    .form-label i { width: 20px; text-align: center; }
    .form-control, .form-select { border-radius: 8px; border: 1px solid #dee2e6; padding: 0.625rem 0.875rem; transition: all 0.3s ease; }
    .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
    .form-control:disabled, .form-control[readonly] { background-color: #f8f9fa; border-color: #e9ecef; }
    .input-group-text { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; font-weight: 500; }
    .btn { border-radius: 8px; padding: 0.625rem 1.25rem; font-weight: 500; transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
    .btn-primary:hover { background: linear-gradient(135deg, #5568d3 0%, #653a8b 100%); }
    .alert { border-radius: 12px; }
    .card { border-radius: 12px; overflow: hidden; }
    .shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }
    .text-muted { color: #6c757d !important; }
    textarea.form-control { resize: vertical; min-height: 100px; }
    .form-control:invalid:not(:placeholder-shown) { border-color: #dc3545; }
    .form-control:valid:not(:placeholder-shown) { border-color: #28a745; }
    input[list]::-webkit-calendar-picker-indicator { opacity: 0.6; }
    input[type="number"]::-webkit-inner-spin-button, input[type="number"]::-webkit-outer-spin-button { opacity: 1; }
    .form-group { margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="mb-0 text-white">
            <i class="fa-solid fa-plus me-2"></i>
            Thêm mới Hoạt động CTXH
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

        <form action="{{ route('nhanvien.hoatdong_ctxh.store') }}" method="POST" id="createForm" novalidate>
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
                                Tên Hoạt động
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="TenHoatDong" name="TenHoatDong" value="{{ old('TenHoatDong') }}" required placeholder="Nhập tên hoạt động">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="LoaiHoatDong" class="form-label">
                                <i class="fa-solid fa-tag me-1 text-muted"></i>
                                Phân loại
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="LoaiHoatDong" name="LoaiHoatDong" required>
                                <option value="Tình nguyện" {{ old('LoaiHoatDong') == 'Tình nguyện' ? 'selected' : '' }}>Tình nguyện</option>
                                <option value="Hội thảo" {{ old('LoaiHoatDong') == 'Hội thảo' ? 'selected' : '' }}>Hội thảo</option>
                                <option value="Tập huấn" {{ old('LoaiHoatDong') == 'Tập huấn' ? 'selected' : '' }}>Tập huấn</option>
                                <option value="Địa chỉ đỏ" {{ old('LoaiHoatDong') == 'Địa chỉ đỏ' ? 'selected' : '' }}>Địa chỉ đỏ</option>
                                <option value="Học thuật" {{ old('LoaiHoatDong') == 'Học thuật' ? 'selected' : '' }}>Học thuật</option>
                                <option value="Văn hóa - Văn nghệ" {{ old('LoaiHoatDong') == 'Văn hóa - Văn nghệ' ? 'selected' : '' }}>Văn hóa - Văn nghệ</option>
                                <option value="Thể dục - Thể thao" {{ old('LoaiHoatDong') == 'Thể dục - Thể thao' ? 'selected' : '' }}>Thể dục - Thể thao</option>
                                <option value="Khác" {{ old('LoaiHoatDong') == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="DiaDiem" class="form-label">
                                <i class="fa-solid fa-location-dot me-1 text-muted"></i>
                                Địa điểm cụ thể (Ghi chú) <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="DiaDiem" name="DiaDiem" value="{{ old('DiaDiem', 'Như địa điểm tổ chức') }}" placeholder="Ví dụ: Sảnh A, Phòng B102..." required>
                        </div>
                    </div>

                    {{-- KHỐI ĐỊA CHỈ ĐỎ --}}
                    <div class="row g-3" id="diaChiDoFields" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dot_id" class="form-label">
                                    <i class="fa-solid fa-calendar-week me-1 text-muted"></i>
                                    Thuộc Đợt
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="dot_id" name="dot_id">
                                    <option value="">-- Chọn Đợt --</option>
                                    @forelse($dots as $dot)
                                        <option value="{{ $dot->id }}" {{ old('dot_id') == $dot->id ? 'selected' : '' }}>
                                            {{ $dot->TenDot }} ({{ $dot->TrangThai }})
                                        </option>
                                    @empty
                                        <option value="" disabled>Không có đợt nào đang/sắp diễn ra.</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="diadiem_id" class="form-label">
                                    <i class="fa-solid fa-map-location-dot me-1 text-muted"></i>
                                    Địa điểm tổ chức
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="diadiem_id" name="diadiem_id">
                                    <option value="">-- Chọn Địa điểm --</option>
                                    @foreach($diadiems as $diadiem)
                                        <option value="{{ $diadiem->id }}" {{ old('diadiem_id') == $diadiem->id ? 'selected' : '' }}>
                                            {{ $diadiem->TenDiaDiem }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- END KHỐI ĐỊA CHỈ ĐỎ --}}

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="MoTa" class="form-label">
                                <i class="fa-solid fa-align-left me-1 text-muted"></i>
                                Mô tả
                            </label>
                            <textarea class="form-control" id="MoTa" name="MoTa" rows="4" placeholder="Nhập mô tả chi tiết về hoạt động...">{{ old('MoTa') }}</textarea>
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
                            <input type="datetime-local" class="form-control" id="ThoiGianBatDau" name="ThoiGianBatDau" value="{{ old('ThoiGianBatDau') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ThoiGianKetThuc" class="form-label">
                                <i class="fa-solid fa-calendar-xmark me-1 text-danger"></i>
                                Thời gian Kết thúc
                                <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" id="ThoiGianKetThuc" name="ThoiGianKetThuc" value="{{ old('ThoiGianKetThuc') }}" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="ThoiHanHuy" class="form-label">
                                <i class="fa-solid fa-clock-rotate-left me-1 text-warning"></i>
                                Thời hạn Hủy đăng ký <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" id="ThoiHanHuy" name="ThoiHanHuy" value="{{ old('ThoiHanHuy') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Điểm số & Số lượng --}}
            <div class="form-section mb-4">
                <div class="section-header mb-3">
                    <h6 class="text-warning mb-0">
                        <i class="fa-solid fa-chart-simple me-2"></i>
                        Điểm số & Số lượng
                    </h6>
                    <hr class="mt-2">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="SoLuong" class="form-label">
                                <i class="fa-solid fa-users me-1 text-primary"></i>
                                Số lượng tối đa
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="SoLuong" name="SoLuong" value="{{ old('SoLuong') }}" min="1" required placeholder="0">
                                <span class="input-group-text">SV</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MaQuyDinhDiem" class="form-label">
                                <i class="fa-solid fa-clipboard-list me-1 text-info"></i>
                                Quy định điểm liên quan
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="MaQuyDinhDiem" name="MaQuyDinhDiem" required>
                                <option value="">-- Chọn quy định điểm --</option>
                                @foreach($quyDinhDiems as $maDiem => $tenCongViec)
                                    <option value="{{ $maDiem }}" {{ old('MaQuyDinhDiem') == $maDiem ? 'selected' : '' }}>
                                        {{ $maDiem }} - {{ $tenCongViec }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lưu ý --}}
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
                            <li>Thời gian kết thúc phải diễn ra sau thời gian bắt đầu</li>
                            <li>Thời hạn hủy (nếu có) phải trước thời gian bắt đầu</li>
                            <li>Chọn Quy định điểm phù hợp để tính điểm tự động (nếu có)</li>
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
                        <i class="fa-solid fa-save me-2"></i>Lưu Hoạt động
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('createForm');
        const thoiGianBatDau = document.getElementById('ThoiGianBatDau');
        const thoiGianKetThuc = document.getElementById('ThoiGianKetThuc');
        const thoiHanHuy = document.getElementById('ThoiHanHuy');
        const loaiHoatDong = document.getElementById('LoaiHoatDong');
        const diaChiDoFields = document.getElementById('diaChiDoFields');

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

        function toggleDiaChiDo() {
            const show = loaiHoatDong.value === 'Địa chỉ đỏ';
            diaChiDoFields.style.display = show ? 'flex' : 'none';
        }

        thoiGianBatDau.addEventListener('change', validateDates);
        thoiGianKetThuc.addEventListener('change', validateDates);
        thoiHanHuy.addEventListener('change', validateDates);
        loaiHoatDong.addEventListener('change', toggleDiaChiDo);

        // Init states
        validateDates();
        toggleDiaChiDo();

        // Bootstrap-like validation
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                validateDates();
            }
            form.classList.add('was-validated');
        }, false);

        const resetButton = form.querySelector('button[type="reset"]');
        if (resetButton) {
            resetButton.addEventListener('click', function () {
                form.classList.remove('was-validated');
                thoiGianKetThuc.setCustomValidity('');
                thoiHanHuy.setCustomValidity('');
                // reset visibility based on default/old value
                setTimeout(toggleDiaChiDo, 0);
            });
        }
    });
</script>
@endpush
