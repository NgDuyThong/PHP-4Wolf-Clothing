$(document).ready(function(){
    getAddress()
    $.validator.addMethod("validPhone", function (value, element) {
        return this.optional(element) || /^(\+84|0)[3|5|7|8|9][0-9]{8}$/.test(value);
    }, "Số điện thoại không hợp lệ");

    $("#form__js").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            name: {
                required: true,
                minlength: 1,
                maxlength: 30
            },
            apartment_number: {
                required: true
            },
            city: {
                required: true
            },
            district: {
                required: true
            },
            ward: {
                required: true
            },
            phone_number: {
                required: true,
                validPhone: true // Sẽ thêm phương thức kiểm tra số điện thoại hợp lệ
            }
        },
        messages: {
            name: {
                required: "Họ và tên là bắt buộc.",
                minlength: "Họ và tên phải có ít nhất 1 ký tự.",
                maxlength: "Họ và tên không được dài quá 30 ký tự."
            },
            email: {
                required: "Email là bắt buộc.",
                email: "Email không hợp lệ."
            },
            phone_number: {
                required: "Số điện thoại là bắt buộc.",
                validPhone: "Số điện thoại không hợp lệ."
            },
            city: {
                required: "Tỉnh, thành phố là bắt buộc."
            },
            district: {
                required: "Quận, huyện là bắt buộc."
            },
            ward: {
                required: "Phường, xã là bắt buộc."
            },
            apartment_number: {
                required: "Số nhà là bắt buộc."
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        submitHandler: (form) => {
            form.submit();
        },
    });

    $.ajaxSetup({
        headers: {
            token: "24d5b95c-7cde-11ed-be76-3233f989b8f3"
        },
    });
    getProvind();
    $(document).on('change', '#city', function(){
        $('#district').html("");
        $('#ward').html("");
        //get list province
        getProvind();
    });

    $(document).on('change', '#district', function(){
        $('#ward').html("");
        // get list ward
        getWard();
    });
});
// fucntion get district
function getProvind()
{
    let provinceId = $('#city').val();
    console.log('🔄 Đang load quận/huyện cho tỉnh:', provinceId);
    
    $.ajax({
        type: 'GET',
        url: 'https://online-gateway.ghn.vn/shiip/public-api/master-data/district',
        data: {
            province_id: provinceId
        }
    }).done((respones) => {
        console.log('✅ Nhận được dữ liệu quận/huyện:', respones);
        
        if (!respones || !respones.data || respones.data.length === 0) {
            console.error('❌ Không có dữ liệu quận/huyện');
            $('#district').html('<option value="">Không có dữ liệu</option>');
            return;
        }
        
        let option = '';
        //add data to district select
        respones.data.forEach(element => {
            option = `<option value="${element.DistrictID}">${element.DistrictName}</option>`
            $('#district').append(option);
        });
        
        console.log('✅ Đã load', respones.data.length, 'quận/huyện');
        getWard();
    }).fail((error) => {
        console.error('❌ Lỗi khi load quận/huyện:', error);
        $('#district').html('<option value="">Lỗi tải dữ liệu</option>');
    });
}

//function get ward
function getWard()
{
    let district_id  = $('#district').val();
    $.ajax({
        type: 'GET',
        url: 'https://online-gateway.ghn.vn/shiip/public-api/master-data/ward',
        data: {
            district_id : district_id 
        }
    }).done((respones) => {
        let option = '';
        //add data to ward select
        respones.data.forEach(element => {
            option = `<option value="${element.WardCode}">${element.NameExtension[0]}</option>`
            $('#ward').append(option);
        });
        getFee()
        getAddress()
    });
}

