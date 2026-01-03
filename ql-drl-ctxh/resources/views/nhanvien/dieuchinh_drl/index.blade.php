@extends('layouts.nhanvien') {{-- (Giả sử layout của bạn là 'layouts.nhanvien') --}}
@section('title', 'Điều chỉnh Điểm Rèn Luyện')

@push('styles')
<style>
    .form-card {
        background: #fdfdfd;
        border: 1px solid #e9ecef;
        border-radius: 12px;
    }
    .history-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
    }
    .form-control, .form-select { border-radius: 8px; }
    .table-vcenter { vertical-align: middle !important; }
    .diem-am { color: #dc3545; font-weight: 700; }
    .diem-duong { color: #198754; font-weight: 700; }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Chỉnh Select2 cho giống Bootstrap 5 */
    .select2-container { width: 100% !important; }
    .select2-container .select2-selection--single {
        height: 38px;
        border-radius: 8px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
        color: #212529;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-dropdown { border-radius: 8px; border: 1px solid #ced4da; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-4">Điều chỉnh Điểm Rèn Luyện Thủ công</h3>

    {{-- Thông báo --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    {{-- Form Thêm Mới --}}
    <div class="card form-card p-4 mb-4">
        <h5 class="mb-3">Áp dụng Khen thưởng / Vi phạm</h5>
        <form action="{{ route('nhanvien.dieuchinh_drl.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="MSSV" class="form-label fw-bold">MSSV (*)</label>
                    <input type="text" class="form-control" id="MSSV" name="MSSV" value="{{ old('MSSV') }}" placeholder="200122..." required>
                </div>
                <div class="col-md-3">
                    <label for="MaHocKy" class="form-label fw-bold">Học kỳ (*)</label>
                    <select class="form-select" id="MaHocKy" name="MaHocKy" required>
                        <option value="">-- Chọn học kỳ --</option>
                        @foreach ($hocKys as $hk)
                            <option value="{{ $hk->MaHocKy }}" {{ old('MaHocKy') == $hk->MaHocKy ? 'selected' : '' }}>
                                {{ $hk->TenHocKy }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    {{-- SỬA: Dùng dropdown thay vì input text --}}
                    <label for="MaDiem" class="form-label fw-bold">Quy định áp dụng (*)</label>
                    <select class="form-select" id="MaDiem" name="MaDiem" required>
                        <option value="">-- Chọn quy định... --</option>
                        @foreach ($quyDinhs as $qd)
                            <option value="{{ $qd->MaDiem }}" {{ old('MaDiem') == $qd->MaDiem ? 'selected' : '' }}>
                                [{{ $qd->MaDiem }}] {{ $qd->TenCongViec }} ({{ $qd->DiemNhan }}đ)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Thêm Điều chỉnh</button>
            </div>
        </form>
    </div>

    {{-- Bảng Lịch sử --}}
    <div class="card history-card">
        <div class="card-header">
            <h5 class="mb-0">Lịch sử Điều chỉnh</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-vcenter mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sinh viên</th>
                            <th>Học kỳ</th>
                            <th>Nội dung áp dụng</th> {{-- SỬA --}}
                            <th class="text-center">Số điểm</th> {{-- SỬA --}}
                            <th>Người cập nhật</th>
                            <th>Ngày Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dieuChinhs as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->sinhvien->HoTen ?? 'N/A' }}</strong>
                                    <div>{{ $item->MSSV }}</div>
                                </td>
                                <td>{{ $item->hocky->TenHocKy ?? $item->MaHocKy }}</td>
                                {{-- SỬA: Lấy từ relationship 'quydinh' --}}
                                <td>
                                    @if($item->quydinh)
                                        [{{ $item->quydinh->MaDiem }}] {{ $item->quydinh->TenCongViec }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->quydinh)
                                        @php $diem = $item->quydinh->DiemNhan; @endphp
                                        @if ($diem > 0)
                                            <span class="diem-duong">+{{ $diem }}</span>
                                        @else
                                            <span class="diem-am">{{ $diem }}</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $item->nhanvien->TenNV ?? $item->MaNV }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->NgayCapNhat)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-5">
                                    <p class="text-muted mb-0">Chưa có lịch sử điều chỉnh nào.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($dieuChinhs->hasPages())
                <div class="card-footer border-top-0">
                    {{ $dieuChinhs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
{{-- SỬA LỖI: Chỗ này là @endsection (để đóng 'content'), không phải @endpush --}}
@endsection 

@push('scripts')
{{-- Script cho Select2 (Tìm kiếm dropdown) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Áp dụng Select2 cho dropdown Quy định
        $('#MaDiem').select2({
            placeholder: "-- Chọn quy định...",
            allowClear: true,
            theme: "default"
        });
        
        // (Tùy chọn) Áp dụng cho Học kỳ
        $('#MaHocKy').select2({
            placeholder: "-- Chọn học kỳ --",
            allowClear: false, // Bắt buộc chọn
            theme: "default"
        });
    });
</script>
@endpush