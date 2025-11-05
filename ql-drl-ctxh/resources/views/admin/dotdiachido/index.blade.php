@extends('layouts.admin') {{-- Giả sử layout Admin của bạn là 'layouts.admin' --}}

@section('title', 'Quản lý Đợt (Địa chỉ đỏ)')

@section('content')
<div class="container-fluid">
    <h4 class="my-4">Quản lý Đợt (Địa chỉ đỏ)</h4>

    <!-- Nút Thêm Mới -->
    <div class="mb-3">
        <a href="{{ route('admin.dotdiachido.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm đợt mới
        </a>
    </div>

    <!-- Thông báo Session -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Bảng Dữ liệu -->
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Danh sách các Đợt</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên Đợt</th>
                            <th scope="col">Ngày Bắt Đầu</th>
                            <th scope="col">Ngày Kết Thúc</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dots as $index => $item)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    {{-- TODO: Thêm link đến trang chi tiết các suất (Giai đoạn 3.3) --}}
                                    {{-- <a href="{{ route('admin.dotdiachido.show', $item->id) }}">{{ $item->TenDot }}</a> --}}
                                    {{ $item->TenDot }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->NgayBatDau)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->NgayKetThuc)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($item->TrangThai == 'DangDienRa')
                                        <span class="badge bg-success">Đang diễn ra</span>
                                    @elseif ($item->TrangThai == 'SapDienRa')
                                        <span class="badge bg-warning text-dark">Sắp diễn ra</span>
                                    @else
                                        <span class="badge bg-secondary">Đã kết thúc</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Nút Sửa -->
                                    <a href="{{ route('admin.dotdiachido.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Sửa đợt">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Nút Xóa (dùng form) -->
                                    <form action="{{ route('admin.dotdiachido.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đợt này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa đợt">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Chưa có đợt nào được tạo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Phân trang -->
            <div class="mt-3">
                {{ $dots->links() }}
            </div>
        </div>
    </div>
</div>
@endsection