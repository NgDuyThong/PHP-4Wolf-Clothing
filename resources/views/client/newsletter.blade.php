@extends('layouts.client')
@section('content-client')
<style>
.newsletter-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    border-radius: 15px;
    margin-bottom: 50px;
}
.newsletter-form {
    background: white;
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.benefit-card {
    text-align: center;
    padding: 30px;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    height: 100%;
    transition: all 0.3s ease;
}
.benefit-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-5px);
}
.benefit-icon {
    font-size: 48px;
    color: #667eea;
    margin-bottom: 20px;
}
</style>

<div class="container mt_50 mb_50">
    <div class="newsletter-hero text-center">
        <div class="container">
            <i class="fa fa-envelope-o fa-5x mb-4"></i>
            <h1>Đăng Ký Nhận Bản Tin</h1>
            <p class="lead">Cập nhật tin tức mới nhất về thời trang và ưu đãi đặc biệt</p>
        </div>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-6">
            <div class="newsletter-form">
                <h3 class="text-center mb-4">Đăng Ký Ngay</h3>
                
                <form id="newsletter-form">
                    <div class="form-group">
                        <label>Địa chỉ Email:</label>
                        <input type="email" id="email" class="form-control form-control-lg" 
                               placeholder="your@email.com" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="agree" required>
                        <label class="form-check-label" for="agree">
                            Tôi đồng ý nhận email về sản phẩm mới và ưu đãi đặc biệt
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fa fa-paper-plane"></i> Đăng Ký
                    </button>

                    <div id="message" class="mt-3" style="display: none;"></div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <h3 class="text-center">Lợi Ích Khi Đăng Ký</h3>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fa fa-gift"></i>
                </div>
                <h5>Ưu Đãi Độc Quyền</h5>
                <p>Nhận mã giảm giá và ưu đãi đặc biệt chỉ dành cho thành viên</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fa fa-star"></i>
                </div>
                <h5>Sản Phẩm Mới</h5>
                <p>Cập nhật sớm nhất về các sản phẩm và bộ sưu tập mới</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fa fa-tags"></i>
                </div>
                <h5>Flash Sale</h5>
                <p>Thông báo về các chương trình giảm giá đặc biệt</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="fa fa-lightbulb-o"></i>
                </div>
                <h5>Xu Hướng Thời Trang</h5>
                <p>Cẩm nang phối đồ và xu hướng thời trang mới nhất</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form');
    const emailInput = document.getElementById('email');
    const messageDiv = document.getElementById('message');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';

        fetch('/api/newsletter/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                email: emailInput.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                form.reset();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            showMessage('Có lỗi xảy ra, vui lòng thử lại!', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Đăng Ký';
        });
    });

    function showMessage(message, type) {
        messageDiv.innerHTML = message;
        messageDiv.className = 'alert alert-' + (type === 'success' ? 'success' : 'danger');
        messageDiv.style.display = 'block';

        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
});
</script>
@endsection
