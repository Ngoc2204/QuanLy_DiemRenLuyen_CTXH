@extends('layouts.sinhvien')

@section('content')

<style>
    .onboarding-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .onboarding-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 3rem 2rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 800px;
        width: 100%;
        animation: slideUp 0.6s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .onboarding-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .onboarding-header i {
        font-size: 3.5rem;
        color: #667eea;
        margin-bottom: 1rem;
        display: block;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .onboarding-header h1 {
        font-size: 2rem;
        color: #2c3e50;
        margin: 0 0 0.5rem 0;
        font-weight: 700;
    }

    .onboarding-header p {
        font-size: 1.1rem;
        color: #7f8c8d;
        margin: 0.5rem 0 0 0;
    }

    .description-box {
        background: #f0f4ff;
        border-left: 4px solid #667eea;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .description-box p {
        margin: 0;
        color: #555;
        line-height: 1.6;
    }

    .description-box strong {
        color: #667eea;
    }

    .interests-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .interest-item {
        position: relative;
    }

    .interest-checkbox {
        display: none;
    }

    .interest-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1rem;
        background: #f8f9fa;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 140px;
        text-align: center;
    }

    .interest-label i {
        font-size: 2.5rem;
        color: #667eea;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .interest-label span {
        font-weight: 500;
        color: #2c3e50;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .interest-checkbox:checked + .interest-label {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }

    .interest-checkbox:checked + .interest-label i {
        color: #fff;
        transform: scale(1.1);
    }

    .interest-checkbox:checked + .interest-label span {
        color: #fff;
        font-weight: 600;
    }

    .interest-label:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }

    .selection-count {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 1rem;
        background: #e8f0fe;
        border-radius: 8px;
        margin-bottom: 2rem;
        text-align: center;
    }

    .selection-count i {
        font-size: 1.5rem;
        color: #667eea;
    }

    .selection-count span {
        color: #667eea;
        font-weight: 600;
        font-size: 1rem;
    }

    .selection-count span.number {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-skip {
        background: transparent;
        color: #667eea;
        border: 2px solid #667eea;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .btn-skip:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .help-text {
        text-align: center;
        color: #999;
        font-size: 0.9rem;
        margin-top: 1.5rem;
    }

    .error-message {
        color: #e74c3c;
        font-weight: 600;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #ffe6e6;
        border-left: 4px solid #e74c3c;
        border-radius: 4px;
        display: block;
    }

    @media (max-width: 600px) {
        .onboarding-card {
            padding: 2rem 1.5rem;
        }

        .onboarding-header h1 {
            font-size: 1.5rem;
        }

        .onboarding-header i {
            font-size: 2.5rem;
        }

        .interests-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .interest-label {
            padding: 1rem;
            min-height: 120px;
        }

        .interest-label i {
            font-size: 2rem;
        }

        .interest-label span {
            font-size: 0.85rem;
        }

        .button-group {
            flex-direction: column;
        }

        .btn-submit,
        .btn-skip {
            width: 100%;
        }
    }
</style>


    <div class="onboarding-card">
        <!-- Header -->
        <div class="onboarding-header">
            <i class="bi bi-star-fill"></i>
            <h1>Chào mừng, {{ $studentName }}!</h1>
            <p>Hãy cho chúng tôi biết bạn quan tâm đến điều gì</p>
        </div>

        <!-- Description -->
        <div class="description-box">
            <p>
                <strong>🎯 Tại sao điều này quan trọng?</strong>
                <br>Bạn cần chọn sở thích để hệ thống có thể gợi ý hoạt động DRL/CTXH <strong>phù hợp nhất</strong> với bạn.
                <br>Sở thích này sẽ giúp chúng tôi hiểu rõ hơn về bạn và cung cấp những gợi ý chất lượng cao.
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('sinhvien.onboarding.interests.store') }}" method="POST" id="interestsForm">
            @csrf

            {{-- Error Messages --}}
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <span class="error-message">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $error }}
                    </span>
                @endforeach
            @endif

            <!-- Interests Grid -->
            <div class="interests-grid">
                @forelse($interests as $interest)
                    <div class="interest-item">
                        <input type="checkbox" 
                               id="interest_{{ $interest->InterestID }}" 
                               name="interests[]" 
                               value="{{ $interest->InterestID }}"
                               class="interest-checkbox"
                               data-interest-name="{{ $interest->InterestName }}">
                        <label for="interest_{{ $interest->InterestID }}" 
                               class="interest-label"
                               title="{{ $interest->InterestName }}">
                            @if($interest->Icon)
                                <i class="{{ $interest->Icon }}"></i>
                            @else
                                <i class="bi bi-heart-fill"></i>
                            @endif
                            <span>{{ $interest->InterestName }}</span>
                        </label>
                    </div>
                @empty
                    <p class="text-muted text-center w-100">Không có sở thích nào để chọn</p>
                @endforelse
            </div>

            <!-- Selection Counter -->
            <div class="selection-count">
                <i class="bi bi-check-circle-fill"></i>
                <span>Bạn đã chọn <span class="number" id="selectionCount">0</span> sở thích</span>
            </div>

            <!-- Buttons -->
            <div class="button-group">
                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                    <i class="bi bi-check-lg"></i>
                    Xác nhận
                </button>
            </div>

            <p class="help-text">
                ℹ️ Bạn cần chọn <strong>ít nhất 1 sở thích</strong> để tiếp tục
            </p>
        </form>
    </div>

<script>
    // Real-time interest count
    const checkboxes = document.querySelectorAll('.interest-checkbox');
    const selectionCountEl = document.getElementById('selectionCount');
    const submitBtn = document.getElementById('submitBtn');

    function updateSelectionCount() {
        const checked = document.querySelectorAll('.interest-checkbox:checked').length;
        selectionCountEl.textContent = checked;
        
        // Enable/disable submit button
        submitBtn.disabled = checked === 0;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionCount);
    });

    // Form submission
    document.getElementById('interestsForm').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.interest-checkbox:checked').length;
        
        if (checked === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất 1 sở thích!');
            return false;
        }
    });

    // Show selected interests as toast/notification
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        const checked = Array.from(document.querySelectorAll('.interest-checkbox:checked'))
            .map(cb => cb.dataset.interestName);
        
        if (checked.length > 0) {
            console.log('Selected interests:', checked);
        }
    });
</script>

@endsection