function getFee()
{
    let shop_id = "3577591";
    let from_district = "2027";
    let to_district = $('#district').val();
    $.ajax({
        type: 'GET',
        url: 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services',
        data: {
            shop_id: shop_id,
            from_district: from_district,
            to_district: to_district
        }
    }).done((respones) => {
        let from_district = "2027";
        let service_type = respones.data[0].service_type_id;
        let to_district_id = $('#district').val();
        let to_ward_code = $('#ward').val();
        let data = {
            service_type_id: service_type,
            insurance_value: 500000,
            coupon: null,
            from_district_id: from_district,
            to_district_id: to_district_id,
            to_ward_code: to_ward_code,
            height:15,
            length:15,
            weight:1000,
            width:15
        }

        $.ajax({
            type: 'GET',
            url: 'https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee',
            data: data
        }).done((respones) => {
            let fee = parseInt(respones.data.total);
            let totalProduct = parseInt($('#total-order-const').val());
            $('#fee').text(new Intl.NumberFormat().format(fee));
            $('#total-order').text(new Intl.NumberFormat().format(fee + totalProduct));
            $('#total-order-input').val(fee + totalProduct)
        });
    });
}

function getAddress()
{
    let ward = $('#ward option:selected').text()
    let district = $('#district option:selected').text()
    let city = $('#city option:selected').text()
    let apartment_number = $('#apartment_number').val()
    $('#address').val(apartment_number + ', ' + ward + ', ' + district + ', ' + city)
}


