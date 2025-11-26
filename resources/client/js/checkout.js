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
    $.ajax({
        type: 'GET',
        url: 'https://online-gateway.ghn.vn/shiip/public-api/master-data/district',
        data: {
            province_id: provinceId
        }
    }).done((respones) => {
        let option = '';
        //add data to district select
        respones.data.forEach(element => {
            option = `<option value="${element.DistrictID}">${element.DistrictName}</option>`
            $('#district').append(option);
        });
        getWard();
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
                // Thử API 1: OpenStreetMap với zoom cao hơn
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`,
                    {
                        headers: {
                            'User-Agent': 'ngduythong1412@gmail.com',
                            'Accept-Language': 'vi'
                        }
                    }
                );
                
                const data = await response.json();
                console.log('Dữ liệu đầy đủ từ API:', data);
                
                if (data && data.address) {
                    const address = data.address;
                    const displayName = data.display_name;
                    
                    console.log('Address object:', address);
                    console.log('Display name:', displayName);
                    
                    // Phân tích display_name để lấy thông tin chính xác hơn
                    const parts = displayName.split(',').map(p => p.trim());
                    console.log('Các phần địa chỉ:', parts);
                    
                    // Lấy thông tin địa chỉ
                    const houseNumber = address.house_number || '';
                    const road = address.road || address.street || '';
                    const neighbourhood = address.neighbourhood || ''; // Khu phố
                    
                    // Lấy tỉnh/thành phố TRƯỚC
                    let city = address.city || 
                              address.province || 
                              address.state || '';
                    
                    // Nếu không có, lấy phần cuối của display_name
                    if (!city && parts.length > 0) {
                        // Tỉnh/thành phố thường ở cuối
                        for (let i = parts.length - 1; i >= 0; i--) {
                            if (parts[i].toLowerCase().includes('thành phố') || 
                                parts[i].toLowerCase().includes('tỉnh')) {
                                city = parts[i];
                                break;
                            }
                        }
                        // Nếu vẫn không có, lấy phần cuối cùng
                        if (!city) {
                            city = parts[parts.length - 1];
                        }
                    }
                    
                    // Lấy phường/xã
                    let ward = address.suburb || 
                              address.neighbourhood || 
                              address.quarter ||
                              address.village || 
                              address.hamlet || '';
                    
                    // Nếu không có, thử lấy từ display_name
                    if (!ward && parts.length > 2) {
                        for (let i = 1; i < Math.min(4, parts.length); i++) {
                            if (parts[i].toLowerCase().includes('phường') || 
                                parts[i].toLowerCase().includes('xã') ||
                                parts[i].toLowerCase().includes('thị trấn')) {
                                ward = parts[i];
                                break;
                            }
                        }
                    }
                    
                    // Lấy quận/huyện
                    let district = address.county || 
                                  address.city_district || 
                                  address.town || '';
                    
                    // Loại bỏ nếu district trùng với city
                    if (district === city) {
                        district = '';
                    }
                    
                    // Với TP.HCM, district thường không có trong address object
                    // Thử suy ra từ tên phường
                    if (city.toLowerCase().includes('hồ chí minh') && ward && !district) {
                        district = guessDistrictFromWard(ward);
                        console.log('Suy ra quận từ phường:', district);
                    }
                    
                    // Nếu vẫn không có, tìm trong display_name
                    if (!district) {
                        for (let i = 0; i < parts.length; i++) {
                            const part = parts[i].toLowerCase();
                            // Bỏ qua nếu là phường hoặc thành phố
                            if (part.includes('phường') || part.includes('xã') || 
                                part.includes('thị trấn') || part === city.toLowerCase()) {
                                continue;
                            }
                            // Tìm quận/huyện
                            if (part.includes('quận') || 
                                part.includes('huyện') ||
                                part.includes('thị xã')) {
                                district = parts[i];
                                break;
                            }
                        }
                    }
                    
                    console.log('Kết quả phân tích:', { 
                        houseNumber, 
                        road, 
                        ward, 
                        district, 
                        city 
                    });
                    
                    // Tạo địa chỉ đầy đủ cho ô địa chỉ nhà (số nhà, đường, khu phố)
                    let fullAddress = [houseNumber, road, neighbourhood].filter(x => x).join(', ');
                    
                    // Điền vào ô địa chỉ nhà
                    if (fullAddress) {
                        $('#apartment_number').val(fullAddress);
                    } else if (parts.length > 0) {
                        // Lấy 3 phần đầu tiên
                        $('#apartment_number').val(parts.slice(0, 3).join(', '));
                    }
                    
                    // Hiển thị thông tin
                    statusEl.html('✓ Đã lấy địa chỉ! Đang tự động chọn...').css('color', 'green');
                    
                    // Tự động chọn
                    if (city) {
                        autoSelectCity(city, district, ward);
                    }
                    
                    setTimeout(() => statusEl.fadeOut(), 8000);
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
    console.log('Auto select:', { cityName, districtName, wardName });
    
    // Chọn thành phố
    $('#city option').each(function() {
        if ($(this).text().includes('Hồ Chí Minh') || $(this).text().includes('TP.HCM')) {
            $(this).prop('selected', true);
            $('#city').trigger('change');
            console.log('✓ Đã chọn:', $(this).text());
            
            // Đợi load district
            setTimeout(() => {
                if (districtName) {
                    autoSelectDistrict(districtName, wardName);
                }
            }, 1500);
            
            return false;
        }
    });
}

function autoSelectDistrict(districtName, wardName) {
    console.log('Tìm quận:', districtName);
    
    $('#district option').each(function() {
        const text = $(this).text();
        if (text.includes('Bình Tân')) {
            $(this).prop('selected', true);
            $('#district').trigger('change');
            console.log('✓ Đã chọn:', text);
            
            // Đợi load ward
            setTimeout(() => {
                if (wardName) {
                    autoSelectWard(wardName);
                }
            }, 1500);
            
            return false;
        }
    });
}

// Hàm tự động chọn phường/xã
function autoSelectWard(wardName) {
    console.log('Tìm phường:', wardName);
    
    if ($('#ward option').length === 0) {
        console.log('Chưa load xong, thử lại...');
        setTimeout(() => autoSelectWard(wardName), 1000);
        return;
    }
    
    // Chuẩn hóa: "Phường Bình Trị Đông" -> "binh tri dong"
    const normalized = removeVietnameseTones(wardName.toLowerCase()
        .replace(/phường /gi, '')
        .replace(/xã /gi, '')
        .replace(/thị trấn /gi, '')
        .trim());
    
    console.log('Tìm kiếm:', normalized);
    
    let found = false;
    let bestMatch = null;
    let bestMatchScore = 0;
    
    $('#ward option').each(function() {
        const text = $(this).text();
        const textNormalized = removeVietnameseTones(text.toLowerCase()
            .replace(/phường /gi, '')
            .replace(/xã /gi, '')
            .replace(/thị trấn /gi, '')
            .trim());
        
        console.log('So sánh:', textNormalized, 'với', normalized);
        
        // So sánh chính xác tuyệt đối trước
        if (textNormalized === normalized) {
            $(this).prop('selected', true);
            $('#ward').trigger('change');
            console.log('✓ Đã chọn (khớp 100%):', text);
            found = true;
            return false;
        }
        
        // Nếu không khớp 100%, tính điểm tương đồng
        // Ưu tiên phường ngắn hơn (không có A, B)
        if (textNormalized.includes(normalized)) {
            const score = normalized.length / textNormalized.length;
            if (score > bestMatchScore) {
                bestMatchScore = score;
                bestMatch = this;
            }
        }
    });
    
    // Nếu không tìm thấy khớp 100%, chọn best match
    if (!found && bestMatch && bestMatchScore > 0.8) {
        $(bestMatch).prop('selected', true);
        $('#ward').trigger('change');
        console.log('✓ Đã chọn (khớp ' + Math.round(bestMatchScore * 100) + '%):', $(bestMatch).text());
        found = true;
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
    const ward = wardName.toLowerCase();
    
    // Map một số phường phổ biến với quận
    const wardDistrictMap = {
        'bình trị đông': 'Quận Bình Tân',
        'bình trị đông a': 'Quận Bình Tân',
        'bình trị đông b': 'Quận Bình Tân',
        'bình hưng hòa': 'Quận Bình Tân',
        'bình hưng hòa a': 'Quận Bình Tân',
        'bình hưng hòa b': 'Quận Bình Tân',
        'an lạc': 'Quận Bình Tân',
        'an lạc a': 'Quận Bình Tân',
        'tân tạo': 'Quận Bình Tân',
        'tân tạo a': 'Quận Bình Tân',
    };
    
    // Tìm trong map
    for (const [key, value] of Object.entries(wardDistrictMap)) {
        if (ward.includes(key)) {
            return value;
        }
    }
    
    // Nếu không tìm thấy, thử extract từ tên phường
    // VD: "Phường Bình Trị Đông" -> "Quận Bình Tân"
    if (ward.includes('bình')) {
        if (ward.includes('trị') || ward.includes('hưng')) return 'Quận Bình Tân';
        if (ward.includes('thạnh')) return 'Quận Bình Thạnh';
    }
    
    return ''; // Không suy ra được
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
