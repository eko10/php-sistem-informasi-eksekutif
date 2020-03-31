<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | Sistem Informasi Eksekutif</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{asset('admin/assets/css/cs-skin-elastic.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    {{-- <style>
        .error { color:red; } 
    </style> --}}
</head>
<body class="bg-dark">
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="{{ route('login') }}">
                        <img class="align-content" src="{{asset('admin/images/logo.png')}}" alt="">
                    </a>
                </div>
                <div class="login-form">
                    <p class="text-center">
                        <b>Login Admin</b><br>
                        Username : admin@itslogistik.com, Password : 123456
                        <br>
                        <b>Login Eksekutif</b><br>
                        Username : eksekutif@itslogistik.com, Password : 123456
                    </p>
                    <span id="form_result_table"></span>
                    <form id="loginForm" name="loginForm" role="form" method="POST" action="javascript:void(0)">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email" class="form-control" placeholder="Email" name="email">
                            <span class="help-block">{{ $errors->first('email') }}</span>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Password" name="password">
                            <span class="help-block">
                                <p style="text-transform: uppercase; color: #ffffff;">
                                    {{ $errors->first('password') }}</span>
                                </p>
                        </div>
                        <button type="submit" id="loginBtn" class="btn btn-success btn-flat m-b-30 m-t-30">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="{{asset('admin/assets/js/main.js')}}"></script>
    <script>
        $(document).ready(function(){
            if ($("#loginForm").length > 0) {
                $("#loginForm").validate({
                    rules: {
                        email: {
                            required: true,
                            maxlength: 50,
                            email: true,
                        },
                        password: {
                            required: true,
                            minlength: 6
                        },   
                    },
                    messages: { 
                        email: {
                            required: "Email harus diisi!",
                            email: "Email tidak valid!",
                            maxlength: "Email maksimal 50 karakter!",
                        },
                        password: {
                            required: "Password harus diisi!",
                            minlength: "Password minimal 6 karakter!"
                        }, 
                    },
                    submitHandler: function(form) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        //$('#loginBtn').click(function (e) {
                            //e.preventDefault();
                        $.ajax({
                            data: $('#loginForm').serialize(),
                            url: '{{ route("prosesLogin") }}',
                            type: 'POST',
                            dataType: 'json',
                            beforeSend: function() {
                                $('#loginBtn').html('Proses..');
                                $('#loginBtn').prop('disabled', true);
                            },
                            success: function(data) {
                                var html = '';
                                if(data.errors) {
                                    html = '<div class="alert alert-danger">';
                                    for(var count = 0; count < data.errors.length; count++) {
                                        html += data.errors[count] + '<br>';
                                    }
                                    html += '</div>';
                                    $('#loginBtn').removeAttr('disabled');
                                    $('#loginBtn').html('Login');
                                }
                                if(data.error_login) {
                                    html = '<div class="alert alert-danger">' + data.error_login + '</div>';
                                    $('#email').val('');
                                    $('#password').val('');
                                    $('#loginBtn').removeAttr('disabled');
                                    $('#loginBtn').html('Login');
                                }
                                if(data.success) {
                                    $('#loginBtn').html('Data ditemukan..');
                                    location.reload(true);
                                }
                                $('#form_result_table').html(html);
                            },
                            error: function(xhr, status, error) {
                                let json = JSON.parse(xhr.responseText);
                                let message = json.message;
                                html = '<div class="alert alert-danger">' + message + '.</div>';
                                $('#loginBtn').removeAttr('disabled');
                                $('#loginBtn').html('Login');
                                $('#form_result_table').html(html);
                            }
                        });
                        //});
                    }
                });
            }   
        });
    </script>
</body>
</html>