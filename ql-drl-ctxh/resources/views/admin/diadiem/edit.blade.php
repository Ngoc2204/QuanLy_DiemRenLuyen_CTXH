@extends('layouts.admin')

@section('title', 'Chỉnh sửa Địa điểm')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.diadiem.index') }}">Địa điểm</a></li>
            <li class="breadcrumb-item active">Chỉnh sửa</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">
                <i class="fas fa-edit text-warning me-2"></i>
                Chỉnh sửa Địa điểm
            </h2>
            <p class="text-muted mb-0">
                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                {{ $diadiem->TenDiaDiem }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.diadiem.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <a href="{{ route('admin.diadiem.show', $diadiem->id) }}" class="btn btn-outline-info">
                <i class="fas fa-eye me-2"></i>Xem chi tiết
            </a>
        </div>
    </div>

    <!-- Alert Success (nếu có session) -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <i class="fas fa-check-circle fa-lg me-3 mt-1"></i>
                <div>
                    <strong>Thành công!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Thông tin Địa điểm
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Alert Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading mb-2">Vui lòng kiểm tra lại thông tin!</h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.diadiem.update', ['diadiem' => $diadiem->id]) }}" method="POST" id="formEditDiaDiem">
                        @csrf
                        @method('PUT')
                        
                        <!-- Tên Địa điểm -->
                        <div class="mb-4">
                            <label for="TenDiaDiem" class="form-label fw-semibold">
                                Tên Địa điểm <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-building text-muted"></i>
                                </span>
                                <input 
                                    type="text" 
                                    class="form-control form-control-lg @error('TenDiaDiem') is-invalid @enderror" 
                                    id="TenDiaDiem" 
                                    name="TenDiaDiem" 
                                    value="{{ old('TenDiaDiem', $diadiem->TenDiaDiem) }}" 
                                    placeholder="VD: Dinh Độc Lập, Nhà thờ Đức Bà..." 
                                    required
                                >
                                @error('TenDiaDiem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Nhập tên đầy đủ và chính xác của địa điểm</small>
                        </div>
                        
                        <!-- Địa chỉ -->
                        <div class="mb-4">
                            <label for="DiaChi" class="form-label fw-semibold">
                                Địa chỉ chi tiết
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-map-marked-alt text-muted"></i>
                                </span>
                                <textarea 
                                    class="form-control @error('DiaChi') is-invalid @enderror" 
                                    id="DiaChi" 
                                    name="DiaChi" 
                                    rows="3" 
                                    placeholder="VD: 135 Nam Kỳ Khởi Nghĩa, Phường Bến Nghé, Quận 1, TP.HCM"
                                >{{ old('DiaChi', $diadiem->DiaChi) }}</textarea>
                                @error('DiaChi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Ghi rõ số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố</small>
                        </div>

                        <div class="row">
                            <!-- Giá vé -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="GiaTien" class="form-label fw-semibold">
                                        Giá vé tham quan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-ticket-alt text-muted"></i>
                                        </span>
                                        <input 
                                            type="number" 
                                            class="form-control form-control-lg @error('GiaTien') is-invalid @enderror" 
                                            id="GiaTien" 
                                            name="GiaTien" 
                                            value="{{ old('GiaTien', $diadiem->GiaTien) }}" 
                                            required 
                                            min="0"
                                            step="1000"
                                        >
                                        <span class="input-group-text bg-light">VNĐ</span>
                                        @error('GiaTien')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">
                                        <span id="giatienFormatted" class="fw-semibold text-success"></span>
                                    </small>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="TrangThai" class="form-label fw-semibold">
                                        Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-toggle-on text-muted"></i>
                                        </span>
                                        <select class="form-select form-select-lg @error('TrangThai') is-invalid @enderror" id="TrangThai" name="TrangThai">
                                            <option value="KhaDung" {{ old('TrangThai', $diadiem->TrangThai) == 'KhaDung' ? 'selected' : '' }}>
                                                ✓ Khả dụng
                                            </option>
                                            <option value="TamNgung" {{ old('TrangThai', $diadiem->TrangThai) == 'TamNgung' ? 'selected' : '' }}>
                                                ✕ Tạm ngưng
                                            </option>
                                        </select>
                                        @error('TrangThai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Chọn trạng thái hiển thị địa điểm</small>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Box -->
                        <div class="alert alert-warning border-0 bg-light" role="alert">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-circle fa-lg text-warning me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">Lưu ý khi chỉnh sửa:</h6>
                                    <ul class="mb-0 small ps-3">
                                        <li>Kiểm tra kỹ thông tin trước khi lưu</li>
                                        <li>Thay đổi trạng thái có thể ảnh hưởng đến hiển thị trên hệ thống</li>
                                        <li>Tất cả thay đổi sẽ được ghi lại lịch sử</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-between mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-lg btn-outline-danger px-4" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash-alt me-2"></i>Xóa địa điểm
                            </button>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.diadiem.index') }}" class="btn btn-lg btn-light px-4">
                                    <i class="fas fa-times me-2"></i>Hủy bỏ
                                </a>
                                <button type="submit" class="btn btn-lg btn-success px-4">
                                    <i class="fas fa-save me-2"></i>Lưu thay đổi
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-xl-4 col-lg-12 mt-4 mt-xl-0">
            <!-- Thông tin bản ghi -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-database me-2"></i>Thông tin bản ghi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">ID Địa điểm</small>
                        <span class="badge bg-secondary">{{ $diadiem->id }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Ngày tạo</small>
                        <div class="text-dark">
                            <i class="fas fa-calendar-plus text-muted me-1"></i>
                            {{ $diadiem->created_at ? $diadiem->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Cập nhật lần cuối</small>
                        <div class="text-dark">
                            <i class="fas fa-clock text-muted me-1"></i>
                            {{ $diadiem->updated_at ? $diadiem->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trạng thái hiện tại -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center py-4">
                    @if($diadiem->TrangThai == 'KhaDung')
                        <div class="mb-3">
                            <i class="fas fa-check-circle fa-3x text-success"></i>
                        </div>
                        <h6 class="text-success mb-1">Đang hoạt động</h6>
                        <small class="text-muted">Địa điểm đang hiển thị</small>
                    @else
                        <div class="mb-3">
                            <i class="fas fa-pause-circle fa-3x text-warning"></i>
                        </div>
                        <h6 class="text-warning mb-1">Tạm ngưng</h6>
                        <small class="text-muted">Không hiển thị trên hệ thống</small>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.diadiem.show', $diadiem->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-eye text-info me-2"></i>Xem chi tiết
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="duplicateLocation(); return false;">
                            <i class="fas fa-copy text-primary me-2"></i>Nhân bản địa điểm
                        </a>
                        <a href="{{ route('admin.diadiem.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-list text-secondary me-2"></i>Danh sách địa điểm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Bạn có chắc chắn muốn xóa địa điểm này?</p>
                <div class="alert alert-warning border-0 mb-0">
                    <strong>{{ $diadiem->TenDiaDiem }}</strong>
                    <p class="mb-0 mt-2 small">Hành động này không thể hoàn tác!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Hủy
                </button>
                <form action="{{ route('admin.diadiem.destroy', $diadiem->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i>Xóa vĩnh viễn
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    border: 1px solid #dee2e6;
}

.card {
    transition: transform 0.2s;
}

.btn {
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.alert {
    border-left: 4px solid;
}

.alert-danger {
    border-left-color: #dc3545;
}

.alert-success {
    border-left-color: #198754;
}

.alert-warning {
    border-left-color: #ffc107;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-size: 1.2rem;
}

.list-group-item {
    transition: all 0.2s;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    padding-left: 1.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format và hiển thị giá tiền
    const giatienInput = document.getElementById('GiaTien');
    const giatienFormatted = document.getElementById('giatienFormatted');
    
    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value);
    }
    
    function updateFormattedPrice() {
        const value = parseInt(giatienInput.value) || 0;
        giatienFormatted.textContent = formatCurrency(value);
    }
    
    if (giatienInput) {
        updateFormattedPrice(); // Initial format
        
        giatienInput.addEventListener('input', updateFormattedPrice);
        
        giatienInput.addEventListener('blur', function() {
            let value = parseInt(this.value) || 0;
            // Làm tròn đến nghìn
            value = Math.round(value / 1000) * 1000;
            this.value = value;
            updateFormattedPrice();
        });
    }

    // Xác nhận trước khi submit
    const form = document.getElementById('formEditDiaDiem');
    if (form) {
        form.addEventListener('submit', function(e) {
            const tenDiaDiem = document.getElementById('TenDiaDiem').value;
            if (!confirm('Xác nhận lưu thay đổi cho địa điểm "' + tenDiaDiem + '"?')) {
                e.preventDefault();
            }
        });
    }

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Highlight changed fields
    const formInputs = form.querySelectorAll('input, textarea, select');
    const originalValues = {};
    
    formInputs.forEach(input => {
        originalValues[input.id] = input.value;
        
        input.addEventListener('change', function() {
            if (this.value !== originalValues[this.id]) {
                this.classList.add('border-warning');
            } else {
                this.classList.remove('border-warning');
            }
        });
    });
});

// Function for duplicate action (placeholder)
function duplicateLocation() {
    alert('Tính năng nhân bản địa điểm đang được phát triển!');
}
</script>
@endsection