@extends('layouts.giangvien')

@section('title', 'Phân bổ Số lượng Hoạt động')
@section('page_title', 'Phân bổ Số lượng Hoạt động')

@php
$breadcrumbs = [
    ['url' => route('giangvien.home'), 'title' => 'Bảng điều khiển'],
    ['url' => route('giangvien.hoatdong.phanbo.index'), 'title' => 'Phân bổ số lượng'],
];
@endphp

@section('content')
<div class="card modern-card">
    <div class="card-header modern-card-header">
        <i class="fa-solid fa-sliders me-2"></i>Danh sách Hoạt động DRL bạn phụ trách
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th>Hoạt động</th>
                        <th class="text-center">Học kỳ</th>
                        <th class="text-center">Tổng Số Lượng</th>
                        <th class="text-center">Đã Phân Bổ</th>
                        <th class="text-center">Đã Đăng Ký</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hoatDongs as $hd)
                    @php
                        // Kiểm tra trạng thái phân bổ
                        $isAllocated = ($hd->SoLuong == $hd->DaPhanBo);
                    @endphp
                    <tr class="table-row-hover">
                        <td>
                            <div class="student-name">{{ $hd->TenHoatDong }}</div>
                            <div class="student-code">{{ $hd->MaHoatDong }}</div>
                        </td>
                        <td class="text-center">{{ $hd->hocKy->TenHocKy ?? $hd->MaHocKy }}</td>
                        <td class="text-center fw-bold text-primary">{{ $hd->SoLuong }}</td>
                        <td class="text-center fw-bold {{ $isAllocated ? 'text-success' : 'text-danger' }}">
                            {{ (int)$hd->DaPhanBo }}
                            @if(!$isAllocated)
                                <i class="fa-solid fa-triangle-exclamation ms-1" title="Chưa khớp"></i>
                            @endif
                        </td>
                        <td class="text-center fw-bold">{{ $hd->dangky_count }}</td>
                        <td class="text-center">
                            <a href="{{ route('giangvien.hoatdong.phanbo.edit', $hd->MaHoatDong) }}" class="btn btn-sm {{ $isAllocated ? 'btn-outline-primary' : 'btn-primary' }}">
                                <i class="fa-solid fa-sliders me-1"></i> {{ $isAllocated ? 'Chỉnh sửa' : 'Phân bổ' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                <h5 class="empty-title">Không có hoạt động nào</h5>
                                <p class="empty-text">Bạn hiện không được gán phụ trách hoạt động nào.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($hoatDongs->hasPages())
        <div class="pagination-wrapper">
            {{ $hoatDongs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
@import url('https://pastebin.com/raw/L8C35G0J'); /* CSS Chung */
</style>
@endpush