@extends('layouts.sinhvien')
@section('title', 'Thanh toán QR Code')

@push('styles')
<style>
    .payment-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,.08);
        padding: 2.5rem;
        max-width: 600px;
        margin: 2rem auto;
        text-align: center;
    }
    .payment-header {
        border-bottom: 2px dashed #e0e0e0;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .payment-header .icon {
        font-size: 3rem;
        color: #4f46e5;
    }
    .payment-header h3 {
        color: #333;
        font-weight: 700;
        margin-top: 1rem;
    }
    .payment-amount {
        font-size: 2.5rem;
        font-weight: 800;
        color: #f5576c;
        margin: 0.5rem 0;
    }
    .qr-wrapper {
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        display: inline-block;
        background: #f8f9fa;
        margin-bottom: 1.5rem;
    }
    .qr-wrapper img {
        width: 280px;
        height: 280px;
        display: block;
    }
    .instruction-list { list-style: none; padding: 0; text-align: left; }
    .instruction-list li {
        font-weight: 500;
        color: #495057;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    .instruction-list .step-num {
        font-size: 1rem;
        font-weight: 700;
        color: #4f46e5;
        background: #eef2ff;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    .instruction-list .content {
        font-size: 0.95rem;
    }
    .content strong {
        color: #dc3545;
        font-size: 1.1rem;
        background: #fff8f8;
        padding: 2px 6px;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="payment-card">
        <div class="payment-header">
            <i class="fas fa-qrcode icon"></i>
            <h3>Thanh toán Online (VietQR)</h3>
            <p class="text-muted">Sử dụng App Ngân hàng của bạn để quét mã</p>
            <h4 class="payment-amount">{{ number_format($soTien, 0, ',', '.') }}<sup>đ</sup></h4>
        </div>

        <div class="qr-wrapper">
            {{-- API sẽ tạo ảnh QR và trả về, chúng ta chỉ cần hiển thị nó --}}
            <img src="{{ $qrUrl }}" alt="VietQR Code">
        </div>

        <div class="alert alert-warning">
            <h5 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Quan trọng!</h5>
            <p class="mb-0">Vui lòng đảm bảo bạn nhập **ĐÚNG** nội dung chuyển khoản để được xác nhận tự động (hoặc để nhân viên đối soát).</p>
        </div>

        <ul class="instruction-list mt-4">
            <li>
                <span class="step-num">1</span>
                <span class="content">Mở App Ngân hàng (hoặc Momo) và chọn "Quét mã QR".</span>
            </li>
            <li>
                <span class="step-num">2</span>
                <span class="content">Quét mã ở trên. Số tiền và STK sẽ được điền tự động.</span>
            </li>
            <li>
                <span class="step-num">3</span>
                <span class="content">
                    Kiểm tra kỹ **Nội dung chuyển khoản** phải là:
                    <strong>{{ $noiDungChuyenKhoan }}</strong>
                </span>
            </li>
            <li>
                <span class="step-num">4</span>
                <span class="content">Hoàn tất chuyển khoản và chờ Nhân viên xác nhận.</span>
            </li>
        </ul>

        <hr>
        <a href="{{ route('sinhvien.quanly_dangky.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Quay lại
        </a>

    </div>
</div>
@endsection