@extends('layouts.sinhvien')

@section('title', 'Lịch Hoạt Động Tuần')

@push('styles')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  :root {
    --primary: #667eea;
    --secondary: #764ba2;
    --success: #10b981;
    --info: #3b82f6;
    --warning: #f59e0b;
    --danger: #ef4444;
    --theory: #10b981;
    --practice: #3b82f6;
    --online: #f59e0b;
    --suspended: #ef4444;
  }

  body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
    min-height: 100vh;
  }

  .schedule-wrapper {
    max-width: 1600px;
    margin: 0 auto;
    padding: 2rem 1rem;
  }

  .schedule-container {
    background: #fff;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .1);
  }

  .schedule-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    padding: 2rem;
    border-radius: 24px 24px 0 0;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
  }

  .schedule-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, .1);
    border-radius: 50%;
  }

  .schedule-header h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: .5rem;
    position: relative;
    z-index: 1;
  }

  .week-info {
    font-size: 1.1rem;
    opacity: .95;
    position: relative;
    z-index: 1;
  }

  .week-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: #fff;
    border-bottom: 2px solid #e2e8f0;
  }

  .nav-btn {
    padding: .75rem 1.5rem;
    border-radius: 50px;
    border: 2px solid #e2e8f0;
    background: #fff;
    color: #475569;
    font-weight: 600;
    cursor: pointer;
    transition: .3s;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    text-decoration: none;
  }

  .nav-btn:hover {
    border-color: var(--primary);
    background: var(--primary);
    color: #fff;
    transform: translateY(-2px);
  }

  .today-btn {
    background: linear-gradient(135deg, var(--success), #34d399);
    color: #fff;
    border: none;
  }

  .today-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(16, 185, 129, .4);
    background: linear-gradient(135deg, var(--success), #34d399);
  }

  .schedule-grid {
    display: grid;
    grid-template-columns: 100px repeat(7, 1fr);
    grid-template-rows: auto auto auto auto;
    background: #fff;
  }

  .time-col {
    background: #f8fafc;
    border-right: 2px solid #e2e8f0;
    display: contents;
  }

  .time-slot {
    padding: 1.5rem 1rem;
    text-align: center;
    font-weight: 700;
    color: #475569;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: .25rem;
    background: #f8fafc;
    border-right: 2px solid #e2e8f0;
  }

  .time-slot.header {
    background: linear-gradient(135deg, #334155, #475569);
    color: #fff;
    font-size: .9rem;
    font-weight: 800;
    min-height: 150px;
    z-index: 1;
  }

  .time-label {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
  }

  .day-col {
    border-right: 1px solid #e2e8f0;
    display: contents;
  }

  .day-col:last-child {
    border-right: none;
  }

  .day-header {
    padding: 1.5rem 1rem;
    text-align: center;
    background: linear-gradient(135deg, #f8fafd, #f1f5f9);
    border-bottom: 2px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: .25rem;
  }

  .day-header.today {
    background: linear-gradient(135deg, rgba(102, 126, 234, .1), rgba(118, 75, 162, .1));
    border-bottom-color: var(--primary);
  }

  .day-name {
    font-weight: 800;
    font-size: 1rem;
    color: #1e293b;
  }

  .day-date {
    font-size: .85rem;
    color: #64748b;
    font-weight: 600;
  }

  .today-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--success), #34d399);
    color: #fff;
    padding: .25rem .75rem;
    border-radius: 50px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-top: .25rem;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0%, 100% {
      transform: scale(1);
      box-shadow: 0 2px 8px rgba(16, 185, 129, .3);
    }
    50% {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(16, 185, 129, .4);
    }
  }

  .class-cell {
    padding: .75rem;
    border-bottom: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    position: relative;
    transition: .3s;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: 120px;
  }

  .class-cell:hover {
    background: #fafbfc;
  }

  .class-card {
    background: #fff;
    border-radius: 8px;
    padding: .75rem;
    margin-bottom: .5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
    border-left: 4px solid;
    transition: .3s;
    cursor: pointer;
    position: relative;
    overflow: visible;
    width: 100%;
  }

  .class-card.has-actions {
    padding-bottom: 2.5rem;
  }

  .class-actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    border-top: 1px solid #f1f5f9;
    background: #fff;
    border-radius: 0 0 6px 6px;
  }

  .btn-scan {
    flex: 1;
    padding: .5rem;
    text-align: center;
    font-size: .75rem;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    transition: .3s;
  }

  .btn-scan.check-in {
    background: rgba(16, 185, 129, .1);
    color: var(--success);
  }

  .btn-scan.check-in:hover {
    background: var(--success);
    color: #fff;
  }

  .btn-scan.check-out {
    background: rgba(245, 158, 11, .1);
    color: var(--online);
    border-left: 1px solid #f1f5f9;
  }

  .btn-scan.check-out:hover {
    background: var(--online);
    color: #fff;
  }

  .class-card::before {
    content: '';
    position: absolute;
    inset: 0;
    opacity: .05;
    transition: opacity .3s;
  }

  .class-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
  }

  .class-card:hover::before {
    opacity: .1;
  }

  .class-card.theory {
    border-left-color: var(--theory);
  }

  .class-card.theory::before {
    background: var(--theory);
  }

  .class-card.practice {
    border-left-color: var(--practice);
  }

  .class-card.practice::before {
    background: var(--practice);
  }

  .class-card.online {
    border-left-color: var(--online);
  }

  .class-card.online::before {
    background: var(--online);
  }

  .class-card.suspended {
    border-left-color: var(--suspended);
  }

  .class-card.suspended::before {
    background: var(--suspended);
  }

  .class-type-badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: 50px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: .5rem;
  }

  .class-card.theory .class-type-badge {
    background: rgba(16, 185, 129, .15);
    color: var(--theory);
  }

  .class-card.practice .class-type-badge {
    background: rgba(59, 130, 246, .15);
    color: var(--practice);
  }

  .class-card.online .class-type-badge {
    background: rgba(245, 158, 11, .15);
    color: var(--online);
  }

  .class-card.suspended .class-type-badge {
    background: rgba(239, 68, 68, .15);
    color: var(--suspended);
  }

  .class-name {
    font-weight: 800;
    color: #1e293b;
    margin-bottom: .5rem;
    font-size: .9rem;
    line-height: 1.4;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .class-code {
    font-size: .75rem;
    color: #64748b;
    margin-bottom: .5rem;
    font-weight: 600;
  }

  .class-time {
    font-size: .8rem;
    color: #475569;
    margin-bottom: .25rem;
    display: flex;
    align-items: center;
    gap: .35rem;
  }

  .class-time i {
    font-size: .7rem;
    color: #94a3b8;
  }

  .class-location {
    font-size: .8rem;
    color: #475569;
    display: flex;
    align-items: center;
    gap: .35rem;
    margin-bottom: .25rem;
  }

  .class-location i {
    font-size: .7rem;
    color: #94a3b8;
  }

  .class-teacher {
    font-size: .75rem;
    color: #64748b;
    margin-top: .5rem;
    padding-top: .5rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: .35rem;
  }

  .legend {
    padding: 1.5rem 2rem;
    background: #f8fafc;
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
    border-top: 2px solid #e2e8f0;
  }

  .legend-item {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: .85rem;
    font-weight: 600;
    color: #475569;
  }

  .legend-color {
    width: 20px;
    height: 20px;
    border-radius: 6px;
    border: 2px solid;
  }

  .legend-color.theory {
    background: rgba(16, 185, 129, .2);
    border-color: var(--theory);
  }

  .legend-color.practice {
    background: rgba(59, 130, 246, .2);
    border-color: var(--practice);
  }

  .legend-color.online {
    background: rgba(245, 158, 11, .2);
    border-color: var(--online);
  }

  .legend-color.suspended {
    background: rgba(239, 68, 68, .2);
    border-color: var(--suspended);
  }

  @media (max-width: 1200px) {
    .schedule-grid {
      grid-template-columns: 80px repeat(7, 1fr);
    }

    .class-name {
      font-size: .85rem;
    }

    .class-time,
    .class-location {
      font-size: .75rem;
    }
  }

  @media (max-width: 768px) {
    .schedule-wrapper {
      padding: 1rem;
    }

    .schedule-header h1 {
      font-size: 1.5rem;
    }

    .week-nav {
      flex-direction: column;
      gap: 1rem;
    }

    .schedule-grid {
      display: flex;
      flex-direction: column;
    }

    .time-col {
      display: none;
    }

    .day-col {
      display: block;
      border-right: none;
      border-bottom: 2px solid #e2e8f0;
    }

    .class-cell {
      display: block;
      min-height: auto;
    }
  }
</style>
@endpush

@section('content')
<div class="schedule-wrapper">
  <div class="schedule-container">

    {{-- Header --}}
    <div class="schedule-header">
      <h1>
        <i class="fas fa-calendar-alt me-2"></i>
        Lịch Hoạt Động
      </h1>
      <div class="week-info">
        Tuần: {{ $startOfWeek->format('d/m') }} - {{ $endOfWeek->format('d/m/Y') }}
      </div>
    </div>

    {{-- Điều hướng tuần --}}
    <div class="week-nav">
      <a href="{{ route('sinhvien.lich_tuan') }}?date={{ $prevWeek->toDateString() }}" class="nav-btn">
        <i class="fas fa-chevron-left"></i> Tuần trước
      </a>

      <a href="{{ route('sinhvien.lich_tuan') }}" class="nav-btn today-btn">
        <i class="fas fa-home"></i> Tuần hiện tại
      </a>

      <a href="{{ route('sinhvien.lich_tuan') }}?date={{ $nextWeek->toDateString() }}" class="nav-btn">
        Tuần sau <i class="fas fa-chevron-right"></i>
      </a>
    </div>

    {{-- Lưới lịch --}}
    <div class="schedule-grid">
      {{-- Cột thời gian --}}
      <div class="time-col">
        <div class="time-slot header" style="grid-row:1;grid-column:1;">Ca</div>

        <div class="time-slot" style="grid-row:2;grid-column:1;">
          <div>Sáng</div>
          <div class="time-label">7:00 - 11:55</div>
        </div>

        <div class="time-slot" style="grid-row:3;grid-column:1;">
          <div>Chiều</div>
          <div class="time-label">12:30 - 17:25</div>
        </div>

        <div class="time-slot" style="grid-row:4;grid-column:1;">
          <div>Tối</div>
          <div class="time-label">18:00 - 21:45</div>
        </div>
      </div>

      {{-- Các ngày trong tuần --}}
      @foreach ($daysOfWeek as $dayData)
        @php
          $morning   = [];
          $afternoon = [];
          $evening   = [];

          foreach ($dayData['activities'] as $item) {
              /** @var \App\Models\HoatDong $activity */
              $activity = $item['hoatdong'];
              $hour     = $activity->ThoiGianBatDau->format('H');

              if ($hour < 12) {
                  $morning[] = $item;
              } elseif ($hour < 18) {
                  $afternoon[] = $item;
              } else {
                  $evening[] = $item;
              }
          }

          $slots = [
              2 => $morning,   // Sáng
              3 => $afternoon, // Chiều
              4 => $evening,   // Tối
          ];

          // Cột tương ứng trong grid
          $col = $loop->index + 2;
        @endphp

        <div class="day-col">
          {{-- Header ngày --}}
          <div
            class="day-header {{ $dayData['date']->isToday() ? 'today' : '' }}"
            style="grid-row:1;grid-column:{{ $col }};"
          >
            <div class="day-name">{{ $dayData['date']->isoFormat('dddd') }}</div>
            <div class="day-date">{{ $dayData['date']->format('d/m/Y') }}</div>

            @if ($dayData['date']->isToday())
              <span class="today-badge">Hôm nay</span>
            @endif
          </div>

          {{-- Các ca trong ngày --}}
          @foreach ($slots as $row => $slotItems)
            <div class="class-cell" style="grid-row:{{ $row }};grid-column:{{ $col }};">
              @foreach ($slotItems as $item)
                @php
                  /** @var \App\Models\HoatDong $activity */
                  $activity     = $item['hoatdong'];
                  $regStatus    = $item['trang_thai'];
                  $joinStatus   = $item['trang_thai_tham_gia'];
                  $isApproved   = $regStatus === 'Đã duyệt';
                  $isCheckedIn  = !empty($item['check_in_at']);
                  $isCheckedOut = !empty($item['check_out_at']);

                  // Xác định class màu thẻ
                  $cardClass = $item['type'] === 'DRL' ? 'theory' : 'practice';

                  if ($regStatus === 'Bị từ chối' || $joinStatus === 'Vắng') {
                      $cardClass = 'suspended';
                  } elseif ($regStatus === 'Chưa duyệt') {
                      $cardClass = 'online';
                  }

                  // Hiển thị nút Check In/Out
                  $showActions = $dayData['date']->isToday()
                      && $isApproved
                      && $activity->CheckInToken
                      && !$isCheckedOut;

                  $hasActionsClass = $showActions ? 'has-actions' : '';

                  // Text trạng thái
                  $statusText = $joinStatus ?? $regStatus;

                  if ($isCheckedIn && !$isCheckedOut) {
                      $statusText = 'Đã Check In';
                  } elseif ($isCheckedOut) {
                      $statusText = 'Đã tham gia';
                  }
                @endphp

                <div class="class-card {{ $cardClass }} {{ $hasActionsClass }}">
                  <span class="class-type-badge">{{ $item['type'] }}</span>

                  <div class="class-name">{{ $activity->TenHoatDong }}</div>
                  <div class="class-code">{{ $statusText }}</div>

                  <div class="class-time">
                    <i class="fas fa-clock"></i>
                    {{ $activity->ThoiGianBatDau->format('H:i') }} -
                    {{ $activity->ThoiGianKetThuc->format('H:i') }}
                  </div>

                  <div class="class-location">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $activity->DiaDiem ?? 'Chưa xác định' }}
                  </div>

                  @if ($showActions)
                    <div class="class-actions">
                      @if (! $isCheckedIn)
                        <a
                          href="{{ route('sinhvien.scan.camera') . '?token=' . urlencode($activity->CheckInToken ?? '') }}"
                          class="btn-scan check-in"
                        >
                          Check In
                        </a>
                      @else
                        <a
                          href="{{ route('sinhvien.scan.camera') . '?token=' . urlencode($activity->CheckOutToken ?? '') }}"
                          class="btn-scan check-out"
                        >
                          Check Out
                        </a>
                      @endif
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  </div>

  {{-- Ghi chú màu --}}
  <div class="legend">
    <div class="legend-item">
      <div class="legend-color theory"></div>
      <span>Hoạt động DRL</span>
    </div>

    <div class="legend-item">
      <div class="legend-color practice"></div>
      <span>Hoạt động CTXH</span>
    </div>

    <div class="legend-item">
      <div class="legend-color online"></div>
      <span>Chờ duyệt</span>
    </div>

    <div class="legend-item">
      <div class="legend-color suspended"></div>
      <span>Bị từ chối / Vắng</span>
    </div>
  </div>
