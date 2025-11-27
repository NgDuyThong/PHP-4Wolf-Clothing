// Xử lý áp dụng mã khuyến mãi
document.addEventListener('DOMContentLoaded', function() {
    const applyPromoBtn = document.getElementById('apply-promo-btn');
    const promoCodeInput = document.getElementById('promo-code');
    const promoMessage = document.getElementById('promo-message');
    const discountRow = document.getElementById('discount-row');
    const discountAmount = document.getElementById('discount-amount');
    const finalTotal = document.getElementById('final-total');
    const promotionIdInput = document.getElementById('promotion-id');
    const appliedPromoCode = document.getElementById('applied-promo-code');
    const removePromoBtn = document.getElementById('remove-promo-btn');
    
    let currentPromotion = null;
    
    // Xử lý nút hủy mã giảm giá
    if (removePromoBtn) {
        removePromoBtn.addEventListener('click', function() {
            removeDiscount();
            promoCodeInput.value = '';
            showMessage('Đã hủy mã khuyến mãi', 'error');
        });
    }
    
    if (applyPromoBtn) {
        // Xử lý khi nhấn Enter
        if (promoCodeInput) {
            promoCodeInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyPromoBtn.click();
                }
            });
        }

        applyPromoBtn.addEventListener('click', function() {
            const code = promoCodeInput.value.trim();
            
            if (!code) {
                showMessage('Vui lòng nhập mã khuyến mãi!', 'error');
                return;
            }
            
            // Lấy tổng tiền gốc (không bao gồm giảm giá cũ)
            const orderTotalConst = document.getElementById('total-order-const');
            if (!orderTotalConst) {
                showMessage('Không tìm thấy thông tin đơn hàng!', 'error');
                return;
            }
            
            const orderTotal = parseFloat(orderTotalConst.value);
            
            // Disable button while processing
            applyPromoBtn.disabled = true;
            applyPromoBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang kiểm tra...';
            
            // Call API to validate promotion
            fetch('/api/promotions/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    code: code,
                    order_total: orderTotal
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentPromotion = data.promotion;
                    applyDiscount(data.promotion.discount, orderTotal);
                    showMessage(data.message, 'success');
                    
                    // Save promotion ID to hidden input
                    if (promotionIdInput) {
                        promotionIdInput.value = data.promotion.id;
                    }
                } else {
                    removeDiscount();
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Có lỗi xảy ra, vui lòng thử lại!', 'error');
            })
            .finally(() => {
                applyPromoBtn.disabled = false;
                applyPromoBtn.innerHTML = '<i class="fa fa-check"></i> Áp dụng';
            });
        });
    }
    
    function applyDiscount(discount, orderTotal) {
        // Show discount row
        if (discountRow) {
            discountRow.style.display = 'block';
            discountAmount.textContent = '-' + formatMoney(discount) + 'đ';
        }
        
        // Show applied promo code
        if (appliedPromoCode && promoCodeInput) {
            appliedPromoCode.textContent = promoCodeInput.value.toUpperCase();
        }
        
        // Get shipping fee
        const feeElement = document.getElementById('fee');
        const feeText = feeElement ? feeElement.textContent : '0';
        const fee = parseFloat(feeText.replace(/[^\d]/g, '')) || 0;
        
        // Calculate new total: orderTotal (sản phẩm gốc) + fee - discount
        const newTotal = orderTotal + fee - discount;
        
        // Update total order display
        const totalOrderElement = document.getElementById('total-order');
        if (totalOrderElement) {
            totalOrderElement.textContent = formatMoney(newTotal) + 'đ';
        }
        
        // Update hidden input for form submission (tổng cuối cùng)
        const totalInput = document.getElementById('total-order-input');
        if (totalInput) {
            totalInput.value = newTotal;
        }
        
        console.log('Applied discount:', {
            orderTotal: orderTotal,
            fee: fee,
            discount: discount,
            finalTotal: newTotal
        });
    }
    
    function removeDiscount() {
        if (discountRow) {
            discountRow.style.display = 'none';
        }
        
        // Get original order total
        const orderTotal = parseFloat(document.getElementById('total-order-const').value);
        
        // Get shipping fee
        const feeElement = document.getElementById('fee');
        const feeText = feeElement ? feeElement.textContent : '0';
        const fee = parseFloat(feeText.replace(/[^\d]/g, '')) || 0;
        
        // Calculate total without discount
        const newTotal = orderTotal + fee;
        
        // Update total order display
        const totalOrderElement = document.getElementById('total-order');
        if (totalOrderElement) {
            totalOrderElement.textContent = formatMoney(newTotal) + 'đ';
        }
        
        const totalInput = document.getElementById('total-order-input');
        if (totalInput) {
            totalInput.value = newTotal;
        }
        
        if (promotionIdInput) {
            promotionIdInput.value = '';
        }
        
        currentPromotion = null;
    }
    
    function showMessage(message, type) {
        if (promoMessage) {
            promoMessage.textContent = message;
            promoMessage.className = 'promo-message ' + (type === 'success' ? 'text-success' : 'text-danger');
            promoMessage.style.display = 'block';
            
            setTimeout(() => {
                promoMessage.style.display = 'none';
            }, 5000);
        }
    }
    
    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount);
    }
});
