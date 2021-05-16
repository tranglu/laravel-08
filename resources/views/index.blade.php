<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hello, world!</title>
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/bootstrap/css/bootstrap-icons.css') }}" rel="stylesheet" >
    <script src="{{ asset('assets/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(function() {
            getInfo();
            function getCharacterLength (str) {
                return [...str].length;
            }
            function getInfo() {
                let tokenFlag = localStorage.getItem('bearer_token');
                if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
                    $(".class-outside").hide();
                    $.ajax({
                        url: '{{ route('api-get-current-user') }}',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Authorization': 'Bearer ' + tokenFlag
                        },
                        beforeSend: function () {
                        },
                        type: 'get',
                        dataType: "json",
                        contentType: "application/json",
                        cache: false,
                        processData: false,
                        success: function(data) {
                            console.log('data: ');
                            console.log(data);
                            if (data) {
                                $("#info_name").html(data.name);
                                $("#info_email").html(data.email);
                                $("#info_provider").html(data.provider);
                                $(".class-inside").show();
                            }
                        },
                        error: function(xhr,status,error){
                            console.log('Lỗi: ' + status + " - " + error);
                        },
                        complete: function(xhr, textStatus) {
                        }
                    });
                } else {
                    $(".class-outside").show();
                    $(".class-inside").hide();
                }
            }

            $("#btn_logout").on("click", function (e) {
                e.preventDefault();
                let tokenFlag = localStorage.getItem('bearer_token');
                $.ajax({
                    url: '{{ route('api-auth-logout') }}',
                    headers: {
                        'Authorization': 'Bearer ' + tokenFlag
                    },
                    beforeSend: function () {
                    },
                    type: 'POST',
                    dataType: "json",
                    contentType: 'application/json',
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log('data: ');
                        console.log(data);
                        if (data.message === 'Tokens Revoked') {
                            localStorage.setItem('bearer_token', '');
                            $(".class-outside").show();
                            $(".class-inside").hide();
                        }
                    },
                    error: function(xhr,status,error){
                        console.log('Lỗi: ' + status + " - " + error);
                    },
                    complete: function(xhr, textStatus) {
                    }
                });
            });
            $("#btn_create_user").on("click", function (e) {
                e.preventDefault();
                let formData = new FormData();
                let password = $("#password").val();
                formData.append('name',$("#name").val());
                formData.append('email',$("#email").val());
                formData.append('password',password);
                formData.append('password_confirmation',password);
                let object = {};
                formData.forEach(function(value, key){
                    object[key] = value;
                });
                let json = JSON.stringify(object);
                $.ajax({
                    url: '{{ route('api-auth-register') }}',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: json,
                    beforeSend: function () {
                    },
                    type: 'POST',
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log('data: ');
                        console.log(data);
                        if (data.status === 'Success') {
                            alert('Đăng ký thành công');
                        } else {
                            alert('-->> Đăng ký thất bại !!!');
                        }
                    },
                    error: function(xhr,status,error){
                        console.log('Lỗi: ' + status + " - " + error);
                        alert('-->> Đăng ký thất bại !!!');
                    },
                    complete: function(xhr, textStatus) {
                    }
                });
            });
            $("#btn_login").on("click", function (e) {
                e.preventDefault();
                let formData = new FormData();
                formData.append('email',$("#email_login").val());
                formData.append('password',$("#password_login").val());
                let object = {};
                formData.forEach(function(value, key){
                    object[key] = value;
                });
                let json = JSON.stringify(object);
                $.ajax({
                    url: '{{ route('api-auth-login') }}',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: json,
                    beforeSend: function () {
                    },
                    type: 'POST',
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log('data: ');
                        console.log(data);
                        if (data.code === 2000 && data.status === 'Success') {
                            localStorage.setItem('bearer_token',data.data.token);
                            console.log('bearer_token = '+data.data.token);
                            $(".class-outside").hide();
                            $(".class-inside").show();
                            getInfo()
                        } else {
                            alert('-->> Đăng nhập thất bại !!!');
                        }
                    },
                    error: function(xhr,status,error){
                        console.log('Lỗi: ' + status + " - " + error);
                        alert('-->> Đăng nhập thất bại !!!');
                    },
                    complete: function(xhr, textStatus) {
                    }
                });
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12 text-center">

        </div>
        <div class="col-12 text-center class-inside my-5">
            <button id="btn_logout" class="btn btn-primary"><i class="bi bi-arrow-up"></i> Logout</button>
        </div>
        <div class="col-4 offset-4 text-center class-inside">
            <div id="show_user_info" class="m-5">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td>Tên</td>
                        <td><span id="info_name"></span></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><span id="info_email"></span></td>
                    </tr>
                    <tr>
                        <td>Cách đăng ký</td>
                        <td><span id="info_provider"></span></td>
                    </tr>
                </table>

            </div>
        </div>
        <form class="col-5 offset-3 text-center my-5 class-outside " id="form_register">
            <h2 class="card-title">ĐĂNG KÝ TÀI KHOẢN</h2>
            <div class="row form-control">
                <label class="col-4" for="name">Tên</label>
                <input class="col-6" type="text" name="name" id="name">
            </div>
            <div class="row form-control">
                <label class="col-4" for="email">Email</label>
                <input class="col-6" type="text" name="email" id="email">
            </div>
            <div class="row form-control">
                <label class="col-4" for="password">Mật khẩu</label>
                <input class="col-6" type="text" name="password" id="password">
            </div>
            <div class="row form-control">
                <button class="btn btn-primary" id="btn_create_user">Tạo người dùng</button>
            </div>
        </form>

        <div  class="col-5 offset-3 text-center my-5 ">
            <form class="class-outside" id="form_login">
                <h2 class="card-title my-5">ĐĂNG NHẬP BẰNG TÀI KHOẢN</h2>
                <div class="row m-3">
                    <span class="col-4" ><label for="email_login">Email</label></span>
                    <span class="col-6" ><input placeholder="Email" class="form-control" type="text" name="email_login" id="email_login"></span>
                </div>
                <div class="row m-3">
                    <span class="col-4" ><label for="password_login">Mật khẩu</label></span>
                    <span class="col-6" ><input placeholder="Mật khẩu" class="form-control" type="text" name="password_login" id="password_login"></span>
                </div>
                <div class="row m-3">
                    <span class="col-6 offset-4" >
                        <button class="btn btn-primary form-control" id="btn_login">LOGIN</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">My modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="my_iframe"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send message</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>

