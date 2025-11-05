

<?php $__env->startSection('title', 'Quản lý lớp'); ?>
<?php $__env->startSection('page_title', 'Danh sách lớp'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Kế thừa và đồng bộ hóa các biến CSS từ trang Quản lý khoa */
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --primary-light: #818cf8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --dark: #1e293b;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-600: #475569;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    /* Styles cho Thống kê (Stats) */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        position: relative;
        border-radius: 16px;
        background: white;
        padding: 1.75rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary-light));
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-light);
    }

    .stat-card-content {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        box-shadow: 0 8px 16px -4px rgba(99, 102, 241, 0.4);
    }

    .stat-info h6 {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }

    .stat-info .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1;
    }
    
    /* Styles cho Main Card và Header */
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
    }

    .card-header-modern {
        background: linear-gradient(to right, var(--gray-50), white);
        padding: 1.75rem 2rem;
        border-bottom: 2px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-title-modern {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .card-title-modern i {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border-radius: 10px;
        font-size: 1.125rem;
    }

    /* Styles cho Button */
    .btn-modern {
        padding: 0.625rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .btn-primary-modern:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        color: white;
    }

    /* Styles cho Search Section và Form Control */
    .search-section {
        padding: 1.75rem 2rem; /* Giảm padding cho search section để nó trông gọn hơn */
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
    }

    .search-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center; /* Đảm bảo các phần tử nằm trên cùng một hàng */
    }

    .search-input-wrapper {
        flex: 1;
        min-width: 200px; /* Điều chỉnh lại min-width để phù hợp với lọc */
        position: relative;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-600);
    }

    .form-control-modern, .form-select-modern {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.75rem;
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        background: white;
        appearance: none; /* Xóa style mặc định của select */
    }
    
    .form-select-modern {
        padding: 0.75rem 1rem; /* Bỏ icon cho select nên padding bình thường */
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 0.65em 0.65em;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    .search-select-wrapper {
        min-width: 200px;
        flex-basis: 250px; /* Định nghĩa độ rộng cho select box */
    }

    .btn-search {
        padding: 0.75rem 1.75rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0; /* Không co lại */
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .btn-reset {
        padding: 0.75rem 1.75rem;
        background: white;
        color: var(--gray-600);
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0; /* Không co lại */
    }

    .btn-reset:hover {
        background: var(--gray-50);
        border-color: var(--gray-600);
        color: var(--gray-600);
    }
    
    /* Styles cho Table */
    .table-modern {
        width: 100%;
        margin: 0;
    }

    .table-modern thead {
        background: linear-gradient(to right, var(--gray-50), var(--gray-100));
    }

    .table-modern thead th {
        padding: 1.25rem 1.5rem;
        font-size: 0.8125rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-600);
        border-bottom: 2px solid var(--gray-200);
    }

    .table-modern tbody td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        vertical-align: middle;
        color: var(--dark);
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: var(--gray-50);
    }

    .badge-code {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(79, 70, 229, 0.15));
        color: var(--primary-dark);
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }
    
    .badge-info {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.15));
        color: #2563eb;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    /* Styles cho Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-action-modern {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        color: white;
    }

    .btn-action-modern:hover {
        transform: translateY(-2px);
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--warning), #d97706);
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .btn-edit:hover {
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, var(--danger), #dc2626);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-delete:hover {
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    /* Styles cho Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray-600);
        font-size: 2rem;
    }

    .empty-state h5 {
        color: var(--dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--gray-600);
        margin: 0;
    }

    /* Styles cho Pagination */
    .pagination-wrapper {
        padding: 1.5rem 2rem;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
        display: flex;
        justify-content: flex-end;
    }

    .pagination {
        margin: 0;
        gap: 0.5rem;
    }

    .page-link {
        border-radius: 8px;
        border: 1px solid var(--gray-200);
        color: var(--gray-600);
        font-weight: 600;
        padding: 0.5rem 0.875rem;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }

    /* Media Queries */
    @media (max-width: 992px) {
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-input-wrapper, .search-select-wrapper {
            min-width: 100%;
            flex-basis: 100%;
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .table-modern {
            font-size: 0.875rem;
        }

        .table-modern thead th,
        .table-modern tbody td {
            padding: 1rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon">
                    <i class="fa-solid fa-graduation-cap"></i> </div>
                <div class="stat-info">
                    <h6>Tổng số lớp</h6>
                    
                    <div class="stat-value"><?php echo e($total ?? 0); ?></div> 
                </div>
            </div>
        </div>
        
        
        
    </div>

    <div class="main-card">
        <div class="card-header-modern">
            <h2 class="card-title-modern">
                <i class="fa-solid fa-list-ul"></i>
                <span>Danh sách lớp</span>
            </h2>
            <a href="<?php echo e(route('admin.lop.create')); ?>" class="btn-modern btn-primary-modern">
                <i class="fa-solid fa-plus"></i>
                Thêm lớp mới
            </a>
        </div>

        <div class="search-section">
            <form method="GET" action="<?php echo e(route('admin.lop.index')); ?>" class="search-form">
                
                
                <div class="search-input-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" 
                            name="keyword" 
                            value="<?php echo e(request('keyword')); ?>"
                            class="form-control-modern" 
                            placeholder="Tìm kiếm theo mã lớp hoặc tên lớp...">
                </div>
                
                
                <div class="search-select-wrapper">
                    <select name="MaKhoa" class="form-select-modern">
                        <option value="">-- Tất cả khoa --</option>
                        
                        <?php $__currentLoopData = $khoas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <option value="<?php echo e($k->MaKhoa); ?>" <?php echo e(request('MaKhoa') == $k->MaKhoa ? 'selected' : ''); ?>>
                                <?php echo e($k->TenKhoa); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                
                <button type="submit" class="btn-search">
                    <i class="fa-solid fa-search"></i>
                    Tìm kiếm
                </button>
                
                
                <?php if(request()->filled('keyword') || request()->filled('MaKhoa')): ?>
                <a href="<?php echo e(route('admin.lop.index')); ?>" class="btn-reset">
                    <i class="fa-solid fa-rotate-right"></i>
                    Đặt lại
                </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="text-center" width="80">STT</th>
                        <th width="150">Mã lớp</th>
                        <th>Tên lớp</th>
                        <th width="200">Khoa</th>
                        <th class="text-center" width="160">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php $__empty_1 = true; $__currentLoopData = $lops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $lop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center fw-semibold">
                            <?php echo e($lops->firstItem() + $index); ?>

                        </td>
                        <td>
                            <span class="badge-code">
                                <?php echo e($lop->MaLop); ?>

                            </span>
                        </td>
                        <td class="fw-semibold"><?php echo e($lop->TenLop); ?></td>
                        <td>
                             <span class="badge-info">
                                <?php echo e($lop->khoa->TenKhoa ?? 'Chưa xác định'); ?>

                             </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo e(route('admin.lop.edit', $lop->MaLop)); ?>" 
                                   class="btn-action-modern btn-edit" 
                                   title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="<?php echo e(route('admin.lop.destroy', $lop->MaLop)); ?>" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa lớp <?php echo e($lop->TenLop); ?> này không?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="btn-action-modern btn-delete" 
                                            title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>
                                <h5>Không tìm thấy dữ liệu</h5>
                                <p>Không có lớp nào được tìm thấy theo tiêu chí của bạn. Hãy thử tìm kiếm khác hoặc <a href="<?php echo e(route('admin.lop.create')); ?>">thêm lớp mới</a>.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($lops->hasPages()): ?>
        <div class="pagination-wrapper">
            <?php echo e($lops->appends(request()->query())->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/admin/lop/index.blade.php ENDPATH**/ ?>