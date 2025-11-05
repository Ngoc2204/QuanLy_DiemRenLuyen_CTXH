@extends('layouts.nhanvien')

@section('title', 'Chi tiết Hoạt động CTXH')
@section('page_title', 'Chi tiết Hoạt động CTXH')

@php
    // Breadcrumbs
    $breadcrumbs = [
        ['url' => route('nhanvien.home'), 'title' => 'Bảng điều khiển'],
        ['url' => route('nhanvien.hoatdong_ctxh.index'), 'title' => 'Hoạt động CTXH'],
        ['url' => '#', 'title' => 'Chi tiết'],
    ];

    // Logic tính toán trạng thái
    $now = now();
    $hoatdong_ctxh->loadCount('dangKy');
    $sinhVienCount = $hoatdong_ctxh->dangKy_count;
    $dangDienRa = $hoatdong_ctxh->ThoiGianBatDau <= $now && $hoatdong_ctxh->ThoiGianKetThuc >= $now;
    $daKetThuc = $hoatdong_ctxh->ThoiGianKetThuc < $now;
    $chuaBatDau = $hoatdong_ctxh->ThoiGianBatDau > $now;
    $tyLeDangKy = $hoatdong_ctxh->SoLuong > 0 ? round(($sinhVienCount / $hoatdong_ctxh->SoLuong) * 100) : 0;
@endphp

@section('content')
{{-- Header Card --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-gradient py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1 text-white">
                    @if($hoatdong_ctxh->LoaiHoatDong == 'Địa chỉ đỏ')
                        <i class="fa-solid fa-map-location-dot me-2"></i>
                    @else
                        <i class="fa-solid fa-hand-holding-heart me-2"></i>
                    @endif
                    {{ $hoatdong_ctxh->TenHoatDong }}
                </h5>
                <small class="text-white-50">Mã: {{ $hoatdong_ctxh->MaHoatDong }}</small>
            </div>
            <div>
                @if($dangDienRa)
                    <span class="badge bg-success px-3 py-2"><i class="fa-solid fa-circle-play me-1"></i>Đang diễn ra</span>
                @elseif($daKetThuc)
                    <span class="badge bg-secondary px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i>Đã kết thúc</span>
                @else
                    <span class="badge bg-info px-3 py-2"><i class="fa-solid fa-clock me-1"></i>Sắp diễn ra</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Layout 2 Cột --}}
