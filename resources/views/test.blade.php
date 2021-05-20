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
    <script src="{{ asset('js/sweet_alert.min.js') }}"></script>
    <script src="{{ asset('js/pnotify.min.js') }}"></script>

    <script>
        let registerURL = `http://laravel-08.test/api/auth/register`;
        // let loginURL = `http://laravel-08.test/api/auth/login`;
        let loginURL = `http://laravel-08.test/login`;
        let logoutURL = `http://laravel-08.test/api/logout`;
        let urlCreate = `http://laravel-08.test/api/songs`;
        let currentUserURL = `http://laravel-08.test/api/me`;
        let allUserURL = `http://laravel-08.test/api/users`;
        let allSongURL = `http://laravel-08.test/api/songs`;
        let showSongURL = `http://laravel-08.test/api/songs`;

    </script>
    <script src="{{ asset('js/script.js') }}"></script>
    <style>
        table, th, td {
            border: 1px solid black;
            text-align: center;
        }
        .dang-ky,.dang-nhap{
            position: fixed;
            top:50px;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>

<div class="container">
    <!--chưa login-->
    <div class="class-outside">
        <div class="row">
            <div class="dang-nhap col-5 offset-5 text-center my-5 border border-2">
                <form  id="form_login" method="post">
                    <h2 class="card-title my-5">ĐĂNG NHẬP</h2>
                    <div class="row m-3">
                        <span class="col-4"><label for="email_login">Email</label></span>
                        <span class="col-6"><input placeholder="Email" class="form-control" type="text" name="email_login"
                                                   id="email_login"></span>
                    </div>
                    <div class="row m-3">
                        <span class="col-4"><label for="password_login">Mật khẩu</label></span>
                        <span class="col-6"><input placeholder="Mật khẩu" class="form-control" type="text"
                                                   name="password_login" id="password_login"></span>
                    </div>
                    <div class="row m-3">
                    <span class="col-6 offset-4">
                        <button class="btn btn-primary form-control" id="btn_login">LOGIN</button>
                    </span>
                    </div>
                    <span>Bạn chưa có tài khoản?</span>
                    <div class="row m-3">
                    <span class="col-6 offset-4">
                        <button class="btn btn-primary form-control" id="register">ĐĂNG KÝ</button>
                    </span>
                    </div>
                </form>
            </div>
            <div class="dang-ky col-5 offset-3 text-center my-5 border border-2">
                <form class="text-center my-5" id="form_register" method="POST">
                    <h2 class="card-title">ĐĂNG KÝ TÀI KHOẢN</h2>
                    <div class="row form-group m-3">
                        <label class="col-4" for="name">Tên</label>
                        <input class="col-6" type="text" name="name" id="name">
                    </div>
                    <div class="row form-group m-3">
                        <label class="col-4" for="email">Email</label>
                        <input class="col-6" type="text" name="email" id="email">
                    </div>
                    <div class="row form-group m-3">
                        <label class="col-4" for="password">Mật khẩu</label>
                        <input class="col-6" type="password" name="password" id="password">
                    </div>
                    <div class="row form-group m-3">
                        <label class="col-4" for="password_confirmation">Nhập Lại Mật khẩu</label>
                        <input class="col-6" type="password" name="password_confirmation" id="password_confirmation">
                    </div>
                    <div class="row form-group m-3">
                    <span class="col-6 offset-4">
                        <button class="btn btn-primary btn-sm" id="login">LOGIN</button>
                        <button class="btn btn-primary btn-sm" id="btn_create_user">ĐĂNG KÝ TÀI KHOẢN</button>
                    </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end chưa login-->
    <!--đã login-->
    <div class="class-inside">

        <div class="row">
            <div class="col-3 ">
                <h3 class="m-3">Xin chào</h3>
            </div>
            <div class="col-9">
                <button class="col-3 btn btn-primary btn-sm btn-warning  m-3" id="btn_logout">LOGOUT</button>
            </div>
            <div class="col-3">
                <button class="btn btn-outline-secondary form-control" id="btn_show_info_user">THÔNG TIN CÁ NHÂN
                </button>
                <button class="btn btn-outline-secondary form-control" id="btn_show_list_user">DANH SÁCH USER</button>
                <button class="btn btn-outline-secondary form-control" id="btn_show_list_song">DANH SÁCH BÀI HÁT
                </button>
            </div>
            <div class="col-9">
                <div class="info-user">
                    <p>Name: <span id="info_name"></span></p>

                    <p> Email: <span id="info_email"></span></p>

                </div>
                <div class="list-user">
                    <h3>DANH SÁCH USER</h3>
                    <table id="table-01" style="width:100%">

                    </table>
                </div>
                <div class="list-song">
                    <h3>DANH SÁCH BÀI HÁT</h3>
                    <button class="btn btn-outline-secondary btn_add_song">THÊM BÀI HÁT</button>

                    <table id="table-02" style="width:100%">

                    </table>
                </div>
            </div>

        </div>
    </div>
    <!--end đã login-->
</div>


<!--create modal-->
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
                <form id="create_song" class="form-horizontal" enctype="multipart/form-data" method="POST">
                    <div class="row">
                        <div class="form-group">
                            <label for="name" class="col-lg-3 control-label">NAME <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="name" id="name_song" required="required"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="composer" class="col-lg-3 control-label">composer</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="composer" id="composer"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="singer" class="col-lg-3 control-label">singer</label>
                            <div class="col-lg-9">
                                <input type="text" data-field="date" class="form-control" name="singer"
                                       id="singer"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail" class="col-lg-3 control-label">THUMBNAIL</label>
                            <div class="col-lg-9">
                                <input type="file" class="form-control" name="thumbnail" id="thumbnail"
                                       accept="image/*"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="song_file" class="col-lg-3 control-label">SONG<span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="file" class="form-control" name="song_file" id="song_file"
                                       accept="audio/*" required="required"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lyric" class="col-lg-3 control-label">lyric</label>
                            <div class="col-lg-9">
                                    <textarea class="form-control" name="lyric" id="lyric"
                                    > </textarea>
                            </div>
                        </div>


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

<!--show modal-->
<div class="modal fade" id="show_song_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4>Song Detail</h4>
                <div class="">
                    <p>Name: <span id="song_name"></span></p>
                    <p> Singer: <span id="song_singer"></span></p>
                    <p> Composer: <span id="song_composer"></span></p>
                    <p> Thumbnail: <span id="song_thumbnail"></span></p>
                    <p> URL: <span id="song_url"></span></p>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--edit modal-->
<div id="edit_song_modal" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-info">
                <h4 class="modal-title">EDIT SONG</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="edit_song_form" class="form-horizontal" method="POST">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <input type="hidden" id="song_id_edit" name="song_id_edit">
                        <div class="form-group">
                            <label for="song_name_edit" class="col-lg-3 control-label">NAME <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="song_name_edit" id="song_name_edit"
                                       required="required"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="song_composer_edit" class="col-lg-3 control-label">composer</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="song_composer_edit"
                                       id="song_composer_edit"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="song_singer_edit" class="col-lg-3 control-label">singer</label>
                            <div class="col-lg-9">
                                <input type="text" data-field="date" class="form-control" name="song_singer_edit"
                                       id="song_singer_edit"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">THUMBNAIL</label>
                            <div class="col-lg-9">
                                <p> Thumbnail: <span id="song_old_thumbnail"></span></p>
                                <!--<input type="file" class="form-control" name="song_thumbnail_edit" id="song_thumbnail_edit" accept="image/*"/>-->
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">SONG<span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <p> OLD URL: <span id="song_old_url"></span></p>
                                <!--<input type="file" class="form-control" name="song_url_edit" id="song_url_edit"-->
                                <!--accept="audio/*" />-->

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="song_lyric_edit" class="col-lg-3 control-label">lyric</label>
                            <div class="col-lg-9">
                                    <textarea class="form-control" name="song_lyric_edit" id="song_lyric_edit"
                                    > </textarea>
                            </div>
                        </div>


                    </div>
                    <button type="submit" class="btn btn-block btn-info">CẬP NHẬT THÔNG TIN</button>

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
