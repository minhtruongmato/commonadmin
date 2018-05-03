function logout() {
    var doLogout = confirm('Do you wanna logout?');
    if (doLogout) {
        $.ajax({
            method: 'GET',
            url: HOSTNAME + 'admin/user/logout',
            success: function(response){
                if(response.status == 200){
                    window.location.href = HOSTNAME + 'admin/user/login';
                }
            },
            error: function(jqXHR, exception){
                console.log(errorHandle(jqXHR, exception));
            }
        });
    }
}

function errorHandle(jqXHR, exception){
    if (jqXHR.status === 0) {
        return ('Not connected.\nPlease verify your network connection.');
    } else if (jqXHR.status == 404) {
        return ('The requested page not found.');
    }  else if (jqXHR.status == 401) {
        return ('Sorry!! You session has expired. Please login to continue access.');
    } else if (jqXHR.status == 500) {
        return ('Internal Server Error.');
    } else if (exception === 'parsererror') {
        return ('Requested JSON parse failed.');
    } else if (exception === 'timeout') {
        return ('Time out error.');
    } else if (exception === 'abort') {
        return ('Ajax request aborted.');
    }

    return ('Unknown error occurred. Please try again.');
}

function to_slug(str){
    str = str.toLowerCase();

    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
    str = str.replace(/(đ)/g, 'd');

    str = str.replace(/([^0-9a-z-\s])/g, '');

    str = str.replace(/(\s+)/g, '-');

    str = str.replace(/^-+/g, '');

    str = str.replace(/-+$/g, '');

    // return
    return str;
}

var csrf_hash = $("input[name='csrf_seafood_token']").val();
function remove(controller, id){
    var url = HOSTNAME + 'admin/' + controller + '/remove';
    if(confirm('Chắc chắn xóa?')){
        $.ajax({
            method: "post",
            url: url,
            data: {
                id : id, csrf_seafood_token : csrf_hash
            },
            success: function(response){
                if(response.status == 200){
                    csrf_hash = response.reponse.csrf_hash;
                    $('.remove_' + id).fadeOut();
                }
            },
            error: function(jqXHR, exception){
                console.log(errorHandle(jqXHR, exception));
            }
        });
    }
}

function active(controller, id, question, message = '') {
    var url = HOSTNAME + 'admin/' + controller + '/active';
    if(confirm(question)){
        $.ajax({
            method: "post",
            url: url,
            data: {
                id : id, csrf_seafood_token : csrf_hash
            },
            success: function(response){
                if(response.success == true){
                    csrf_hash = response.reponse.csrf_hash;
                    switch(controller){
                        case 'event' :
                            alert('Mở sự kiện thành công');
                            break;
                        case 'order' :
                            alert('Xác nhận đặt bàn thành công');
                            break;
                        case 'upload' :
                            alert('Thay đổi thành công');
                            break;
                    }
                    
                    location.reload();
                }else{
                    switch(controller){
                        case 'event' :
                            alert('Hiện có 1 sự kiện đang được sử dụng. Vui lòng tắt sự kiện đó rồi thực hiện lại thao tác!');
                            break;
                        case 'upload' :
                            alert(message);
                            break;
                    }
                    
                    location.reload();
                }
            },
            error: function(jqXHR, exception){
                console.log(errorHandle(jqXHR, exception));
            }
        });
    }
}

function deactive(controller, id, question) {
    var url = HOSTNAME + 'admin/' + controller + '/deactive';
    if(confirm(question)){
        $.ajax({
            method: "post",
            url: url,
            data: {
                id : id, csrf_seafood_token : csrf_hash
            },
            success: function(response){
                csrf_hash = response.reponse.csrf_hash;
                if(response.status == 200){
                    
                    switch(controller){
                        case 'event' :
                            alert('Tắt sự kiện thành công');
                            break;
                        case 'order' :
                            alert('Hủy đặt bàn thành công');
                            break;
                    }
                    location.reload();
                }
            },
            error: function(jqXHR, exception){
                console.log(errorHandle(jqXHR, exception));
            }
        });
    }
}

function remove_image(controller, id, image, key){
    var url = HOSTNAME + 'admin/' + controller + '/remove_image';
    if(confirm('Chắc chắn xóa ảnh này?')){
        $.ajax({
            method: "post",
            url: url,
            data: {
                id : id, csrf_seafood_token : csrf_hash, image : image
            },
            success: function(response){
                if(response.status == 200){
                    csrf_hash = response.reponse.csrf_hash;
                    $('.row_' + key).fadeOut();
                    $("input[name='csrf_seafood_token']").val(csrf_hash);
                }
            },
            error: function(jqXHR, exception){
                console.log(errorHandle(jqXHR, exception));
            }
        });
    }
}

function active_avatar(controller, image) {
    var url = HOSTNAME + 'admin/' + controller + '/active_avatar';
    if(confirm('Chọn hình ảnh này làm avatar?')){
        $.ajax({
            method: "post",
            url: url,
            data: {
                csrf_seafood_token : csrf_hash, image : image
            },
            success: function(response){
                if(response.status == 200){
                    csrf_hash = response.reponse.csrf_hash;
                    location.reload();
                }
            },
            error: function(jqXHR, exception){
                console.log(errorHandle(jqXHR, exception));
            }
        });
    }
}