<div class="row g-4">
    {{-- Left Column - Thông tin chi tiết --}}
    <div class="col-lg-8">
        {{-- Card Thông tin chi tiết --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fa-solid fa-circle-info me-2 text-primary"></i>Thông tin chi tiết</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="info-item">
                            <label class="info-label"><i class="fa-solid fa-tag me-2 text-muted"></i>Phân loại</label>
                            <p class="info-value">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ $hoatdong_ctxh->LoaiHoatDong }}</span>
                            </p>
                        </div>
                    </div>

                    @if($hoatdong_ctxh->LoaiHoatDong == 'Địa chỉ đỏ')
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label"><i class="fa-solid fa-calendar-week me-2 text-muted"></i>Thuộc Đợt</label>
                                <p class="info-value">{{ $hoatdong_ctxh->dotDiaChiDo->TenDot ?? 'Không rõ' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label class="info-label"><i class="fa-solid fa-map-location-dot me-2 text-muted"></i>Địa điểm tổ chức</label>
                                <p class="info-value">{{ $hoatdong_ctxh->diaDiem->TenDiaDiem ?? 'Không rõ' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <div class="info-item">
                            <label class="info-label"><i class="fa-solid fa-align-left me-2 text-muted"></i>Mô tả</label>
                            <p class="info-value text-muted">{{ $hoatdong_ctxh->MoTa ?: 'Không có mô tả.' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label"><i class="fa-solid fa-calendar-plus me-2 text-success"></i>Thời gian Bắt đầu</label>
                            <p class="info-value">{{ $hoatdong_ctxh->ThoiGianBatDau->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label"><i class="fa-solid fa-calendar-xmark me-2 text-danger"></i>Thời gian Kết thúc</label>
                            <p class="info-value">{{ $hoatdong_ctxh->ThoiGianKetThuc->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label"><i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i>Thời hạn Hủy đăng ký</label>
                            <p class="info-value">{{ $hoatdong_ctxh->ThoiHanHuy ? $hoatdong_ctxh->ThoiHanHuy->format('d/m/Y H:i') : 'Không cho phép' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label"><i class="fa-solid fa-location-dot me-2 text-danger"></i>Địa điểm cụ thể (Ghi chú)</label>
                            <p class="info-value">{{ $hoatdong_ctxh->DiaDiem ?: 'Không xác định' }}</p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="info-item">
                            <label class="info-label"><i class="fa-solid fa-clipboard-list me-2 text-info"></i>Quy định điểm</label>
                            <p class="info-value">
                                @if($hoatdong_ctxh->quydinh)
                                    <span class="badge bg-info-subtle text-info px-3 py-2">
                                        {{ $hoatdong_ctxh->quydinh->MaDiem }} - {{ $hoatdong_ctxh->quydinh->TenCongViec }} ({{ $hoatdong_ctxh->quydinh->DiemNhan ?? 'N/A' }} điểm)
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Không rõ</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danh sách sinh viên đăng ký --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fa-solid fa-users me-2 text-primary"></i>Danh sách Sinh viên Đăng ký <span class="badge bg-primary ms-2">{{ $sinhVienCount }}</span></h6>
            </div>
            <div class="card-body p-0">
                @if($hoatdong_ctxh->sinhVienDangKy->isNotEmpty())
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="text-center" style="width: 5%;">#</th>
                                    <th>Sinh viên</th>
                                    <th class="text-center">Trạng thái ĐK</th>
                                    <th class="text-center">Check-in</th>
                                    <th class="text-center">Check-out</th>
                                    <th class="text-center">Kết quả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hoatdong_ctxh->sinhVienDangKy as $index => $sv)
                                    @php $pivot = $sv->pivot; @endphp
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle-small">
                                                    <span>{{ strtoupper(substr($sv->HoTen ?? 'N/A', 0, 1)) }}</span>
                                                </div>
                                                <div class="ms-3">
                                                    <div class="student-name">{{ $sv->HoTen ?? 'Không rõ' }}</div>
                                                    <div class="student-code">{{ $sv->MSSV }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($pivot->TrangThaiDangKy == 'Chờ duyệt')
                                                <span class="badge-status badge-warning"><i class="fa-solid fa-hourglass-half me-1"></i>{{ $pivot->TrangThaiDangKy }}</span>
                                            @elseif($pivot->TrangThaiDangKy == 'Đã duyệt')
                                                <span class="badge-status badge-success"><i class="fa-solid fa-check-circle me-1"></i>{{ $pivot->TrangThaiDangKy }}</span>
                                            @elseif($pivot->TrangThaiDangKy == 'Đã hủy')
                                                <span class="badge-status badge-secondary"><i class="fa-solid fa-ban me-1"></i>{{ $pivot->TrangThaiDangKy }}</span>
                                            @else
                                                <span class="badge-status badge-danger"><i class="fa-solid fa-exclamation-circle me-1"></i>{{ $pivot->TrangThaiDangKy ?: 'Không rõ' }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($pivot->CheckInAt)
                                                <span class="badge-check check-in"><i class="fa-solid fa-check me-1"></i>{{ \Carbon\Carbon::parse($pivot->CheckInAt)->format('H:i:s') }}</span>
                                            @else
                                                <span class="badge-check check-null">Chưa có</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($pivot->CheckOutAt)
                                                <span class="badge-check check-out"><i class="fa-solid fa-check me-1"></i>{{ \Carbon\Carbon::parse($pivot->CheckOutAt)->format('H:i:s') }}</span>
                                            @else
                                                <span class="badge-check check-null">Chưa có</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($pivot->TrangThaiThamGia == 'Đã tham gia')
                                                <span class="badge-status badge-success">Đã tham gia</span>
                                            @elseif($pivot->TrangThaiThamGia == 'Vắng')
                                                <span class="badge-status badge-danger">Vắng</span>
                                            @else
                                                <span class="badge-status badge-secondary">Chưa tổng kết</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-user-slash fa-3x mb-3 d-block opacity-25"></i>
                        <p class="mb-0">Chưa có sinh viên nào đăng ký hoạt động này.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Column - Thống kê & Tác vụ --}}
    <div class="col-lg-4">
        {{-- CARD ĐIỂM DANH QR --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fa-solid fa-qrcode me-2 text-primary"></i>Điểm danh QR Code</h6>
            </div>
            <div class="card-body text-center">
                @if($hoatdong_ctxh->CheckInToken)
                    <div class="row g-3">
                        <div class="col-6">
                            <h6 class="fw-bold small text-uppercase mb-2">Check-In</h6>
                            <div class="qr-code-wrapper-small">
                                {!! QrCode::size(100)->generate(route('sinhvien.scan', ['token' => $hoatdong_ctxh->CheckInToken])) !!}
                            </div>
                            <button class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="showQRModal('checkin', '{{ route('sinhvien.scan', ['token' => $hoatdong_ctxh->CheckInToken]) }}')">
                                <i class="fa-solid fa-expand me-1"></i>Phóng to
                            </button>
                            <small class="text-muted d-block mt-1">Quét khi bắt đầu</small>
                        </div>
                        <div class="col-6">
                            <h6 class="fw-bold small text-uppercase mb-2">Check-Out</h6>
                            <div class="qr-code-wrapper-small">
                                {!! QrCode::size(100)->generate(route('sinhvien.scan', ['token' => $hoatdong_ctxh->CheckOutToken])) !!}
                            </div>
                            <button class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="showQRModal('checkout', '{{ route('sinhvien.scan', ['token' => $hoatdong_ctxh->CheckOutToken]) }}')">
                                <i class="fa-solid fa-expand me-1"></i>Phóng to
                            </button>
                            <small class="text-muted d-block mt-1">Quét khi kết thúc</small>
                        </div>
                    </div>
                @else
                    <div class="text-center py-3 text-muted">
                        <i class="fa-solid fa-camera fa-3x mb-3 d-block opacity-25"></i>
                        <p class="mb-0">Chưa tạo mã QR.</p>
                        <small>Nhấn nút "Tạo/Làm mới Mã QR" ở bên dưới.</small>
                    </div>
                @endif
            </div>
        </div>

        {{-- QR Modal --}}
        <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="qrModalTitle">QR Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-5">
                        <div id="qrModalContent"></div>
                        <p class="text-muted mt-3 mb-0" id="qrModalDescription"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fa-solid fa-chart-pie me-2 text-primary"></i>Thống kê</h6>
            </div>
            <div class="card-body">
                <div class="stat-item mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-star"></i></div>
                        <div class="ms-3 flex-grow-1">
                            <small class="text-muted d-block">Điểm CTXH</small>
                            <h4 class="mb-0 text-warning">{{ $hoatdong_ctxh->quydinh->DiemNhan ?? 'N/A' }} <small>điểm</small></h4>
                        </div>
                    </div>
                </div>

                <div class="stat-item mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-users"></i></div>
                        <div class="ms-3 flex-grow-1">
                            <small class="text-muted d-block">Số lượng</small>
                            <h4 class="mb-0 text-primary">{{ $sinhVienCount }}/{{ $hoatdong_ctxh->SoLuong }}</h4>
                        </div>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar {{ $tyLeDangKy >= 80 ? 'bg-danger' : ($tyLeDangKy >= 50 ? 'bg-warning' : 'bg-success') }}" role="progressbar" style="width: {{ $tyLeDangKy }}%;" aria-valuenow="{{ $tyLeDangKy }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted mt-1 d-block">Đã lấp đầy {{ $tyLeDangKy }}%</small>
                </div>

                <div class="stat-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fa-solid fa-check-circle"></i></div>
                        <div class="ms-3">
                            <small class="text-muted d-block">Đã duyệt</small>
                            <h5 class="mb-0 text-success">{{ $hoatdong_ctxh->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Đã duyệt')->count() }}</h5>
                        </div>
                    </div>
                </div>

                <div class="stat-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fa-solid fa-hourglass-half"></i></div>
                        <div class="ms-3">
                            <small class="text-muted d-block">Chờ duyệt</small>
                            <h5 class="mb-0 text-warning">{{ $hoatdong_ctxh->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Chờ duyệt')->count() }}</h5>
                        </div>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-secondary bg-opacity-10 text-secondary"><i class="fa-solid fa-ban"></i></div>
                        <div class="ms-3">
                            <small class="text-muted d-block">Đã hủy</small>
                            <h5 class="mb-0 text-secondary">{{ $hoatdong_ctxh->sinhVienDangKy->where('pivot.TrangThaiDangKy', 'Đã hủy')->count() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD TÁC VỤ --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fa-solid fa-cogs me-2 text-primary"></i>Quản lý & Tác vụ</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#confirmQrRefreshModal">
                    <i class="fa-solid fa-arrows-rotate me-2"></i> Tạo / Làm mới Mã QR
                </button>
                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#confirmFinalizeModal" {{ $daKetThuc ? '' : 'disabled' }} title="{{ $daKetThuc ? 'Tổng kết điểm danh' : 'Chỉ có thể tổng kết sau khi hoạt động đã kết thúc' }}">
                    <i class="fa-solid fa-clipboard-check me-2"></i> Tổng kết Điểm danh
                </button>

                {{-- Các form ẩn để modal trigger --}}
                <form id="qrForm" action="{{ route('nhanvien.hoatdong_ctxh.generate_qr', $hoatdong_ctxh) }}" method="POST" class="d-none">@csrf</form>
                <form id="finalizeForm" action="{{ route('nhanvien.hoatdong_ctxh.finalize', $hoatdong_ctxh) }}" method="POST" class="d-none">@csrf</form>

                <hr class="my-2">

                <a href="{{ route('nhanvien.hoatdong_ctxh.edit', $hoatdong_ctxh) }}" class="btn btn-warning text-white">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Chỉnh sửa
                </a>
                <a href="{{ route('nhanvien.hoatdong_ctxh.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Xác nhận Tạo QR --}}
<div class="modal fade" id="confirmQrRefreshModal" tabindex="-1" aria-labelledby="qrRefreshModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="qrRefreshModalLabel"><i class="fa-solid fa-triangle-exclamation me-2"></i>Xác nhận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                Tạo mã QR mới sẽ <strong>vô hiệu hóa</strong> mã cũ (nếu có). Bạn có chắc chắn muốn tiếp tục?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('qrForm').submit();">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Xác nhận Tổng kết --}}
<div class="modal fade" id="confirmFinalizeModal" tabindex="-1" aria-labelledby="finalizeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="finalizeModalLabel"><i class="fa-solid fa-clipboard-check me-2"></i>Xác nhận Tổng kết</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                Bạn có chắc muốn tổng kết điểm danh? <br><br>
                Hành động này sẽ ghi nhận trạng thái (Đã tham gia/Vắng) cho tất cả sinh viên <strong>Đã duyệt</strong>.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" onclick="document.getElementById('finalizeForm').submit();">Xác nhận Tổng kết</button>
            </div>
        </div>
    </div>
</div>

{{-- Style --}}
<style>
/* Info List */
.info-item { padding: 1rem 0; border-bottom: 1px solid #f0f0f0; }
.info-item:last-child { border-bottom: none; }
.info-label { font-weight: 600; color: #6c757d; font-size: 0.875rem; margin-bottom: 0.5rem; display: block; }
.info-value { margin: 0; color: #212529; font-size: 0.95rem; }

/* Stat Card */
.stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
.stat-item { padding-bottom: 1rem; border-bottom: 1px solid #f0f0f0; }
.stat-item:last-child { border-bottom: none; padding-bottom: 0; }
.avatar-xs { width: 32px; height: 32px; }
.card { border-radius: 12px; overflow: hidden; }
.badge { padding: 0.5em 0.75em; font-weight: 500; border-radius: 6px; }
.progress { height: 12px; border-radius: 10px; background-color: #e9ecef; }
.progress-bar { border-radius: 10px; }
.table > :not(caption) > * > * { padding: 0.75rem; }
.sticky-top { position: sticky; top: 0; z-index: 10; }
.btn { border-radius: 8px; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease; }
.btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.shadow-sm { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important; }
.bg-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

/* QR Code - Small */
.qr-code-wrapper-small { border: 3px solid #f3f4f6; border-radius: 10px; padding: 8px; display: inline-block; background: #fff; margin-bottom: 0.5rem; }

/* QR Code - Modal */
.qr-modal-wrapper { display: inline-block; padding: 20px; background: #fff; border: 5px solid #f3f4f6; border-radius: 16px; }
#qrModalContent svg, #qrModalContent canvas, #qrModalContent img { display: block; width: 300px; height: 300px; }

/* Avatar Circle Small */
.avatar-circle-small { width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1rem; flex-shrink: 0; }
.student-name { font-weight: 600; color: #1f2937; font-size: 0.9375rem; }
.student-code { font-size: 0.8125rem; color: #6b7280; }

/* Badge Status */
.badge-status { padding: 0.35rem 0.75rem; border-radius: 20px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; }
.badge-success { background-color: #e7f8f0; color: #0d9255; }
.badge-warning { background-color: #fff8e1; color: #f59e0b; }
.badge-danger { background-color: #fef2f2; color: #ef4444; }
.badge-secondary { background-color: #f3f4f6; color: #6b7280; }

/* Badge Check */
.badge-check { padding: 0.35rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 500; }
.badge-check.check-in, .badge-check.check-out { background-color: #e7f8f0; color: #0d9255; }
.badge-check.check-null { background-color: #f3f4f6; color: #9ca3af; }
</style>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function showQRModal(type, url) {
    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
    const modalTitle = document.getElementById('qrModalTitle');
    const modalContent = document.getElementById('qrModalContent');
    const modalDescription = document.getElementById('qrModalDescription');

    modalContent.innerHTML = '';

    if (type === 'checkin') {
        modalTitle.innerHTML = '<i class="fa-solid fa-arrow-right-to-bracket me-2 text-success"></i>QR Code Check-In';
        modalDescription.textContent = 'Sinh viên quét mã này khi bắt đầu hoạt động';
    } else {
        modalTitle.innerHTML = '<i class="fa-solid fa-arrow-right-from-bracket me-2 text-danger"></i>QR Code Check-Out';
        modalDescription.textContent = 'Sinh viên quét mã này khi kết thúc hoạt động';
    }

    try {
        new QRCode(modalContent, { text: url, width: 300, height: 300, colorDark: '#000000', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.H });
        if (modalContent.firstChild) {
            const wrapper = document.createElement('div');
            wrapper.className = 'qr-modal-wrapper';
            wrapper.appendChild(modalContent.firstChild);
            modalContent.appendChild(wrapper);
        }
    } catch (e) {
        console.error('Lỗi tạo QR Code:', e);
        modalContent.innerHTML = `<p class="text-danger">Lỗi khi tạo mã QR: ${e.message}</p>`;
    }

    modal.show();
}
</script>
@endpush
