$(function () {
    $('.dang-ky').animate({opacity: 0, marginLeft: -100});
    $('#register').click(function (event) {
        event.preventDefault();
        $('.dang-nhap').animate({opacity: 0, marginLeft: -100});
        $('.dang-ky').animate({opacity: 1, marginLeft: 600});
        console.log("nhấn nút 1 nè");
    });
    $('#login').click(function (event) {
        event.preventDefault();
        $('.dang-nhap').animate({opacity: 1, marginLeft: 600});
        $('.dang-ky').animate({opacity: 0, marginLeft: -100});
        console.log("nhấn nút 2 nè");
    });
    let tokenFlag = localStorage.getItem('bearer_token');
    getInfo();

    // $(".class-inside").hide();
    function getCharacterLength(str) {
        return [...str].length;
    };

    function getInfo() {
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            $(".class-outside").hide();
            $(".list-user").hide();
            $(".list-song").hide();
            $.ajax({
                url: currentUserURL,
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
                    // console.log('data: ');
                    // console.log(data);
                    if (data) {
                        $("#info_name").html(data.data.name);
                        $("#info_email").html(data.data.email);
                        $(".list-user").hide();
                        $(".info-user").show();
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

    function template_list_user(id, name, email) {
        var str = "<tr>" +
            "<td>" + id + "</td>" +
            "<td>" + name + "</td>" +
            "<td>" + email + "</td>" +
            "</tr>";
        return str;
    }

    function template_list_song(item) {


        var str = "<tr>" +
            "<td>" +
            "<button  data-id='" + item.id + "'class='btn btn-outline-info btn-show-song'>XEM</button> " +
            "<button data-id='" + item.id + "' class='btn btn-outline-success btn_edit_song' data-toggle='modal' data-target='#edit_admin_modal'>SỬA</button> " +
            "<button data-id='" + item.id + "' class='btn btn-outline-danger btn-delete-song'>XÓA</button>" +
            "</td>" +
            "<td>" + item.id + "</td>" +
            "<td>" + item.name + "</td>" +
            "<td>" + item.composer + "</td>" +
            "<td>" + item.singer + "</td>" +
            "<td>" + item.user.name + "</td>" +
            "</tr>";
        return str;

    }

    function loadListSong() {
        // let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            $(".class-outside").hide();
            $(".info-user").hide();
            $(".list-user").hide();
            $.ajax({
                url: allSongURL,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': 'Bearer ' + tokenFlag
                },
                beforeSend: function () {
                },
                type: 'get',
                dataType: "json",
                contentType: 'application/json',
                cache: false,
                processData: false,
                success: function (data) {
                    // console.log('data: ');
                    // console.log(data.songs);
                    if (data.songs) {
                        $("#table-02").empty();
                        $("#table-02").append("<tr style='background-color: lightblue; margin: 10px 20px'>" +
                            "<th > Thao Tác</th>" +
                            "<th> ID</th>" +
                            "<th>TÊN BÀI HÁT</th>" +
                            "<th>TÁC GIẢ</th>" +
                            "<th>CA SĨ</th>" +
                            // "<th>URL HÌNH ĐẠI DIỆN</th>" +
                            // "<th>LỜI BÀI HÁT</th>" +
                            // "<th>URL BÀI HÁT</th>" +
                            "<th>NGƯỜI TẠO</th>" +
                            "</tr>");

                        $.each(data.songs, function (id, item) {

                            $("#table-02").append(template_list_song(item))
                        });
                        $(".list-song").show();
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
    };
    $("#btn_create_user").on("click", function (e) {
        e.preventDefault();
        let formData = new FormData();
        let password = $("#password").val();
        formData.append('name', $("#name").val());
        formData.append('email', $("#email").val());
        formData.append('password', password);
        formData.append('password_confirmation', password);
        let object = {};
        formData.forEach(function (value, key) {
            object[key] = value;
        });
        let json = JSON.stringify(object);
        $.ajax({
            url: registerURL,
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
                    alert('Đăng ký thành công');
                    $('.dang-nhap').animate({opacity: 1, marginLeft: 600});
                    $('.dang-ky').animate({opacity: 0, marginLeft: -100});
                } else {
                    alert('-->> Đăng ký thất bại !!!');
                }
            },
            error: function (xhr, status, error) {
                console.log('Lỗi: ' + status + " - " + error);
                alert('-->> Đăng ký thất bại !!!');
            },
            complete: function (xhr, textStatus) {
            }
        });
    });// xong
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
            url: loginURL,
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
        // let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            $.ajax({
                url: logoutURL,
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
                    if (data.message === 'Tokens Revoked') {
                        localStorage.setItem('bearer_token', '');
                        $(".class-outside").show();
                        $(".class-inside").hide();
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
    });
    $("#btn_show_info_user").on("click", function (e) {
        e.preventDefault();
        // let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            $.ajax({
                url: currentUserURL,
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
                        $("#info_name").html(data.data.name);
                        $("#info_email").html(data.data.email);
                        $(".list-user").hide();
                        $(".list-song").hide();
                        $(".info-user").show();
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
    });
    $("#btn_show_list_user").on("click", function (e) {
        e.preventDefault();
        // let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            $(".class-outside").hide();
            $(".info-user").hide();
            $(".list-song").hide();
            $.ajax({
                url: allUserURL,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': 'Bearer ' + tokenFlag
                },
                beforeSend: function () {
                },
                type: 'get',
                dataType: "json",
                contentType: 'application/json',
                cache: false,
                processData: false,
                success: function (data) {
                    console.log('data: ');
                    console.log(data);
                    if (data) {
                        $("#table-01").empty();
                        $("#table-01").append("<tr>" +
                            "<th> ID</th>" +
                            "<th>NAME </th>" +
                            "<th>EMAIL</th>" +
                            "</tr>");

                        $.each(data.data, function (id, item) {
                            $("#table-01").append(template_list_user(item.id, item.name, item.email))
                        });
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
        } else {
            $(".class-outside").show();
            $(".class-inside").hide();
        }
    });
    $("#btn_show_list_song").on("click", function (e) {
        e.preventDefault();
        loadListSong();

    });
    $(".btn_add_song").on("click", function (e) {
        $("#create_song_modal").modal('show');// gọi tới modal create mở ra
    });
    /* ajax tAO MOI */
    let createModal = $('#create_song_modal');
    let formCreate = $('#create_song');
    formCreate.submit(function (event) {
        event.preventDefault();
        // let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            let name = $('#name_song').val();
            let composer = $('#composer').val();
            let singer = $('#singer').val();
            let lyric = $('#lyric').val();
            let song_file = $('#song_file').prop('files')[0];
            let formData = new FormData();
            formData.append('name', name);
            formData.append('singer', singer);
            if (typeof $('#thumbnail').prop('files')[0] !== 'undefined') {
                let thumbnail = $('#thumbnail').prop('files')[0];
                console.log(thumbnail);
                formData.append('thumbnail', thumbnail);
            }
            formData.append('composer', composer);
            formData.append('song_file', song_file);
            formData.append('lyric', lyric);
            jQuery.ajax({
                url: urlCreate,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': 'Bearer ' + tokenFlag
                },
                data: formData,
                type: 'POST',
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status_code === 201) {
                        console.log("đã add được bài hát");
                        loadListSong();
                        alert('Thêm bài hát thành công');
                    } else {
                        alert('-->Thêm bài hát thất bại, vui lòng kiểm tra lại');
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Lỗi: ' + status + " - " + error);

                },
                complete: function (xhr, textStatus) {
                    createModal.modal("hide");
                    $('#create_song').trigger("reset");

                }
            });
        } else {
            $(".class-outside").show();
            $(".class-inside").hide();
        }
    });
    let songIDEdit = $('#song_id_edit');
    let songNameEdit = $('#song_name_edit');
    let songSingerEdit = $('#song_singer_edit');
    let songComposerEdit = $('#song_composer_edit');
    let songLyricEdit = $('#song_lyric_edit');
    // let songThumbnailEdit = $('#song_thumbnail_edit');
    // let songUrlEdit = $('#song_url_edit');
    $("#table-02").on('click', '.btn_edit_song', function (e) {
        $("#edit_song_modal").modal('show');
        let myDataId = $(this).data('id');
        console.log("vao day");
        // let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            $.ajax({
                url: `http://laravel-08.test/api/songs/${myDataId}`,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': 'Bearer ' + tokenFlag
                },
                type: 'get',
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (song) {
                    console.log(song);
                    songIDEdit.val(song.song.id);
                    songNameEdit.val(song.song.name);
                    songSingerEdit.val(song.song.singer);
                    songComposerEdit.val(song.song.composer);
                    songLyricEdit.val(song.song.lyric);
                    $("#song_old_thumbnail").html(song.song.thumbnail);
                    $("#song_old_url").html(song.song.url);
                },
                error: function (jqxhr, status, exception) {
                    console.log('Exception: ' + exception);
                },
                complete: function (xhr, textStatus) {
                }
            });
        } else {
            $(".class-outside").show();
            $(".class-inside").hide();
        }

    });
    let updateForm = $('#edit_song_form');
    updateForm.submit(function (event) {
        event.preventDefault();
        let id = songIDEdit.val();
        let name = songNameEdit.val();
        let singer = songSingerEdit.val();
        let composer = songComposerEdit.val();
        let lyric = songLyricEdit.val();
        // let PATCH=
        // let thumbnail = songThumbnailEdit.val();
        // let url = songUrlEdit.val();
        let formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('singer', singer);
        formData.append('composer', composer);
        formData.append('lyric', lyric);
        formData.append('_method', "PATCH");
        // let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            jQuery.ajax({
                url: `http://laravel-08.test/api/songs/${id}`,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': 'Bearer ' + tokenFlag
                },
                data: formData,
                type: 'post',
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if (data.status_code === 200) {
                        console.log("thanh cong");
                        loadListSong();
                        alert('Cập nhật bài hát thành công');
                    } else {
                        alert('-->Cập nhật bài hát thất bại, vui lòng kiểm tra lại');
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Lỗi: ' + status + " - " + error);
                },
                complete: function (xhr, textStatus) {
                    $("#edit_song_modal").modal("hide");
                    updateForm.trigger('reset');

                }
            });
        } else {
            $(".class-outside").show();
            $(".class-inside").hide();
        }
    })
    $("#table-02").on('click', '.btn-show-song', function (e) {
        $("#show_song_modal").modal('show');
        let myDataId = $(this).data('id');
        let ten_bai_hat = $('#ten_bai_hat');
        console.log("vao day");
        // let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            $.ajax({
                url: `http://laravel-08.test/api/songs/${myDataId}`,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': 'Bearer ' + tokenFlag
                },
                type: 'get',
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function (song) {
                    console.log(song);
                    $("#song_name").html(song.song.name);
                    $("#song_singer").html(song.song.singer);
                    $("#song_composer").html(song.song.composer);
                    $("#song_thumbnail").html(song.song.thumbnail);
                    $("#song_url").html(song.song.url);

                },
                error: function (jqxhr, status, exception) {
                    console.log('Exception: ' + exception);

                },
                complete: function (xhr, textStatus) {

                }
            });
        } else {
            $(".class-outside").show();
            $(".class-inside").hide();
        }
    });
    $("#table-02").on('click', '.btn-delete-song', function (e) {
        event.preventDefault();
        let id = $(this).data('id');
        // console.log(id);
        swal({
            title: "Bạn chắc chứ?",
            text: "Dữ liệu bị xóa sẽ không thể phục hồi!\n"
            ,
            type: "warning",
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Vâng, xóa nhé!",
            cancelButtonText: "Không, để xem lại!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            //console.log("chay toi day roi 2");
            if (isConfirm) {

                let formData = new FormData();
                formData.append('id', id);
                formData.append('_method', "DELETE");
                // let tokenFlag = localStorage.getItem('bearer_token');
                if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
                    $.ajaxSetup({
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Authorization': 'Bearer ' + tokenFlag
                        }
                    });
                    $.ajax({
                        url: `http://laravel-08.test/api/songs/${id}`,
                        data: formData,
                        beforeSend: function () {
                        },
                        type: 'POST',
                        dataType: "json",
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.message === "Deleted") {
                                console.log("thanh cong");
                                loadListSong();
                            } else {
                                console.log("that bai");
                            }
                        },
                        error: function (xhr, status, error) {

                        }
                    });
                } else {
                    $(".class-outside").show();
                    $(".class-inside").hide();
                }
            } else {
                swal({
                    title: "Hủy xóa",
                    text: "Kiểm tra lại thông tin trước khi xóa.",
                    confirmButtonColor: "#2196F3",
                    type: "info"
                });
            }
        });
    });
});
