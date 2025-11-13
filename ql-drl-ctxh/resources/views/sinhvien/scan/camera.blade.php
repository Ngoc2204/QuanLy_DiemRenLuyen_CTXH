@php
    $title = 'Quét QR điểm danh';
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="/css/layout.css">
    <style>
        .scanner-wrapper { max-width: 700px; margin: 1.5rem auto; text-align: center; }
        video { width: 100%; height: auto; border-radius: 8px; background: #000; }
        .controls { margin-top: 0.75rem; display:flex; gap:0.5rem; justify-content:center; }
        .manual { margin-top: 1rem; }
        .notice { margin-top:0.75rem; color:#555; }
    </style>
</head>
<body>
<div class="scanner-wrapper">
    <h1>{{ $title }}</h1>

    <div id="reader">
        <video id="video" playsinline></video>
    </div>

    <div class="controls">
        <button id="startBtn" type="button">Mở camera</button>
        <button id="stopBtn" type="button" disabled>Đóng camera</button>
    </div>

    <div class="notice">Khi quét thành công trang sẽ tự chuyển hướng đến xử lý điểm danh.</div>

    <div class="manual">
        <label for="manualToken">Nếu camera không hoạt động, dán mã/token ở đây:</label>
        <div style="display:flex;gap:.5rem;margin-top:.5rem;justify-content:center;">
            <input id="manualToken" type="text" placeholder="Dán token hoặc URL" style="width:60%" />
            <button id="manualGo" type="button">Gửi</button>
        </div>
    </div>
</div>

<!-- ZXing library (UMD build) -->
<script src="https://unpkg.com/@zxing/library@0.18.6"></script>
<script>
    (function(){
        // Prefill manual token from query param ?token=...
        try {
            const params = new URLSearchParams(window.location.search);
            const pre = params.get('token');
            if (pre) {
                const el = document.getElementById('manualToken');
                if (el) el.value = decodeURIComponent(pre);
            }
        } catch (e) {
            console.warn('Không thể đọc token từ URL', e);
        }
        const videoElem = document.getElementById('video');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const manualToken = document.getElementById('manualToken');
        const manualGo = document.getElementById('manualGo');

        // Attach manual handler early so it works even if ZXing fails to load
        manualGo.addEventListener('click', function(){
            const val = manualToken.value.trim();
            if (!val) return alert('Vui lòng nhập mã/token.');
            if (val.indexOf('/sinhvien/scan/') !== -1 || /^https?:\/\//i.test(val)) {
                window.location.href = val;
            } else {
                window.location.href = '/sinhvien/scan/' + encodeURIComponent(val);
            }
        });

        let selectedDeviceId = null;
        let codeReader = null;

        function handleResult(text) {
            // If scanned value contains a path to our app, redirect. Otherwise treat as token.
            try {
                const lower = text.toString();
                // If it's a full URL and contains '/sinhvien/scan/', redirect directly
                if (/\/sinhvien\/scan\//.test(lower)) {
                    window.location.href = lower;
                    return;
                }

                // Otherwise, assume it's a token and build route
                const token = encodeURIComponent(text);
                const url = '/sinhvien/scan/' + token;
                window.location.href = url;
            } catch (e) {
                console.error('Redirect error', e);
            }
        }

        function startScanner() {
            if (!codeReader) return alert('Trình quét chưa sẵn sàng. Vui lòng thử lại.');
            startBtn.disabled = true;
            stopBtn.disabled = false;

            codeReader.listVideoInputDevices()
                .then((videoInputDevices) => {
                    if (videoInputDevices.length === 0) {
                        alert('Không tìm thấy camera trên thiết bị này.');
                        startBtn.disabled = false;
                        stopBtn.disabled = true;
                        return;
                    }

                    const back = videoInputDevices.find(d => /rear|back|environment/i.test(d.label));
                    selectedDeviceId = (back && back.deviceId) || videoInputDevices[0].deviceId;

                    codeReader.decodeFromVideoDevice(selectedDeviceId, videoElem, (result, err) => {
                        if (result) {
                            stopScanner();
                            handleResult(result.getText());
                        }
                    });
                })
                .catch(err => {
                    console.error(err);
                    alert('Không thể khởi tạo camera: ' + (err && err.message));
                    startBtn.disabled = false;
                    stopBtn.disabled = true;
                });
        }

        function stopScanner() {
            try {
                if (codeReader) codeReader.reset();
            } catch (e) {
                console.warn('Error stopping scanner', e);
            }
            startBtn.disabled = false;
            stopBtn.disabled = true;
        }

        // Try to initialize ZXing; if it fails, attach safe handlers so buttons still respond
        try {
            if (window && window.ZXing && ZXing.BrowserMultiFormatReader) {
                codeReader = new ZXing.BrowserMultiFormatReader();
                startBtn.addEventListener('click', startScanner);
                stopBtn.addEventListener('click', stopScanner);
            } else {
                throw new Error('ZXing library not available');
            }
        } catch (e) {
            console.warn('ZXing init failed:', e);
            // Graceful handlers: inform user and allow manual token submission
            startBtn.addEventListener('click', function(){
                alert('Trình quét QR (ZXing) chưa sẵn sàng trên trình duyệt này. Vui lòng dán token và nhấn Gửi.');
            });
            stopBtn.addEventListener('click', function(){
                alert('Camera chưa được bật.');
            });
        }

        // Auto-attempt to start camera on mobile when page loads (some browsers block auto-start without user gesture)
        // We keep controls so user can explicitly start if auto attempt fails.
        if (/Mobi|Android/i.test(navigator.userAgent)) {
            // try starting after a short delay
            setTimeout(() => {
                // some mobile browsers require a user interaction; don't force a prompt
                // so only pre-enable start button
                startBtn.disabled = false;
            }, 300);
        }

    })();
</script>
</body>
</html>
