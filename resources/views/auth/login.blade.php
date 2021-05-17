
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
        let loginURL = '<?php echo e(route('api-login')); ?>';
        let logoutURL = '<?php echo e(route('api-auth-logout')); ?>';
        let currentUserURL = '<?php echo e(route('api-get-current-user')); ?>';
        let allUserURL = '<?php echo e(route('api-get-all-user')); ?>';
        let allSongURL = '<?php echo e(route('songs.index')); ?>';
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
    <style>
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<div class="container">
        {{--chưa login--}}
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
        {{--end chưa login--}}
         {{--đã login--}}
        <div class="class-inside">
            <h3>Xin chào</h3>
            <div class="row">
            <div class="col-3">
                <button class="btn btn-outline-secondary form-control" id="btn_show_info_user">THÔNG TIN CÁ NHÂN</button>
                <button class="btn btn-outline-secondary form-control" id="btn_show_list_user">DANH SÁCH USER</button>
                <button class="btn btn-outline-secondary form-control" id="btn_show_list_song">DANH SÁCH BÀI HÁT</button>
            </div>
            <div class="col-9">
                <div class="info-user">
                    <p>Name: <span id="info_name"></span></p>

                    <p> Email: <span id="info_email"></span></p>

                </div>
                <div class="list-user">
                    <h3>DANH SÁCH USER</h3>
                    <table style="width:100%">

                    </table>
                </div>
                <div class="list-song">
                    <h3>DANH SÁCH BÀI HÁT</h3>
                    <button class="btn btn-outline-secondary" id="btn_add_song">THÊM BÀI HÁT</button>
                    <table style="width:100%">

                    </table>
                </div>
            </div>
            <button class="btn btn-primary form-control" id="btn_logout">LOGOUT</button>
        </div>
    </div>
        {{--end đã login--}}
</div>


{{--create modal--}}
<div id="create_song_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-info">
                <h4 class="modal-title">CREATE</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="create_song" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{route('songs.store')}}" id="create_song_url"
                           name="create_song_url"/>
                    <div class="row">
                        <div class="form-group">
                            <label for="name" class="col-lg-3 control-label">NAME <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="name" id="name" required="required"
                                />
                            </div>
                        </div>
                        {{--NAME--}}
                        <div class="form-group">
                            <label for="composer" class="col-lg-3 control-label">composer</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="composer" id="composer"
                                />
                            </div>
                        </div>
                        {{--COMPOSER--}}
                        <div class="form-group">
                            <label for="singer" class="col-lg-3 control-label">singer</label>
                            <div class="col-lg-9">
                                <input type="text" data-field="date" class="form-control" name="singer"
                                       id="singer"
                                />
                            </div>
                        </div>
                        {{--SINGER--}}
                        <div class="form-group">
                            <label for="thumbnail" class="col-lg-3 control-label">THUMBNAIL</label>
                            <div class="col-lg-9">
                                <input type="file" class="form-control" name="thumbnail" id="thumbnail"
                                       accept="image/*"
                                />
                            </div>
                        </div>
                        {{--THUMBNAIL--}}
                        <div class="form-group">
                            <label for="song_file" class="col-lg-3 control-label">SONG<span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="file" class="form-control" name="song_file" id="song_file"
                                       accept="audio/*" required="required"
                                />
                            </div>
                        </div>
                        {{--FILE MP3--}}
                        <div class="form-group">
                            <label for="lyric" class="col-lg-3 control-label">lyric</label>
                            <div class="col-lg-9">
                                    <textarea class="form-control" name="lyric" id="lyric"
                                    > </textarea>
                            </div>
                        </div>
                        {{--lyric--}}

                    </div>
                    <button type="submit" class="btn btn-block btn-success">CREATE</button>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