// Chức năng lấy vị trí GPS
$(document).on('click', '#get-location-btn', function(e) {
    e.preventDefault();
    const btn = $(this);
    const statusEl = $('#location-status');
    
    // Kiểm tra trình duyệt có hỗ trợ Geolocation không
    if (!navigator.geolocation) {
        alert('Trình duyệt của bạn không hỗ trợ định vị GPS');
        return;
    }
    
    // Disable button và hiển thị trạng thái
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang lấy vị trí...');
    statusEl.show().text('Đang lấy tọa độ GPS...');
    
    // Lấy vị trí hiện tại
    navigator.geolocation.getCurrentPosition(
        async function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            
            statusEl.text('Đang tìm địa chỉ...');
            
            try {
                // Thử API Google Geocoding (tốt nhất cho Việt Nam)
                let data = null;
                
                try {
                    // API 1: Google Geocoding (cần API key nhưng có thể dùng free tier)
                    const googleResponse = await fetch(
                        `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&language=vi&key=AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBknqv7I`
                    );
                    const googleData = await googleResponse.json();
                    
                    if (googleData.status === 'OK' && googleData.results.length > 0) {
                        data = parseGoogleAddress(googleData.results[0]);
                        console.log('Dữ liệu từ Google:', data);
                    }
                } catch (e) {
                    console.log('Google API không khả dụng, thử OpenStreetMap...');
                }
                
                // Nếu Google không hoạt động, dùng OpenStreetMap
                if (!data) {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`,
                        {
                            headers: {
                                'User-Agent': 'YeenOkShop',
                                'Accept-Language': 'vi'
                            }
                        }
                    );
                    
                    const osmData = await response.json();
                    data = parseOSMAddress(osmData);
                    console.log('Dữ liệu từ OSM:', data);
                    
                    // Nếu không có quận, thử tìm bằng GPS
                    if (!data.district && data.ward) {
                        console.log('🔍 Không có quận, thử suy luận từ phường...');
                        data.district = guessDistrictFromWard(data.ward);
                        
                        if (data.district) {
                            console.log('✅ Đã suy luận được quận:', data.district);
                        }
                    }
                    
                    // Nếu vẫn không có quận, thử tìm bằng tọa độ GPS
                    if (!data.district) {
                        console.log('🔍 Thử tìm quận bằng tọa độ GPS...');
                        const districtFromGPS = await findDistrictByGPS(latitude, longitude, data.city);
                        if (districtFromGPS) {
                            data.district = districtFromGPS;
                            console.log('✅ Đã tìm được quận từ GPS:', data.district);
                        }
                    }
                }
                
                if (data) {
                    console.log('Kết quả phân tích:', data);
                    
                    // Điền vào ô địa chỉ nhà
                    if (data.fullAddress) {
                        $('#apartment_number').val(data.fullAddress);
                    }
                    
                    // Hiển thị thông tin
                    statusEl.html(`✓ Đã lấy địa chỉ!<br>
                        Phường: ${data.ward || 'N/A'}<br>
                        Quận: ${data.district || 'N/A'}<br>
                        Thành phố: ${data.city || 'N/A'}`
                    ).css('color', 'green');
                    
                    // Tự động chọn
                    if (data.city) {
                        setTimeout(() => {
                            autoSelectCity(data.city, data.district, data.ward);
                        }, 500);
                    }
                    
                    setTimeout(() => statusEl.fadeOut(), 10000);
                } else {
                    throw new Error('Không tìm thấy địa chỉ');
                }
            } catch (error) {
                console.error('Lỗi khi lấy địa chỉ:', error);
                statusEl.text('✗ Không thể lấy địa chỉ. Vui lòng thử lại.').css('color', 'red');
                setTimeout(() => statusEl.fadeOut(), 3000);
            }
            
            // Enable lại button
            btn.prop('disabled', false).html('<i class="fa fa-map-marker"></i> Lấy vị trí');
        },
        function(error) {
            // Xử lý lỗi
            let errorMsg = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMsg = 'Bạn đã từ chối quyền truy cập vị trí';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMsg = 'Không thể xác định vị trí';
                    break;
                case error.TIMEOUT:
                    errorMsg = 'Hết thời gian chờ';
                    break;
                default:
                    errorMsg = 'Có lỗi xảy ra';
            }
            
            statusEl.text('✗ ' + errorMsg).css('color', 'red');
            setTimeout(() => statusEl.fadeOut(), 3000);
            btn.prop('disabled', false).html('<i class="fa fa-map-marker"></i> Lấy vị trí');
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
});

// Parse địa chỉ từ Google Geocoding API
function parseGoogleAddress(result) {
    const components = result.address_components;
    let houseNumber = '';
    let road = '';
    let ward = '';
    let district = '';
    let city = '';
    
    components.forEach(component => {
        const types = component.types;
        
        if (types.includes('street_number')) {
            houseNumber = component.long_name;
        }
        if (types.includes('route')) {
            road = component.long_name;
        }
        if (types.includes('sublocality_level_1') || types.includes('sublocality')) {
            ward = component.long_name;
        }
        if (types.includes('administrative_area_level_2')) {
            district = component.long_name;
        }
        if (types.includes('administrative_area_level_1')) {
            city = component.long_name;
        }
    });
    
    return {
        houseNumber,
        road,
        ward,
        district,
        city,
        fullAddress: [houseNumber, road].filter(x => x).join(' ')
    };
}

// Parse địa chỉ từ OpenStreetMap
function parseOSMAddress(data) {
    if (!data || !data.address) {
        return null;
    }
    
    const address = data.address;
    const displayName = data.display_name;
    const parts = displayName.split(',').map(p => p.trim());
    
    let houseNumber = address.house_number || '';
    let road = address.road || address.street || '';
    let ward = address.suburb || address.neighbourhood || address.quarter || '';
    let district = address.county || address.city_district || address.town || '';
    let city = address.city || address.province || address.state || '';
    
    // Cải thiện cho TP.HCM
    if (!city && parts.length > 0) {
        city = parts[parts.length - 1];
    }
    
    // Tìm phường trong display_name nếu chưa có
    if (!ward) {
        for (let part of parts) {
            if (part.toLowerCase().includes('phường') || 
                part.toLowerCase().includes('xã')) {
                ward = part;
                break;
            }
        }
    }
    
    // Tìm quận trong display_name nếu chưa có
    if (!district) {
        for (let part of parts) {
            if (part.toLowerCase().includes('quận') || 
                part.toLowerCase().includes('huyện')) {
                district = part;
                break;
            }
        }
    }
    
    return {
        houseNumber,
        road,
        ward,
        district,
        city,
        fullAddress: [houseNumber, road].filter(x => x).join(', ')
    };
}

// Hàm tự động chọn tỉnh/thành phố và quận/huyện
function autoSelectLocation(cityName, districtName, wardName) {
    console.log('Tìm kiếm:', { cityName, districtName, wardName });
    
    // Chuẩn hóa tên thành phố
    let normalizedCity = cityName.toLowerCase()
        .replace(/thành phố /gi, '')
        .replace(/tỉnh /gi, '')
        .replace(/tp\. /gi, '')
        .replace(/tp /gi, '')
        .trim();
    
    // Tìm và chọn tỉnh/thành phố
    let cityFound = false;
    $('#city option').each(function() {
        const optionText = $(this).text().toLowerCase()
            .replace(/thành phố /gi, '')
            .replace(/tỉnh /gi, '')
            .replace(/tp\. /gi, '')
            .replace(/tp /gi, '')
            .trim();
        
        // So sánh linh hoạt hơn
        if (optionText.includes(normalizedCity) || 
            normalizedCity.includes(optionText) ||
            removeVietnameseTones(optionText).includes(removeVietnameseTones(normalizedCity)) ||
            removeVietnameseTones(normalizedCity).includes(removeVietnameseTones(optionText))) {
            
            $(this).prop('selected', true);
            $('#city').trigger('change');
            cityFound = true;
            console.log('Đã chọn thành phố:', $(this).text());
            
            // Đợi load xong district rồi mới chọn
            setTimeout(() => {
                if (districtName) {
                    let normalizedDistrict = districtName.toLowerCase()
                        .replace(/quận /gi, '')
                        .replace(/huyện /gi, '')
                        .replace(/thị xã /gi, '')
                        .replace(/thành phố /gi, '')
                        .trim();
                    
                    let districtFound = false;
                    $('#district option').each(function() {
                        const districtText = $(this).text().toLowerCase()
                            .replace(/quận /gi, '')
                            .replace(/huyện /gi, '')
                            .replace(/thị xã /gi, '')
                            .replace(/thành phố /gi, '')
                            .trim();
                        
                        if (districtText.includes(normalizedDistrict) || 
                            normalizedDistrict.includes(districtText) ||
                            removeVietnameseTones(districtText).includes(removeVietnameseTones(normalizedDistrict)) ||
                            removeVietnameseTones(normalizedDistrict).includes(removeVietnameseTones(districtText))) {
                            
                            $(this).prop('selected', true);
                            $('#district').trigger('change');
                            districtFound = true;
                            console.log('Đã chọn quận/huyện:', $(this).text());
                            
                            // Đợi load xong ward rồi chọn phường
                            if (wardName) {
                                console.log('Sẽ tìm phường sau 2 giây...');
                                setTimeout(() => {
                                    autoSelectWard(wardName);
                                }, 2000);
                            } else {
                                console.log('Không có tên phường để tìm');
                            }
                            
                            return false;
                        }
                    });
                    
                    if (!districtFound) {
                        console.log('Không tìm thấy quận/huyện:', districtName);
                    }
                }
            }, 1500);
            
            return false;
        }
    });
    
    if (!cityFound) {
        console.log('Không tìm thấy thành phố:', cityName);
    }
}

// Hàm chọn thành phố và cascade chọn quận, phường
function autoSelectCity(cityName, districtName, wardName) {
    console.log('🔍 Bước 1: Chọn Thành phố -', cityName);
    
    if (!cityName) {
        console.log('❌ Không có tên thành phố');
        return;
    }
    
    // Chuẩn hóa tên thành phố
    const normalizedCity = removeVietnameseTones(cityName.toLowerCase()
        .replace(/thành phố /gi, '')
        .replace(/tỉnh /gi, '')
        .replace(/tp\.? /gi, '')
        .trim());
    
    let cityFound = false;
    
    // Tìm và chọn thành phố
    $('#city option').each(function() {
        const optionText = $(this).text();
        const normalizedOption = removeVietnameseTones(optionText.toLowerCase()
            .replace(/thành phố /gi, '')
            .replace(/tỉnh /gi, '')
            .replace(/tp\.? /gi, '')
            .trim());
        
        // So sánh linh hoạt
        if (normalizedOption.includes(normalizedCity) || 
            normalizedCity.includes(normalizedOption)) {
            
            $(this).prop('selected', true);
            $('#city').trigger('change');
            cityFound = true;
            console.log('✅ Đã chọn Thành phố:', optionText);
            
            // Chuyển sang bước 2: Chọn quận/huyện
            setTimeout(() => {
                autoSelectDistrict(districtName, wardName);
            }, 1500);
            
            return false; // Break loop
        }
    });
    
    if (!cityFound) {
        console.log('❌ Không tìm thấy Thành phố:', cityName);
    }
}

function autoSelectDistrict(districtName, wardName) {
    console.log('🔍 Bước 2: Chọn Quận/Huyện -', districtName);
    
    if (!districtName) {
        console.log('⚠️ Không có tên quận/huyện, bỏ qua');
        return;
    }
    
    // Chuẩn hóa tên quận
    const normalizedDistrict = removeVietnameseTones(districtName.toLowerCase()
        .replace(/quận /gi, '')
        .replace(/huyện /gi, '')
        .replace(/thị xã /gi, '')
        .replace(/thành phố /gi, '')
        .trim());
    
    let districtFound = false;
    
    // Tìm và chọn quận/huyện
    $('#district option').each(function() {
        const optionText = $(this).text();
        const normalizedOption = removeVietnameseTones(optionText.toLowerCase()
            .replace(/quận /gi, '')
            .replace(/huyện /gi, '')
            .replace(/thị xã /gi, '')
            .replace(/thành phố /gi, '')
            .trim());
        
        // So sánh linh hoạt
        if (normalizedOption.includes(normalizedDistrict) || 
            normalizedDistrict.includes(normalizedOption)) {
            
            $(this).prop('selected', true);
            $('#district').trigger('change');
            districtFound = true;
            console.log('✅ Đã chọn Quận/Huyện:', optionText);
            
            // Chuyển sang bước 3: Chọn phường/xã
            setTimeout(() => {
                autoSelectWard(wardName);
            }, 1500);
            
            return false; // Break loop
        }
    });
    
    if (!districtFound) {
        console.log('❌ Không tìm thấy Quận/Huyện:', districtName);
    }
}

// Hàm tự động chọn phường/xã
function autoSelectWard(wardName) {
    console.log('🔍 Bước 3: Chọn Phường/Xã -', wardName);
    
    if (!wardName) {
        console.log('⚠️ Không có tên phường/xã, bỏ qua');
        console.log('✅ Hoàn tất! Vui lòng kiểm tra và điều chỉnh nếu cần.');
        return;
    }
    
    // Kiểm tra đã load xong chưa
    if ($('#ward option').length <= 1) {
        console.log('⏳ Đang load danh sách phường/xã, thử lại sau 1 giây...');
        setTimeout(() => autoSelectWard(wardName), 1000);
        return;
    }
    
    // Chuẩn hóa tên phường
    const normalizedWard = removeVietnameseTones(wardName.toLowerCase()
        .replace(/phường /gi, '')
        .replace(/xã /gi, '')
        .replace(/thị trấn /gi, '')
        .trim());
    
    let wardFound = false;
    let bestMatch = null;
    let bestMatchScore = 0;
    
    // Tìm và chọn phường/xã
    $('#ward option').each(function() {
        if ($(this).val() === '') return; // Bỏ qua option mặc định
        
        const optionText = $(this).text();
        const normalizedOption = removeVietnameseTones(optionText.toLowerCase()
            .replace(/phường /gi, '')
            .replace(/xã /gi, '')
            .replace(/thị trấn /gi, '')
            .trim());
        
        // So sánh chính xác 100%
        if (normalizedOption === normalizedWard) {
            $(this).prop('selected', true);
            $('#ward').trigger('change');
            wardFound = true;
            console.log('✅ Đã chọn Phường/Xã (khớp 100%):', optionText);
            console.log('🎉 Hoàn tất tự động chọn địa chỉ!');
            return false; // Break loop
        }
        
        // Tính điểm tương đồng cho best match
        if (normalizedOption.includes(normalizedWard) || 
            normalizedWard.includes(normalizedOption)) {
            const score = Math.min(normalizedWard.length, normalizedOption.length) / 
                         Math.max(normalizedWard.length, normalizedOption.length);
            if (score > bestMatchScore) {
                bestMatchScore = score;
                bestMatch = this;
            }
        }
    });
    
    // Nếu không tìm thấy khớp 100%, chọn best match
    if (!wardFound && bestMatch && bestMatchScore > 0.7) {
        $(bestMatch).prop('selected', true);
        $('#ward').trigger('change');
        console.log('✅ Đã chọn Phường/Xã (khớp ' + Math.round(bestMatchScore * 100) + '%):', $(bestMatch).text());
        console.log('⚠️ Vui lòng kiểm tra lại phường/xã có đúng không!');
        wardFound = true;
    }
    
    if (!wardFound) {
        console.log('❌ Không tìm thấy Phường/Xã:', wardName);
        console.log('💡 Vui lòng chọn thủ công');
    }
    
    if (!found) {
        console.log('✗ Không tìm thấy. Danh sách có:');
        $('#ward option').each(function(i) {
            console.log(`  ${i+1}. ${$(this).text()}`);
        });
    }
}

// Hàm suy ra quận từ tên phường (đặc biệt cho TP.HCM)
function guessDistrictFromWard(wardName) {
    if (!wardName) return '';
    
    const ward = removeVietnameseTones(wardName.toLowerCase());
    
    // Map phường với quận cho TP.HCM (mở rộng)
    const wardDistrictMap = {
        // Quận Bình Tân
        'binh tri dong': 'Bình Tân',
        'binh tri dong a': 'Bình Tân',
        'binh tri dong b': 'Bình Tân',
        'binh hung hoa': 'Bình Tân',
        'binh hung hoa a': 'Bình Tân',
        'binh hung hoa b': 'Bình Tân',
        'an lac': 'Bình Tân',
        'an lac a': 'Bình Tán',
        'tan tao': 'Bình Tân',
        'tan tao a': 'Bình Tân',
        
        // Quận Bình Thạnh
        '1 binh thanh': 'Bình Thạnh',
        '2 binh thanh': 'Bình Thạnh',
        '3 binh thanh': 'Bình Thạnh',
        '5 binh thanh': 'Bình Thạnh',
        '6 binh thanh': 'Bình Thạnh',
        '7 binh thanh': 'Bình Thạnh',
        '11 binh thanh': 'Bình Thạnh',
        '12 binh thanh': 'Bình Thạnh',
        '13 binh thanh': 'Bình Thạnh',
        '14 binh thanh': 'Bình Thạnh',
        '15 binh thanh': 'Bình Thạnh',
        '17 binh thanh': 'Bình Thạnh',
        '19 binh thanh': 'Bình Thạnh',
        '21 binh thanh': 'Bình Thạnh',
        '22 binh thanh': 'Bình Thạnh',
        '24 binh thanh': 'Bình Thạnh',
        '25 binh thanh': 'Bình Thạnh',
        '26 binh thanh': 'Bình Thạnh',
        '27 binh thanh': 'Bình Thạnh',
        '28 binh thanh': 'Bình Thạnh',
        
        // Quận Tân Bình
        '1 tan binh': 'Tân Bình',
        '2 tan binh': 'Tân Bình',
        '3 tan binh': 'Tân Bình',
        '4 tan binh': 'Tân Bình',
        '5 tan binh': 'Tân Bình',
        '6 tan binh': 'Tân Bình',
        '7 tan binh': 'Tân Bình',
        '8 tan binh': 'Tân Bình',
        '9 tan binh': 'Tân Bình',
        '10 tan binh': 'Tân Bình',
        '11 tan binh': 'Tân Bình',
        '12 tan binh': 'Tân Bình',
        '13 tan binh': 'Tân Bình',
        '14 tan binh': 'Tân Bình',
        '15 tan binh': 'Tân Bình',
        
        // Quận Thủ Đức
        'linh xuan': 'Thủ Đức',
        'linh trung': 'Thủ Đức',
        'linh chieu': 'Thủ Đức',
        'linh dong': 'Thủ Đức',
        'linh tay': 'Thủ Đức',
        'tam binh': 'Thủ Đức',
        'tam phu': 'Thủ Đức',
        'hieu linh': 'Thủ Đức',
        'truong tho': 'Thủ Đức',
    };
    
    // Tìm trong map
    for (const [key, value] of Object.entries(wardDistrictMap)) {
        if (ward.includes(key)) {
            return value;
        }
    }
    
    // Thử suy luận từ pattern tên phường
    if (ward.includes('binh tan')) return 'Bình Tân';
    if (ward.includes('binh thanh')) return 'Bình Thạnh';
    if (ward.includes('tan binh')) return 'Tân Bình';
    if (ward.includes('tan phu')) return 'Tân Phú';
    if (ward.includes('phu nhuan')) return 'Phú Nhuận';
    if (ward.includes('go vap')) return 'Gò Vấp';
    if (ward.includes('thu duc')) return 'Thủ Đức';
    
    return ''; // Không suy ra được
}

// Hàm tìm quận gần nhất bằng tọa độ GPS
async function findDistrictByGPS(lat, lon, cityName) {
    try {
        console.log('📍 Tìm quận từ GPS:', { lat, lon, cityName });
        
        // Lấy danh sách tất cả quận của thành phố
        const cityId = getCityIdByName(cityName);
        if (!cityId) {
            console.log('❌ Không tìm thấy ID thành phố');
            return null;
        }
        
        const response = await $.ajax({
            type: 'GET',
            url: 'https://online-gateway.ghn.vn/shiip/public-api/master-data/district',
            headers: {
                token: "24d5b95c-7cde-11ed-be76-3233f989b8f3"
            },
            data: {
                province_id: cityId
            }
        });
        
        if (!response || !response.data || response.data.length === 0) {
            console.log('❌ Không có dữ liệu quận');
            return null;
        }
        
        console.log('📍 Tìm thấy', response.data.length, 'quận/huyện');
        
        // Tìm quận bằng cách query từng quận với OSM
        const districtCoords = getDistrictCoordinates(cityName);
        
        if (districtCoords && Object.keys(districtCoords).length > 0) {
            // Tính khoảng cách đến từng quận
            let nearestDistrict = null;
            let minDistance = Infinity;
            
            for (const [districtName, coords] of Object.entries(districtCoords)) {
                const distance = calculateDistance(lat, lon, coords.lat, coords.lon);
                console.log(`  - ${districtName}: ${distance.toFixed(2)} km`);
                
                if (distance < minDistance) {
                    minDistance = distance;
                    nearestDistrict = districtName;
                }
            }
            
            if (nearestDistrict) {
                console.log(`✅ Quận gần nhất: ${nearestDistrict} (${minDistance.toFixed(2)} km)`);
                return nearestDistrict;
            }
        }
        
        // Fallback: Trả về quận đầu tiên
        console.log('⚠️ Không tính được khoảng cách, chọn quận đầu tiên');
        return response.data[0].DistrictName;
        
    } catch (error) {
        console.error('❌ Lỗi khi tìm quận từ GPS:', error);
        return null;
    }
}

// Hàm tính khoảng cách giữa 2 điểm GPS (Haversine formula)
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Bán kính Trái Đất (km)
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Database tọa độ trung tâm các quận TP.HCM
function getDistrictCoordinates(cityName) {
    const normalized = removeVietnameseTones(cityName.toLowerCase());
    
    if (normalized.includes('ho chi minh')) {
        return {
            'Quận 1': { lat: 10.7756, lon: 106.7019 },
            'Quận 2': { lat: 10.7829, lon: 106.7436 },
            'Quận 3': { lat: 10.7839, lon: 106.6881 },
            'Quận 4': { lat: 10.7574, lon: 106.7037 },
            'Quận 5': { lat: 10.7546, lon: 106.6677 },
            'Quận 6': { lat: 10.7474, lon: 106.6345 },
            'Quận 7': { lat: 10.7333, lon: 106.7196 },
            'Quận 8': { lat: 10.7380, lon: 106.6291 },
            'Quận 9': { lat: 10.8502, lon: 106.7890 },
            'Quận 10': { lat: 10.7729, lon: 106.6685 },
            'Quận 11': { lat: 10.7626, lon: 106.6503 },
            'Quận 12': { lat: 10.8635, lon: 106.6621 },
            'Bình Thạnh': { lat: 10.8054, lon: 106.7138 },
            'Bình Tân': { lat: 10.7401, lon: 106.6055 },
            'Gò Vấp': { lat: 10.8376, lon: 106.6765 },
            'Phú Nhuận': { lat: 10.7980, lon: 106.6825 },
            'Tân Bình': { lat: 10.8006, lon: 106.6525 },
            'Tân Phú': { lat: 10.7881, lon: 106.6281 },
            'Thủ Đức': { lat: 10.8509, lon: 106.7717 },
            'Bình Chánh': { lat: 10.6891, lon: 106.5789 },
            'Cần Giờ': { lat: 10.4078, lon: 106.9547 },
            'Củ Chi': { lat: 10.9742, lon: 106.4922 },
            'Hóc Môn': { lat: 10.8843, lon: 106.5925 },
            'Nhà Bè': { lat: 10.6954, lon: 106.7297 }
        };
    }
    
    // Có thể thêm tọa độ cho các thành phố khác
    if (normalized.includes('ha noi')) {
        return {
            'Ba Đình': { lat: 21.0341, lon: 105.8195 },
            'Hoàn Kiếm': { lat: 21.0285, lon: 105.8542 },
            'Hai Bà Trưng': { lat: 21.0096, lon: 105.8478 },
            'Đống Đa': { lat: 21.0181, lon: 105.8270 },
            'Tây Hồ': { lat: 21.0715, lon: 105.8192 },
            'Cầu Giấy': { lat: 21.0333, lon: 105.7943 },
            'Thanh Xuân': { lat: 20.9948, lon: 105.8081 },
            'Hoàng Mai': { lat: 20.9815, lon: 105.8468 },
            'Long Biên': { lat: 21.0364, lon: 105.8938 },
            'Nam Từ Liêm': { lat: 21.0167, lon: 105.7573 },
            'Bắc Từ Liêm': { lat: 21.0715, lon: 105.7574 },
            'Hà Đông': { lat: 20.9719, lon: 105.7692 }
        };
    }
    
    return {};
}

// Hàm lấy ID thành phố từ tên
function getCityIdByName(cityName) {
    if (!cityName) return null;
    
    const normalized = removeVietnameseTones(cityName.toLowerCase());
    
    // Map các thành phố phổ biến với ID của GHN
    const cityMap = {
        'ho chi minh': 202,
        'ha noi': 201,
        'da nang': 203,
        'binh duong': 217,
        'dong nai': 218,
        'can tho': 292,
    };
    
    for (const [key, value] of Object.entries(cityMap)) {
        if (normalized.includes(key)) {
            return value;
        }
    }
    
    return null;
}

// Hàm bỏ dấu tiếng Việt để so sánh
function removeVietnameseTones(str) {
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    return str;
}
