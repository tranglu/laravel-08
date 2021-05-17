$(function() {
    // getInfo();
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
                    console.log('data: ');
                    console.log(data);
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
        let tokenFlag = localStorage.getItem('bearer_token');
        $.ajax({
            url: logoutURL,
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
    $("#btn_show_list_song").on("click", function (e) {
        e.preventDefault();
        let tokenFlag = localStorage.getItem('bearer_token');
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
                    console.log('data: ');
                    console.log(data.songs);
                    if (data.songs) {
                        $('table').empty();
                        $('table').append("<tr>" +
                            "<th> ID</th>" +
                            "<th>TÊN BÀI HÁT</th>" +
                            "<th>TÁC GIẢ</th>" +
                            "<th>CA SĨ</th>" +
                            "<th>URL HÌNH ĐẠI DIỆN</th>" +
                            "<th>LỜI BÀI HÁT</th>" +
                            "<th>URL BÀI HÁT</th>" +
                            "<th>NGƯỜI TẠO</th>" +
                            "</tr>");

                        $.each(data.songs, function (id, item) {

                            $('table').append("<tr>" +
                                "<td>"+item.id+"</td>" +
                                "<td>"+item.name+"</td>" +
                                "<td>"+item.composer+"</td>" +
                                "<td>"+item.singer+"</td>" +
                                "<td>"+item.thumbnail+"</td>" +
                                "<td>"+item.lyric+"</td>" +
                                "<td>"+item.url+"</td>" +
                                "<td>"+item.user.name+"</td>" +
                                "</tr>");
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
        }else{
            $(".class-outside").show();
            $(".class-inside").hide();
        }
    });
    $("#btn_add_song").on("click", function (e) {
            $("#create_song_modal").modal('show');// gọi tới modal create mở ra
    });
    /* ajax tAO MOI */
    let createModal = $('#create_song_modal');
    let formCreate = $('#create_song');
    let urlCreate = $('#create_song_url').val();
    formCreate.submit(function (event) {
        event.preventDefault();
        let tokenFlag = localStorage.getItem('bearer_token');
        if (tokenFlag != null && getCharacterLength(tokenFlag) > 0) {
            let name = $('#name').val();
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
                    if (data.status === 201) {
                        console.log("đã add được bài hát");
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
        }
    });
});