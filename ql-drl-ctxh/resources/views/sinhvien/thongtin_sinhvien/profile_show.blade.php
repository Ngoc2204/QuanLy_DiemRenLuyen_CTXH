@extends('layouts.sinhvien') 

@section('content')    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12 ">
                
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success-custom alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="alert-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="flex-grow-1">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                <!-- Main Profile Card -->
                <div class="profile-card">
                    <!-- Header Section with Cover -->
                    <div class="profile-header">
                        <div class="cover-gradient"></div>
                        <div class="profile-header-content">
                            <div class="avatar-wrapper">
                                <div class="avatar-large">
                                    @php
                                        $avatarUrl = null;
                                        if (!empty(Auth::user()->Avatar) && file_exists(storage_path('app/public/' . Auth::user()->Avatar))) {
                                            $avatarUrl = asset('storage/' . Auth::user()->Avatar);
                                        }
                                    @endphp

                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="Avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                                    @else
                                        <span>{{ strtoupper(substr($student->HoTen, 0, 2)) }}</span>
                                    @endif
                                    <div class="avatar-status"></div>
                                </div>
                            </div>
                            <div class="profile-title">
                                <h1 class="student-name">{{ $student->HoTen }}</h1>
                                <p class="student-id">
                                    <i class="bi bi-person-badge"></i>
                                    {{ $student->MSSV }}
                                </p>
                            </div>
                            <div class="header-actions">
                                <a href="{{ route('sinhvien.profile.edit') }}" class="btn-edit">
                                    <i class="bi bi-pencil"></i>
                                    <span>Chỉnh sửa hồ sơ</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="profile-content">
                        <div class="row g-4">
                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <div class="info-section">
                                    <h3 class="section-title">
                                        <i class="bi bi-person-circle"></i>
                                        Thông tin cá nhân
                                    </h3>
                                    
                                    <div class="info-card">
                                        <div class="info-item">
                                            <div class="info-icon bg-blue">
                                                <i class="bi bi-envelope"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Email</span>
                                                <span class="info-text">{{ $student->Email }}</span>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon bg-green">
                                                <i class="bi bi-telephone"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Số điện thoại</span>
                                                <span class="info-text">{{ $student->SDT }}</span>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon bg-purple">
                                                <i class="bi bi-calendar-heart"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Ngày sinh</span>
                                                <span class="info-text">{{ \Carbon\Carbon::parse($student->NgaySinh)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <div class="info-section">
                                    <h3 class="section-title">
                                        <i class="bi bi-mortarboard"></i>
                                        Thông tin học tập
                                    </h3>
                                    
                                    <div class="info-card">
                                        <div class="info-item">
                                            <div class="info-icon bg-orange">
                                                <i class="bi bi-building"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Khoa</span>
                                                <span class="info-text">{{ $student->lop->khoa->TenKhoa }}</span>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon bg-pink">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Lớp</span>
                                                <span class="info-text">{{ $student->lop->TenLop }}</span>
                                            </div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-icon bg-teal">
                                                <i class="bi bi-calendar-check"></i>
                                            </div>
                                            <div class="info-content">
                                                <span class="info-label">Năm nhập học</span>
                                                <span class="info-text">{{ $student->NamNhapHoc }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Full Width Section -->
                            <div class="col-12">
                                <div class="info-section">
                                    <h3 class="section-title">
                                        <i class="bi bi-star"></i>
                                        Sở thích & Đam mê
                                    </h3>
                                    
                                    @php
                                        $studentInterests = \App\Models\StudentInterest::where('MSSV', $student->MSSV)
                                            ->with('interest')
                                            ->get();
                                    @endphp

                                    @if($studentInterests->count() > 0)
                                        <div class="interests-display">
                                            @foreach($studentInterests as $si)
                                                <div class="interest-badge">
                                                    @if($si->interest && $si->interest->Icon)
                                                        <i class="{{ $si->interest->Icon }}"></i>
                                                    @else
                                                        <i class="bi bi-heart-fill"></i>
                                                    @endif
                                                    <span>{{ $si->interest->InterestName ?? 'N/A' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="hobby-card">
                                            <div class="hobby-icon">
                                                <i class="bi bi-emoji-smile"></i>
                                            </div>
                                            <p class="hobby-text">Chưa chọn sở thích</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<style>
    .profile-container {
        position: relative;
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem 0;
    }

    .gradient-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        z-index: 0;
    }

    .container {
        position: relative;
        z-index: 1;
    }

    /* Alert Custom */
    .alert-success-custom {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        border: none;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        color: #065f46;
        box-shadow: 0 10px 30px rgba(132, 250, 176, 0.3);
        animation: slideDown 0.5s ease;
    }

    .alert-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.25rem;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Profile Card */
    .profile-card {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Profile Header */
    .profile-header {
        position: relative;
        padding: 3rem 2rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        overflow: hidden;
    }

    .cover-gradient {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,197.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.5;
    }

    .profile-header-content {
        position: relative;
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    /* Avatar */
    .avatar-wrapper {
        flex-shrink: 0;
    }

    .avatar-large {
        position: relative;
        width: 140px;
        height: 140px;
        background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        color: #ffffff;
        border: 6px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .avatar-large:hover {
        transform: scale(1.05);
    }

    .avatar-status {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        background: #10b981;
        border: 4px solid #ffffff;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    /* Profile Title */
    .profile-title {
        flex: 1;
        min-width: 200px;
    }

    .student-name {
        font-size: 2.5rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 0.5rem 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .student-id {
        font-size: 1.125rem;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Edit Button */
    .header-actions {
        margin-left: auto;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 2rem;
        background: rgba(255, 255, 255, 0.95);
        color: #667eea;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-edit:hover {
        background: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        color: #5a67d8;
    }

    .btn-edit i {
        font-size: 1.125rem;
    }

    /* Profile Content */
    .profile-content {
        padding: 2.5rem;
    }

    /* Info Section */
    .info-section {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: #667eea;
        font-size: 1.5rem;
    }

    /* Info Card */
    .info-card {
        background: #f9fafb;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #ffffff;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .info-item:hover {
        transform: translateX(8px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #ffffff;
        flex-shrink: 0;
    }

    .bg-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-green { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); }
    .bg-purple { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
    .bg-orange { background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%); }
    .bg-pink { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }
    .bg-teal { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        flex: 1;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-text {
        font-size: 1.0625rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* Hobby Card */
    .hobby-card {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border-radius: 16px;
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 8px 24px rgba(252, 182, 159, 0.3);
    }

    .hobby-icon {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #ff6a88;
        flex-shrink: 0;
    }

    .hobby-text {
        font-size: 1.125rem;
        color: #78350f;
        margin: 0;
        line-height: 1.7;
    }

    /* Interests Display */
    .interests-display {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
        border-radius: 16px;
        border: 2px solid rgba(102, 126, 234, 0.2);
    }

    .interest-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1.25rem 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
    }

    .interest-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .interest-badge i {
        font-size: 1.75rem;
        color: white;
    }


    /* Responsive */
    @media (max-width: 992px) {
        .profile-header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-actions {
            margin-left: 0;
            width: 100%;
        }

        .btn-edit {
            width: 100%;
            justify-content: center;
        }

        .student-name {
            font-size: 2rem;
        }

        .student-id {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .profile-content {
            padding: 1.5rem;
        }

        .avatar-large {
            width: 120px;
            height: 120px;
            font-size: 2.5rem;
        }

        .student-name {
            font-size: 1.75rem;
        }

        .info-item {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }

        .hobby-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endsection