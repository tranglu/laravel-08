
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
            // $(".class-inside").hide();
            function getCharacterLength(str) {
                return [...str].length;
            }

            function getInfo() {
                let tokenFlag = localStorage.getItem('bearer_token');
                if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
                    $(".class-outside").hide();
                    $(".list-user").hide();
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
                        success: function (data) {
                            console.log('data: ');
                            console.log(data);
                            if (data) {
                                $("#info_name").html(data.name);
                                $("#info_email").html(data.email);
                                $("#info_provider").html(data.provider);
                                $(".class-inside").show();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log('Lỗi: ' + status + " - " + error);
                        },
                        complete: function (xhr, textStatus) {
                        }
                    });
                } else {
                    $(".class-outside").show();
                    $(".class-inside").hide();
                }
            }

            $("#btn_login").on("click", function (e) {
                e.preventDefault();
                let formData = new FormData();
                formData.append('email', $("#email_login").val());
                formData.append('password', $("#password_login").val());
                let object = {};
                formData.forEach(function (value, key) {
                    object[key] = value;
                });
                let json = JSON.stringify(object);
                $.ajax({
                    url: '{{ route('api-fortify-login') }}',
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
                    success: function (data) {
                        console.log('data: ');
                        console.log(data);
                        if (data.status_code === 200) {
                            localStorage.setItem('bearer_token', data.access_token);
                            console.log('bearer_token = ' + data.access_token);
                            $(".class-outside").hide();
                            $(".class-inside").show();
                            getInfo();
                        } else {
                            alert('-->> Đăng nhập thất bại !!!');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log('Lỗi: ' + status + " - " + error);
                        alert('-->> Đăng nhập thất bại !!!');
                    },
                    complete: function (xhr, textStatus) {
                    }
                });
            });
            $("#btn_logout").on("click", function (e) {
                e.preventDefault();
                let tokenFlag = localStorage.getItem('bearer_token');
                $.ajax({
                    url: '{{ route('api-fortify-logout') }}',
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
            $("#btn_show_info_user").on("click", function (e) {
                e.preventDefault();
                let tokenFlag = localStorage.getItem('bearer_token');
                $.ajax({
                    url: '{{ route('api-get-current-user') }}',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Authorization': 'Bearer ' + tokenFlag
                    },
                    beforeSend: function () {
                    },
                    type: 'GET',
                    dataType: "json",
                    contentType: 'application/json',
                    cache: false,
                    processData: false,
                    success: function (data) {
                        console.log('data: ');
                        console.log(data);
                        if (data) {
                            $("#info_name").html(data.name);
                            $("#info_email").html(data.email);
                            $(".list-user").hide();
                            $(".info-user").show();
                        }
                    },
                    error: function(xhr,status,error){
                        console.log('Lỗi: ' + status + " - " + error);
                    },
                    complete: function(xhr, textStatus) {
                    }
                });
            });
            $("#btn_show_list_user").on("click", function (e) {
                e.preventDefault();
                let tokenFlag = localStorage.getItem('bearer_token');
                if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
                    $(".class-outside").hide();
                    $(".info-user").hide();
                    $.ajax({
                        url: '{{ route('api-get-all-user') }}',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Authorization': 'Bearer ' + tokenFlag
                        },
                        beforeSend: function () {
                        },
                        type: 'POST',
                        dataType: "json",
                        contentType: 'application/json',
                        cache: false,
                        processData: false,
                        success: function (data) {
                            console.log('data: ');
                            console.log(data);
                            if (data) {
                                $('table').empty();
                                $('table').append("<tr>" +
                                    "<th> ID</th>" +
                                    "<th>NAME </th>" +
                                    "<th>EMAIL</th>" +
                                    "</tr>");

                                    $.each(data.data, function (id, item) {

                                        console.log(item.id);
                                        console.log(item.name);
                                        console.log(item.email);
                                        $('table').append("<tr>" +
                                            "<td>"+item.id+"</td>" +
                                            "<td>"+item.name+"</td>" +
                                            "<td>"+item.email+"</td>" +
                                            "</tr>");
                                    });



                                // $("#info_name").html(data.name);
                                // $("#info_email").html(data.email);
                                $(".info-user").hide();
                                $(".list-user").show();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log('Lỗi: ' + status + " - " + error);
                        },
                        complete: function (xhr, textStatus) {
                        }
                    });
                }else{
                    $(".class-outside").show();
                    $(".class-inside").hide();
                }
            });
        });
    </script>
    <style>
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<div class="container">

        <div class="class-outside">
            <div  class="col-5 offset-3 text-center my-5 ">
                <form class="class-outside" id="form_login" action="{{route('login')}}">
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
        <div class="class-inside">
            <h3>Xin chào</h3>
            <div class="row">
            <div class="col-3">
                <button class="btn btn-outline-secondary form-control" id="btn_show_info_user">THÔNG TIN CÁ NHÂN</button>
                <button class="btn btn-outline-secondary form-control" id="btn_show_list_user">DANH SÁCH USER</button>
            </div>
            <div class="col-9">
                <div class="info-user">
                    <p>Name: <span id="info_name"></span></p>

                    <p> Email: <span id="info_email"></span></p>

                </div>
                <div class="list-user">
                    <h3>DANH SÁCH USER</h3>
                    <table style="width:100%">
                        {{--<tr>--}}
                            {{--<th>ID</th>--}}
                            {{--<th>Name</th>--}}
                            {{--<th>Email</th>--}}
                        {{--</tr>--}}

                    </table>
                </div>
            </div>
            <button class="btn btn-primary form-control" id="btn_logout">LOGOUT</button>
        </div>
    </div>
</div>

</body>
</html>
