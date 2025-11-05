
<?php $__env->startSection('title', 'Lịch Học Tuần'); ?>

<?php $__env->startPush('styles'); ?>
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

    /* Header */
    .schedule-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 2rem;
        border-radius: 24px 24px 0 0;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .schedule-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .schedule-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .week-info {
        font-size: 1.1rem;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    /* Navigation */
    .week-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        background: white;
        border-bottom: 2px solid #e2e8f0;
    }

    .nav-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #475569;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .nav-btn:hover {
        border-color: var(--primary);
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    .today-btn {
        background: linear-gradient(135deg, var(--success), #34d399);
        color: white;
        border: none;
    }

    .today-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, var(--success), #34d399);
    }

    /* Schedule Grid */
    .schedule-container {
        background: white;
        border-radius: 0 0 24px 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    .schedule-grid {
        display: grid;
        /* 1 cột cho thời gian (100px) và 7 cột cho các ngày (1fr) */
        grid-template-columns: 100px repeat(7, 1fr);
        background: white;
        /* 4 hàng: 1 cho header, 3 cho các ca */
        grid-template-rows: auto auto auto auto;
    }

    /* Time Column */
    .time-col {
        background: #f8fafc;
        border-right: 2px solid #e2e8f0;
        /* Biến time-col thành 1 phần của grid cha */
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
        gap: 0.25rem;
        background: #f8fafc;
        border-right: 2px solid #e2e8f0;
    }

    .time-slot.header {
        background: linear-gradient(135deg, #334155, #475569);
        color: white;
        font-size: 0.9rem;
        font-weight: 800;
        min-height: auto;
    }

    .time-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }

    /* Day Columns */
    .day-col {
        border-right: 1px solid #e2e8f0;
        /* Biến day-col thành 1 phần của grid cha */
        display: contents;
    }

    .day-col:last-child {
        border-right: none;
    }

    .day-header {
        padding: 1.5rem 1rem;
        text-align: center;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 2px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.25rem;
    }

    .day-header.today {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-bottom-color: var(--primary);
    }

    .day-name {
        font-weight: 800;
        font-size: 1rem;
        color: #1e293b;
    }

    .day-date {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
    }

    .today-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--success), #34d399);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-top: 0.25rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        50% {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
    }

    /* Class Cells */
    .class-cell {
        padding: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        position: relative;
        transition: all 0.3s ease;
        overflow-y: auto;
        overflow-x: hidden;
        min-height: 120px;
    }

    .class-cell:hover {
        background: #fafbfc;
    }

    /* Class Card */
    .class-card {
        background: white;
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border-left: 4px solid;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: visible;
        width: 100%;
        box-sizing: border-box;
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
        background: white;
        border-radius: 0 0 6px 6px;
    }

    .btn-scan {
        flex: 1;
        padding: 0.5rem;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .btn-scan.check-in {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .btn-scan.check-in:hover {
        background: var(--success);
        color: white;
    }

    .btn-scan.check-out {
        background: rgba(245, 158, 11, 0.1);
        color: var(--online);
        border-left: 1px solid #f1f5f9;
    }

    .btn-scan.check-out:hover {
        background: var(--online);
        color: white;
    }

    .class-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.05;
        transition: opacity 0.3s ease;
    }

    .class-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .class-card:hover::before {
        opacity: 0.1;
    }

    /* Class Types */
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

    /* Class Info */
    .class-type-badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .class-card.theory .class-type-badge {
        background: rgba(16, 185, 129, 0.15);
        color: var(--theory);
    }

    .class-card.practice .class-type-badge {
        background: rgba(59, 130, 246, 0.15);
        color: var(--practice);
    }

    .class-card.online .class-type-badge {
        background: rgba(245, 158, 11, 0.15);
        color: var(--online);
    }

    .class-card.suspended .class-type-badge {
        background: rgba(239, 68, 68, 0.15);
        color: var(--suspended);
    }

    .class-name {
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        line-height: 1.4;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .class-code {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .class-time {
        font-size: 0.8rem;
        color: #475569;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .class-time i {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .class-location {
        font-size: 0.8rem;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin-bottom: 0.25rem;
    }

    .class-location i {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .class-teacher {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .class-teacher i {
        font-size: 0.7rem;
    }

    /* Legend */
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
        gap: 0.5rem;
        font-size: 0.85rem;
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
        background: rgba(16, 185, 129, 0.2);
        border-color: var(--theory);
    }

    .legend-color.practice {
        background: rgba(59, 130, 246, 0.2);
        border-color: var(--practice);
    }

    .legend-color.online {
        background: rgba(245, 158, 11, 0.2);
        border-color: var(--online);
    }

    .legend-color.suspended {
        background: rgba(239, 68, 68, 0.2);
        border-color: var(--suspended);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .schedule-grid {
            /* Cập nhật cột time cho màn hình nhỏ hơn */
            grid-template-columns: 80px repeat(7, 1fr);
        }

        .class-name {
            font-size: 0.85rem;
        }

        .class-time,
        .class-location {
            font-size: 0.75rem;
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
            /* Thay đổi về flex column cho mobile */
            display: flex;
            flex-direction: column;
        }

        .time-col {
            /* Ẩn cột thời gian dọc trên mobile */
            display: none;
        }

        .day-col {
            /* Tắt display: contents để .day-col hoạt động như 1 flex item */
            display: block; 
            border-right: none;
            border-bottom: 2px solid #e2e8f0;
        }

        .day-header {
            /* Đảm bảo day-header hiển thị bình thường */
            display: flex;
        }

        .class-cell {
            /* Đảm bảo class-cell hiển thị bình thường */
            display: block;
            min-height: auto;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="schedule-wrapper">
    <div class="schedule-container">
        <!-- Header -->
        <div class="schedule-header">
            <h1><i class="fas fa-calendar-alt me-2"></i>Lịch Hoạt Động</h1>
            <div class="week-info">Tuần: <?php echo e($startOfWeek->format('d/m')); ?> - <?php echo e($endOfWeek->format('d/m/Y')); ?></div>
        </div>

        <!-- Navigation -->
        <div class="week-nav">
            <a href="<?php echo e(route('sinhvien.lich_tuan')); ?>?date=<?php echo e($prevWeek->toDateString()); ?>" class="nav-btn">
                <i class="fas fa-chevron-left"></i>
                Tuần trước
            </a>
            <a href="<?php echo e(route('sinhvien.lich_tuan')); ?>" class="nav-btn today-btn">
                <i class="fas fa-home"></i>
                Tuần hiện tại
            </a>
            <a href="<?php echo e(route('sinhvien.lich_tuan')); ?>?date=<?php echo e($nextWeek->toDateString()); ?>" class="nav-btn">
                Tuần sau
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Schedule Grid -->
        <div class="schedule-grid">
            <!-- Time Column -->
            <div class="time-col">
                
                <div class="time-slot header" style="grid-row: 1; grid-column: 1;">
                    Ca học
                </div>
                <div class="time-slot" style="grid-row: 2; grid-column: 1;">
                    <div>Sáng</div>
                    <div class="time-label">7:00 - 11:55</div>
                </div>
                <div class="time-slot" style="grid-row: 3; grid-column: 1;">
                    <div>Chiều</div>
                    <div class="time-label">12:30 - 17:25</div>
                </div>
                <div class="time-slot" style="grid-row: 4; grid-column: 1;">
                    <div>Tối</div>
                    <div class="time-label">18:00 - 21:45</div>
                </div>
            </div>

            <?php $__currentLoopData = $daysOfWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <div class="day-col">
                <div class="day-header <?php echo e($dayData['date']->isToday() ? 'today' : ''); ?>" style="grid-row: 1; grid-column: <?php echo e($loop->index + 2); ?>;">
                    <div class="day-name"><?php echo e($dayData['date']->isoFormat('dddd')); ?></div>
                    <div class="day-date"><?php echo e($dayData['date']->format('d/m/Y')); ?></div>
                    <?php if($dayData['date']->isToday()): ?>
                    <span class="today-badge">Hôm nay</span>
                    <?php endif; ?>
                </div>

                <?php
                $morning = [];
                $afternoon = [];
                $evening = [];
                foreach($dayData['activities'] as $item) {
                    $activity = $item['hoatdong'];
                    $hour = $activity->ThoiGianBatDau->format('H');
                    if ($hour < 12) {
                        $morning[]=$item;
                    } elseif ($hour < 18) {
                        $afternoon[]=$item;
                    } else {
                        $evening[]=$item;
                    }
                }
                ?>

                
                <div class="class-cell" style="grid-row: 2; grid-column: <?php echo e($loop->index + 2); ?>;">
                    <?php $__currentLoopData = $morning; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($activity = $item['hoatdong']); ?>
                    <div class="class-card <?php echo e($item['type'] == 'DRL' ? 'theory' : 'practice'); ?> <?php echo e($dayData['date']->isToday() && $item['trang_thai'] == 'Đã duyệt' && $activity->CheckInToken ? 'has-actions' : ''); ?>">
                        <span class="class-type-badge"><?php echo e($item['type']); ?></span>
                        <div class="class-name"><?php echo e($activity->TenHoatDong); ?></div>
                        <div class="class-code"><?php echo e($item['trang_thai'] ?? 'Đang diễn ra'); ?></div>
                        <div class="class-time">
                            <i class="fas fa-clock"></i>
                            <?php echo e($activity->ThoiGianBatDau->format('H:i')); ?> - <?php echo e($activity->ThoiGianKetThuc->format('H:i')); ?>

                        </div>
                        <div class="class-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo e($activity->DiaDiem ?? 'Chưa xác định'); ?>

                        </div>
                        <?php if($dayData['date']->isToday() && $item['trang_thai'] == 'Đã duyệt' && $activity->CheckInToken): ?>
                        <div class="class-actions">
                            <a href="<?php echo e(route('sinhvien.scan', $activity->CheckInToken ?? '#')); ?>" class="btn-scan check-in">Check In</a>
                            <a href="<?php echo e(route('sinhvien.scan', $activity->CheckOutToken ?? '#')); ?>" class="btn-scan check-out">Check Out</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="class-cell" style="grid-row: 3; grid-column: <?php echo e($loop->index + 2); ?>;">
                    <?php $__currentLoopData = $afternoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($activity = $item['hoatdong']); ?>
                    <div class="class-card <?php echo e($item['type'] == 'DRL' ? 'theory' : 'practice'); ?> <?php echo e($dayData['date']->isToday() && $item['trang_thai'] == 'Đã duyệt' && $activity->CheckInToken ? 'has-actions' : ''); ?>">
                        <span class="class-type-badge"><?php echo e($item['type']); ?></span>
                        <div class="class-name"><?php echo e($activity->TenHoatDong); ?></div>
                        <div class="class-code"><?php echo e($item['trang_thai'] ?? 'Đang diễn ra'); ?></div>
                        <div class="class-time">
                            <i class="fas fa-clock"></i>
                            <?php echo e($activity->ThoiGianBatDau->format('H:i')); ?> - <?php echo e($activity->ThoiGianKetThuc->format('H:i')); ?>

                        </div>
                        <div class="class-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo e($activity->DiaDiem ?? 'Chưa xác định'); ?>

                        </div>
                        <?php if($dayData['date']->isToday() && $item['trang_thai'] == 'Đã duyệt' && $activity->CheckInToken): ?>
                        <div class="class-actions">
                            <a href="<?php echo e(route('sinhvien.scan', $activity->CheckInToken ?? '#')); ?>" class="btn-scan check-in">Check In</a>
                            <a href="<?php echo e(route('sinhvien.scan', $activity->CheckOutToken ?? '#')); ?>" class="btn-scan check-out">Check Out</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="class-cell" style="grid-row: 4; grid-column: <?php echo e($loop->index + 2); ?>;">
                    <?php $__currentLoopData = $evening; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($activity = $item['hoatdong']); ?>
                    <div class="class-card <?php echo e($item['type'] == 'DRL' ? 'theory' : 'practice'); ?> <?php echo e($dayData['date']->isToday() && $item['trang_thai'] == 'Đã duyệt' && $activity->CheckInToken ? 'has-actions' : ''); ?>">
                        <span class="class-type-badge"><?php echo e($item['type']); ?></span>
                        <div class="class-name"><?php echo e($activity->TenHoatDong); ?></div>
                        <div class="class-code"><?php echo e($item['trang_thai'] ?? 'Đang diễn ra'); ?></div>
                        <div class="class-time">
                            <i class="fas fa-clock"></i>
                            <?php echo e($activity->ThoiGianBatDau->format('H:i')); ?> - <?php echo e($activity->ThoiGianKetThuc->format('H:i')); ?>

                        </div>
                        <div class="class-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo e($activity->DiaDiem ?? 'Chưa xác định'); ?>

                        </div>
                        <?php if($dayData['date']->isToday() && $item['trang_thai'] == 'Đã duyệt' && $activity->CheckInToken): ?>
                        <div class="class-actions">
                            <a href="<?php echo e(route('sinhvien.scan', $activity->CheckInToken ?? '#')); ?>" class="btn-scan check-in">Check In</a>
                            <a href="<?php echo e(route('sinhvien.scan', $activity->CheckOutToken ?? '#')); ?>" class="btn-scan check-out">Check Out</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Legend -->
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
            <span>Hoạt động khác</span>
        </div>
        <div class="legend-item">
            <div class="legend-color suspended"></div>
            <span>Đã hủy</span>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.sinhvien', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\HT_QuanLy_DRL_CTXH\ql-drl-ctxh\resources\views/sinhvien/lich_tuan/index.blade.php ENDPATH**/ ?>