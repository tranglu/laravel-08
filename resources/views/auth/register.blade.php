
<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello World</title>
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
                    url: '{{ route('api-fortify-register') }}',
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
                        if (data.status_code === 200) {
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
        });
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <a href="http://laravel-08.test/"><button class="btn btn-primary">TRỞ LẠI</button></a>
        <div  class="col-5 offset-3 text-center my-5 ">
            <form class="text-center my-5 class-outside " id="form_register" action="{{route('register')}}" method="POST">
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
                    <input class="col-6" type="password" name="password" id="password">
                </div>
                <div class="row form-control">
                    <label class="col-4" for="password_confirmation">Nhập Lại Mật khẩu</label>
                    <input class="col-6" type="password" name="password_confirmation" id="password_confirmation">
                </div>
                <div class="row form-control">
                    <button class="btn btn-primary" id="btn_create_user">Tạo người dùng</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
