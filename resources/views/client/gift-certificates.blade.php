@extends('layouts.client')
@section('content-client')
<style>
.gift-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 40px;
    color: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.check-form {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.check-form #check-btn {
    background: white !important;
    border: 2px solid #333 !important;
    color: #000 !important;
}
.check-form #check-btn:hover {
    background: #333 !important;
    color: white !important;
}
.check-form .btn-outline-primary {
    background: white !important;
    border: 2px solid #333 !important;
    color: #000 !important;
}
.check-form .btn-outline-primary:hover {
    background: #333 !important;
    color: white !important;
}
.result-box {
    margin-top: 20px;
    padding: 20px;
    border-radius: 8px;
    display: none;
}
.result-box.success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}
.result-box.error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
</style>

<div class="container mt_50 mb_50">
    <div class="row">
        <div class="col-12">
            <div class="heading-part mb_30 text-center">
                <h2 class="main_title">Giấy Chứng Nhận Quà Tặng</h2>
                <p class="sub-title">Kiểm tra và sử dụng giấy chứng nhận quà tặng của bạn</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="gift-card mb-4">
                <div class="text-center">
                    <i class="fa fa-gift fa-5x mb-3"></i>
                    <h3>Giấy Chứng Nhận Quà Tặng</h3>
                    <p>Tặng người thân yêu một món quà đặc biệt</p>
                </div>
            </div>

            <div class="check-form">
                <h4 class="mb-4">Kiểm Tra Mã Giấy Chứng Nhận</h4>
                
                <div class="form-group">
                    <label>Nhập mã giấy chứng nhận:</label>
                    <div class="input-group">
                        <input type="text" id="certificate-code" class="form-control" placeholder="VD: GIFT-XXXX-XXXX">
                        <div class="input-group-append">
                            <button type="button" id="check-btn" class="btn btn-primary">
                                <i class="fa fa-search"></i> Kiểm tra
                            </button>
                        </div>
                    </div>
                </div>

                <div id="result-box" class="result-box"></div>

                <div class="mt-4">
                    <h5>Hướng dẫn sử dụng:</h5>
                    <ul>
                        <li>Nhập mã giấy chứng nhận vào ô trên</li>
                        <li>Click "Kiểm tra" để xác minh mã</li>
                        <li>Nếu mã hợp lệ, bạn có thể sử dụng khi thanh toán</li>
                        <li>Mỗi mã chỉ được sử dụng một lần</li>
                    </ul>
                </div>

                @auth
                <div class="mt-4 text-center">
                    <a href="{{ route('user.my-gift-certificates') }}" class="btn btn-outline-primary">
                        <i class="fa fa-list"></i> Xem giấy chứng nhận của tôi
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkBtn = document.getElementById('check-btn');
    const codeInput = document.getElementById('certificate-code');
    const resultBox = document.getElementById('result-box');

    // Xử lý khi nhấn Enter
    codeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            checkBtn.click();
        }
    });

    checkBtn.addEventListener('click', function() {
        const code = codeInput.value.trim();
        
        if (!code) {
            showResult('Vui lòng nhập mã giấy chứng nhận!', 'error');
            return;
        }

        checkBtn.disabled = true;
        checkBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang kiểm tra...';

        fetch('/api/gift-certificates/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let message = `<strong>Mã hợp lệ!</strong><br>`;
                message += `Giá trị: <strong>${formatMoney(data.certificate.value)}đ</strong><br>`;
                if (data.certificate.expires_at) {
                    message += `Hết hạn: ${data.certificate.expires_at}`;
                }
                showResult(message, 'success');
            } else {
                showResult(data.message, 'error');
            }
        })
        .catch(error => {
            showResult('Có lỗi xảy ra, vui lòng thử lại!', 'error');
        })
        .finally(() => {
            checkBtn.disabled = false;
            checkBtn.innerHTML = '<i class="fa fa-search"></i> Kiểm tra';
        });
    });

    function showResult(message, type) {
        resultBox.innerHTML = message;
        resultBox.className = 'result-box ' + type;
        resultBox.style.display = 'block';
    }

    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount);
    }
});
</script>
@endsection
