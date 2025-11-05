

<?php $__env->startSection('content'); ?>

    <div class="grades-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="bi bi-book-half"></i>
                Thông tin học tập
            </h1>
            <p class="page-subtitle">Xem bảng điểm và kết quả học tập của bạn</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <?php if($diemHocKy->isEmpty()): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3>Chưa có dữ liệu</h3>
                <p>Bảng điểm của bạn sẽ được cập nhật sau khi kỳ thi kết thúc</p>
            </div>
        <?php else: ?>
            <!-- GPA Overview Card -->
            <div class="overview-cards">
                <div class="overview-card">
                    <div class="card-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="card-info">
                        <p class="card-label">Tổng số học kỳ</p>
                        <p class="card-value"><?php echo e($diemHocKy->groupBy('MaHocKy')->count()); ?></p>
                    </div>
                </div>

                <div class="overview-card">
                    <div class="card-icon">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div class="card-info">
                        <p class="card-label">Tổng số môn học</p>
                        <p class="card-value"><?php echo e($diemHocKy->count()); ?></p>
                    </div>
                </div>
            </div>

            <!-- Grades by Semester -->
            <?php $__currentLoopData = $diemHocKy->groupBy('MaHocKy'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maHocKy => $dsMonHoc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="semester-card">
                    <!-- Semester Header -->
                    <div class="semester-header">
                        <div class="semester-title">
                            <i class="bi bi-calendar3"></i>
                            <h3>Học kỳ <?php echo e($maHocKy); ?></h3>
                        </div>
                        <div class="semester-stats">
                            <div class="stat-item">
                                <span class="stat-label">Môn học:</span>
                                <span class="stat-value"><?php echo e($dsMonHoc->count()); ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Điểm TB:</span>
                                <span class="stat-value"><?php echo e(number_format($dsMonHoc->avg('DiemTongKet'), 2)); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Grades Table -->
                    <div class="table-wrapper">
                        <table class="grades-table">
                            <thead>
                                <tr>
                                    <th class="col-mamon">Mã Môn</th>
                                    <th class="col-tenmon">Tên Môn Học</th>
                                    <th class="col-diem">Điểm QT</th>
                                    <th class="col-diem">Điểm Thi</th>
                                    <th class="col-diem">Điểm TK</th>
                                    <th class="col-xeploai">Xếp Loại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dsMonHoc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $diemTK = $mon->DiemTongKet ?? 0;
                                        $gradeClass = '';
                                        if ($diemTK >= 8.5) $gradeClass = 'grade-excellent';
                                        elseif ($diemTK >= 7) $gradeClass = 'grade-good';
                                        elseif ($diemTK >= 5.5) $gradeClass = 'grade-pass';
                                        else $gradeClass = 'grade-fail';
                                    ?>
                                    <tr class="grade-row <?php echo e($gradeClass); ?>">
                                        <td class="col-mamon">
                                            <span class="badge-code"><?php echo e($mon->MaMonHoc); ?></span>
                                        </td>
                                        <td class="col-tenmon">
                                            <div class="mon-info">
                                                <span class="mon-name"><?php echo e($mon->TenMonHoc); ?></span>
                                            </div>
                                        </td>
                                        <td class="col-diem">
                                            <span class="diem-badge"><?php echo e($mon->DiemQT ?? '-'); ?></span>
                                        </td>
                                        <td class="col-diem">
                                            <span class="diem-badge"><?php echo e($mon->DiemThi ?? '-'); ?></span>
                                        </td>
                                        <td class="col-diem">
                                            <strong class="diem-tongket <?php echo e($gradeClass); ?>">
                                                <?php echo e($diemTK); ?>

                                            </strong>
                                        </td>
                                        <td class="col-xeploai">
                                            <span class="xeploai-badge <?php echo e(strtolower(str_replace(' ', '-', $mon->XepLoai))); ?>">
                                                <?php echo e($mon->XepLoai); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .grades-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Header Section */
    .grades-header {
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

    /* Overview Cards */
    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .overview-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .overview-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #ffffff;
        flex-shrink: 0;
    }

    .card-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .card-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    /* Empty State */
    .empty-state {
        background: #ffffff;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 1rem;
    }

    /* Semester Card */
    .semester-card {
        background: #ffffff;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .semester-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #e5e7eb;
    }

    .semester-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .semester-title i {
        font-size: 1.5rem;
        color: #667eea;
    }

    .semester-title h3 {
        font-size: 1.25rem;
        color: #1f2937;
        margin: 0;
    }

    .semester-stats {
        display: flex;
        gap: 2rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-value {
        color: #667eea;
        font-weight: 700;
        font-size: 1.125rem;
    }

    /* Table Wrapper */
    .table-wrapper {
        overflow-x: auto;
    }

    .grades-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .grades-table thead {
        background: #f3f4f6;
        border-bottom: 2px solid #e5e7eb;
    }

    .grades-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        white-space: nowrap;
    }

    .grades-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .grade-row {
        transition: all 0.2s ease;
    }

    .grade-row:hover {
        background: #fafbfc;
    }

    /* Grade Row Colors */
    .grade-row.grade-excellent {
        border-left: 4px solid #10b981;
    }

    .grade-row.grade-good {
        border-left: 4px solid #3b82f6;
    }

    .grade-row.grade-pass {
        border-left: 4px solid #f59e0b;
    }

    .grade-row.grade-fail {
        border-left: 4px solid #ef4444;
    }

    /* Column Widths */
    .col-mamon {
        width: 12%;
    }

    .col-tenmon {
        width: 40%;
    }

    .col-diem {
        width: 12%;
        text-align: center;
    }

    .col-xeploai {
        width: 15%;
        text-align: center;
    }

    /* Badge Styles */
    .badge-code {
        background: #f0f4ff;
        color: #667eea;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .mon-info {
        display: flex;
        flex-direction: column;
    }

    .mon-name {
        font-weight: 500;
        color: #1f2937;
    }

    .diem-badge {
        background: #f3f4f6;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        color: #374151;
    }

    .diem-tongket {
        font-size: 1.05rem;
    }

    .diem-tongket.grade-excellent {
        color: #10b981;
    }

    .diem-tongket.grade-good {
        color: #3b82f6;
    }

    .diem-tongket.grade-pass {
        color: #f59e0b;
    }

    .diem-tongket.grade-fail {
        color: #ef4444;
    }

    .xeploai-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .xeploai-badge.xuất-sắc {
        background: #d1fae5;
        color: #065f46;
    }

    .xeploai-badge.giỏi {
        background: #dbeafe;
        color: #0c2d6b;
    }

    .xeploai-badge.khá {
        background: #fef3c7;
        color: #78350f;
    }

    .xeploai-badge.trung-bình {
        background: #fed7aa;
        color: #7c2d12;
    }

    .xeploai-badge.yếu {
        background: #fee2e2;
        color: #7f1d1d;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .overview-cards {
            grid-template-columns: 1fr;
        }

        .semester-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .semester-stats {
            width: 100%;
            justify-content: flex-start;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        .grades-header {
            padding: 2rem 1rem;
            margin-bottom: 1rem;
        }

        .col-mamon,
        .col-tenmon,
        .col-diem,
        .col-xeploai {
            width: auto;
        }

        .grades-table th,
        .grades-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }

        .semester-title h3 {
            font-size: 1.1rem;
        }

        .stat-label {
            font-size: 0.75rem;
        }

        .stat-value {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .table-wrapper {
            font-size: 0.8rem;
        }

        .grades-table th,
        .grades-table td {
            padding: 0.5rem 0.25rem;
        }

        .diem-badge,
        .badge-code,
        .xeploai-badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/thongtin_sinhvien/academics_show.blade.php ENDPATH**/ ?>