</div>

{{-- Modal quét QR --}}
<div
  class="modal fade"
  id="qrScanModal"
  tabindex="-1"
  aria-labelledby="qrScanLabel"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width:680px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="qrScanLabel" class="modal-title">
          <i class="fas fa-qrcode me-2"></i>
          Quét mã QR để điểm danh
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <div class="modal-body">
        <div id="qr-reader" style="width:100%;"></div>

        <div class="mt-3">
          <label class="form-label">Nhập token thủ công (nếu camera lỗi)</label>
          <div class="input-group">
            <input
              id="manualToken"
              type="text"
              class="form-control"
              placeholder="Dán/nhập token từ QR..."
            >
            <button id="btnManualGo" class="btn btn-primary" type="button">
              Điểm danh
            </button>
          </div>

          <small class="text-muted">
            Trình duyệt có thể yêu cầu quyền Camera — hãy bấm
            <strong>Cho phép</strong>.
          </small>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
  (function () {
    let html5QrCode = null;

    const modalEl     = document.getElementById('qrScanModal');
    // Build scan URL on client to avoid blade-injected JS issues
    function buildScanUrl(token) {
      // If token already looks like a full URL, return it
      try {
        if (/^https?:\/\//i.test(token)) return token;
      } catch (e) {}
      // Otherwise build relative URL
      return window.location.origin + '/sinhvien/scan/' + encodeURIComponent(token);
    }
    const manualBtn   = document.getElementById('btnManualGo');
    const manualInput = document.getElementById('manualToken');

    // Bắt click vào các nút Check In / Check Out để mở modal
    document.addEventListener('click', function (event) {
      const btn = event.target.closest('a.btn-scan');
      if (!btn) return;

      event.preventDefault();

      // Nếu nút có token, đặt giá trị này vào ô nhập tay (fallback)
      try {
        const token = btn.getAttribute('data-scan-token') || '';
        if (manualInput) manualInput.value = token;
      } catch (e) {
        console.warn('Không thể set token vào ô manual:', e);
      }

      const bsModal = new bootstrap.Modal(modalEl);
      bsModal.show();

      modalEl.addEventListener('shown.bs.modal', startScanner, { once: true });
      modalEl.addEventListener('hidden.bs.modal', stopScanner, { once: true });
    });

    function startScanner() {
      const containerId = 'qr-reader';

      try {
        html5QrCode = new Html5Qrcode(containerId);

        const config = {
          fps: 10,
          qrbox: 280,
          rememberLastUsedCamera: true,
          aspectRatio: 1.333
        };

        Html5Qrcode.getCameras()
          .then(function (devices) {
            const backCam  = devices.find(function (device) {
              return /back|environment/i.test(device.label);
            });

            const cameraId = (backCam && backCam.id) || (devices[0] && devices[0].id);

            if (!cameraId) {
              throw new Error('Không tìm thấy camera.');
            }

            html5QrCode.start(
              { deviceId: { exact: cameraId } },
              config,
              onScanSuccess,
              onScanFailure
            );
          })
          .catch(function (error) {
            console.error(error);
            alert('Không truy cập được camera: ' + error);
          });

      } catch (error) {
        console.error(error);
        alert('Không khởi động được trình quét: ' + error.message);
      }
    }

    function stopScanner() {
      if (!html5QrCode) return;

      html5QrCode.stop()
        .then(function () {
          html5QrCode.clear();
          html5QrCode = null;
        })
        .catch(function (error) {
          console.warn('Stop scanner error:', error);
        });
    }

    function onScanSuccess(decodedText) {
      const url = buildScanUrl(decodedText);
      window.location.href = url;
    }

    function onScanFailure() {
      // Bỏ qua để tránh spam log
    }

    // Fallback nhập tay
    manualBtn.addEventListener('click', function () {
      const token = manualInput.value.trim();
      if (!token) {
        alert('Vui lòng nhập token.');
        return;
      }

  const url = buildScanUrl(token);
  window.location.href = url;
    });
  })();
</script>
@endpush
