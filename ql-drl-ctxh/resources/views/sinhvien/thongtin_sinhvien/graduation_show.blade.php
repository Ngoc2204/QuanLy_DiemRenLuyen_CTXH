@extends('layouts.sinhvien')

@section('content')

    <div class="graduation-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="bi bi-mortarboard"></i>
                Thông tin xét tốt nghiệp
            </h1>
            <p class="page-subtitle">Quản lý thời gian tốt nghiệp dự kiến của bạn</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Student Info Card -->
        <div class="info-card">
            <div class="card-header">
                <i class="bi bi-person-circle"></i>
                <h3>Thông tin sinh viên</h3>
            </div>
            
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-person"></i>
                            Họ và Tên
                        </span>
                        <p class="info-value">{{ $student->HoTen }}</p>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-hash"></i>
                            Mã Sinh Viên
                        </span>
                        <p class="info-value">{{ $student->MSSV }}</p>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-building"></i>
                            Lớp
                        </span>
                        <p class="info-value">{{ $student->lop->TenLop ?? $student->MaLop }}</p>
                    </div>

                    <div class="info-item">
                        <span class="info-label">
                            <i class="bi bi-briefcase"></i>
                            Khoa
                        </span>
                        <p class="info-value">{{ $student->lop->khoa->TenKhoa ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graduation Form Card -->
        <div class="form-card">
            <div class="card-header">
                <i class="bi bi-calendar-event"></i>
                <h3>Đề xuất thời gian tốt nghiệp</h3>
            </div>

            <div class="card-body">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <div class="alert-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <h5>Thành công!</h5>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button type="button" class="alert-close" data-bs-dismiss="alert">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-circle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <h5>Có lỗi xảy ra!</h5>
                            <ul class="alert-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="alert-close" data-bs-dismiss="alert">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif

                <!-- Info Message -->
                <div class="info-message">
                    <i class="bi bi-info-circle"></i>
                    <span>Vui lòng chọn ngày dự kiến bạn hoàn thành chương trình học và đủ điều kiện tốt nghiệp.</span>
                </div>

                <!-- Form -->
                <form action="{{ route('sinhvien.graduation.update') }}" method="POST" class="graduation-form">
                    @csrf

                    <div class="form-group">
                        <label for="ThoiGianTotNghiepDuKien" class="form-label">
                            <i class="bi bi-calendar"></i>
                            Chọn ngày tốt nghiệp dự kiến
                            <span class="required-mark">*</span>
                        </label>
                        
                        <div class="date-input-wrapper">
                            <input 
                                type="date" 
                                class="form-control-custom @error('ThoiGianTotNghiepDuKien') is-invalid @enderror"
                                id="ThoiGianTotNghiepDuKien" 
                                name="ThoiGianTotNghiepDuKien"
                                value="{{ old('ThoiGianTotNghiepDuKien', $student->ThoiGianTotNghiepDuKien) }}"
                                min="{{ date('Y-m-d') }}"
                                required>
                            <div class="input-icon">
                                <i class="bi bi-calendar3"></i>
                            </div>
                        </div>

                        @error('ThoiGianTotNghiepDuKien')
                            <span class="error-message">{{ $message }}</span>
                        @enderror

                        @if($student->ThoiGianTotNghiepDuKien)
                            <div class="selected-date-info">
                                <i class="bi bi-check-circle"></i>
                                <span>Ngày được chọn: <strong>{{ \Carbon\Carbon::parse($student->ThoiGianTotNghiepDuKien)->format('d/m/Y') }}</strong></span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-circle"></i>
                            <span>Lưu thay đổi</span>
                        </button>
                        <a href="javascript:history.back()" class="btn-cancel">
                            <i class="bi bi-arrow-left"></i>
                            <span>Quay lại</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        
    </div>


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .graduation-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Header Section */
    .graduation-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        color: #ffffff;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title i {
        font-size: 2.5rem;
    }

    .page-subtitle {
        font-size: 1rem;
        opacity: 0.95;
        margin: 0;
    }

    /* Container */
    .container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Card Styles */
    .info-card,
    .form-card,
    .timeline-info {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .info-card:hover,
    .form-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-header i {
        font-size: 1.5rem;
        color: #667eea;
    }

    .card-header h3 {
        font-size: 1.25rem;
        color: #1f2937;
        margin: 0;
    }

    .card-body {
        padding: 2rem;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-label i {
        color: #667eea;
        font-size: 1rem;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 500;
        color: #1f2937;
        margin: 0;
        padding: 0.75rem 1rem;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 3px solid #667eea;
    }

    /* Alert Styles */
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 1px solid;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: #f0fdf4;
        border-color: #86efac;
        color: #166534;
    }

    .alert-danger {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .alert-icon {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .alert-success .alert-icon {
        color: #16a34a;
    }

    .alert-danger .alert-icon {
        color: #dc2626;
    }

    .alert-content h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
    }

    .alert-content p {
        margin: 0;
        font-size: 0.95rem;
    }

    .alert-list {
        margin: 0;
        padding-left: 1.25rem;
    }

    .alert-list li {
        margin-bottom: 0.25rem;
    }

    .alert-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: inherit;
        cursor: pointer;
        padding: 0;
        margin-left: auto;
        flex-shrink: 0;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Info Message */
    .info-message {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        color: #1e40af;
        font-size: 0.95rem;
    }

    .info-message i {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
    }

    .form-label i {
        color: #667eea;
        font-size: 1.125rem;
    }

    .required-mark {
        color: #ef4444;
    }

    .date-input-wrapper {
        position: relative;
    }

    .form-control-custom {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        font-size: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #1f2937;
        font-weight: 500;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-control-custom.is-invalid {
        border-color: #ef4444;
    }

    .form-control-custom.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.125rem;
        pointer-events: none;
    }

    .error-message {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #ef4444;
        font-weight: 500;
    }

    .selected-date-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        padding: 0.75rem 1rem;
        background: #f0fdf4;
        border-radius: 8px;
        color: #166534;
        font-size: 0.95rem;
        border-left: 3px solid #16a34a;
    }

    .selected-date-info i {
        color: #16a34a;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid #f3f4f6;
    }

    .btn-save,
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        flex: 1;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
        min-width: 140px;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        color: #374151;
    }

    /* Timeline */
    .timeline-info {
        background: #ffffff;
    }

    .timeline-info h4 {
        font-size: 1.25rem;
        color: #1f2937;
        margin-bottom: 2rem;
    }

    .timeline {
        position: relative;
        padding: 2rem 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 700;
        font-size: 1.25rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .timeline-content h5 {
        font-size: 1rem;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .timeline-content p {
        color: #6b7280;
        margin: 0;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        .graduation-header {
            padding: 2rem 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-save,
        .btn-cancel {
            width: 100%;
            min-width: auto;
        }

        .alert {
            flex-direction: column;
        }

        .alert-close {
            margin-left: 0;
            align-self: flex-end;
        }

        .timeline::before {
            left: 16px;
        }

        .timeline-marker {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .card-body {
            padding: 1rem;
        }

        .info-value {
            font-size: 0.95rem;
        }

        .timeline-content h5 {
            font-size: 0.95rem;
        }
    }
</style>
@endsection