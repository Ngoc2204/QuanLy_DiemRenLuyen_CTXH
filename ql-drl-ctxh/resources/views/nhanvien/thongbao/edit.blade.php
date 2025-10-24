@extends('layouts.nhanvien')

@section('title', 'Cập nhật Trạng thái Phản hồi')
@section('page_title', 'Cập nhật Trạng thái Phản hồi')

@php
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.thongbao.index'), 'title' => 'Phản hồi Sinh viên'],
        ['url' => '#', 'title' => 'Cập nhật trạng thái'],
    ];
@endphp

@section('content')
<div class="container-fluid px-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">
                        <i class="fa-solid fa-pen-to-square text-primary me-2"></i>Cập nhật Trạng thái Phản hồi
                    </h4>
                    <p class="text-muted mb-0">Thay đổi trạng thái xử lý phản hồi từ sinh viên</p>
                </div>
                <a href="{{ route('nhanvien.thongbao.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            {{-- Feedback Information Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-info-circle me-2 text-primary"></i>Thông tin Phản hồi #{{ $thongbao->MaPhanHoi }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    {{-- Student Info --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label text-muted small mb-2">
                            <i class="fa-solid fa-user me-1"></i>Sinh viên gửi
                        </label>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fa-solid fa-user-graduate fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $thongbao->sinhvien->HoTen ?? 'Không tìm thấy thông tin SV' }}</h6>
                                <small class="text-muted">MSSV: {{ $thongbao->MSSV }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Send Date --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label text-muted small mb-2">
                            <i class="fa-regular fa-calendar me-1"></i>Ngày gửi
                        </label>
                        <p class="mb-0 fw-semibold">
                            <i class="fa-regular fa-clock me-2 text-muted"></i>
                            {{ $thongbao->NgayGui ? \Carbon\Carbon::parse($thongbao->NgayGui)->format('d/m/Y H:i:s') : 'N/A' }}
                        </p>
                    </div>

                    {{-- Current Status --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label text-muted small mb-2">
                            <i class="fa-solid fa-flag me-1"></i>Trạng thái hiện tại
                        </label>
                        <div>
                            @if($thongbao->TrangThai == 'Chưa xử lý')
                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i>{{ $thongbao->TrangThai }}
                                </span>
                            @elseif($thongbao->TrangThai == 'Đang xử lý')
                                <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2">
                                    <i class="fa-solid fa-spinner me-1"></i>{{ $thongbao->TrangThai }}
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                                    <i class="fa-solid fa-circle-check me-1"></i>{{ $thongbao->TrangThai }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Feedback Content --}}
                    <div>
                        <label class="form-label text-muted small mb-2">
                            <i class="fa-solid fa-message me-1"></i>Nội dung phản hồi
                        </label>
                        <div class="bg-light border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <div class="feedback-content-display">
                                {!! nl2br(e($thongbao->NoiDung)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Update Status Form Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient text-white border-0 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-edit me-2"></i>Cập nhật Trạng thái
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('nhanvien.thongbao.update', $thongbao->MaPhanHoi) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="TrangThai" class="form-label fw-semibold">
                                <i class="fa-solid fa-toggle-on me-1 text-primary"></i>
                                Chọn trạng thái mới <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('TrangThai') is-invalid @enderror" 
                                    id="TrangThai" 
                                    name="TrangThai" 
                                    required>
                                <option value="Chưa xử lý" {{ old('TrangThai', $thongbao->TrangThai) == 'Chưa xử lý' ? 'selected' : '' }}>
                                    🔴 Chưa xử lý
                                </option>
                                <option value="Đang xử lý" {{ old('TrangThai', $thongbao->TrangThai) == 'Đang xử lý' ? 'selected' : '' }}>
                                    🟡 Đang xử lý
                                </option>
                                <option value="Đã phản hồi" {{ old('TrangThai', $thongbao->TrangThai) == 'Đã phản hồi' ? 'selected' : '' }}>
                                    🟢 Đã phản hồi
                                </option>
                            </select>
                            @error('TrangThai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-2 d-block">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Chọn trạng thái phù hợp với tình trạng xử lý phản hồi
                            </small>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('nhanvien.thongbao.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fa-solid fa-xmark me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa-solid fa-check me-2"></i>Cập nhật Trạng thái
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Help Card --}}
            <div class="card border-0 bg-light mt-4">
                <div class="card-body p-3">
                    <h6 class="mb-2">
                        <i class="fa-solid fa-lightbulb text-warning me-2"></i>Hướng dẫn
                    </h6>
                    <ul class="mb-0 small text-muted">
                        <li><strong>Chưa xử lý:</strong> Phản hồi mới chưa được xem xét</li>
                        <li><strong>Đang xử lý:</strong> Phản hồi đang được xem xét và giải quyết</li>
                        <li><strong>Đã phản hồi:</strong> Phản hồi đã được xử lý xong và trả lời sinh viên</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1.05rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-select-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.feedback-content-display {
    line-height: 1.8;
    color: #495057;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.bg-danger-subtle {
    background-color: #f8d7da !important;
}

.bg-warning-subtle {
    background-color: #fff3cd !important;
}

.bg-success-subtle {
    background-color: #d1e7dd !important;
}

.badge {
    padding: 8px 12px;
    font-weight: 500;
    font-size: 0.9rem;
}

.btn {
    border-radius: 8px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #66328b 100%);
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Status change confirmation
    const form = document.querySelector('form');
    const statusSelect = document.getElementById('TrangThai');
    const originalStatus = '{{ $thongbao->TrangThai }}';

    form.addEventListener('submit', function(e) {
        const newStatus = statusSelect.value;
        if (newStatus === originalStatus) {
            e.preventDefault();
            alert('Trạng thái không thay đổi. Vui lòng chọn trạng thái khác.');
            return false;
        }
    });

    // Add visual feedback on status change
    statusSelect.addEventListener('change', function() {
        this.style.borderColor = '#667eea';
        this.style.boxShadow = '0 0 0 0.25rem rgba(102, 126, 234, 0.25)';
        
        setTimeout(() => {
            this.style.borderColor = '';
            this.style.boxShadow = '';
        }, 1000);
    });
});
</script>
@endpush