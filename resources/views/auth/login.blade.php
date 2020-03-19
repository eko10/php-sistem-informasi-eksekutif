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
                    <span id="form_result_table"></span>
                    <form id="loginForm" name="loginForm" role="form">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email" class="form-control" placeholder="Email" name="email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Password" name="password">
                        </div>
                        {{-- <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                            <label class="pull-right">
                                <a href="#">Forgotten Password?</a>
                            </label>
                        </div> --}}
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
    <script src="{{asset('admin/assets/js/main.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#loginBtn').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: $('#loginForm').serialize(),
                    url: '/prosesLogin',
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
                            // $('#email').val('');
                            // $('#password').val('');
                            $('#loginBtn').html('Login');
                        }
                        if(data.error_login) {
                            html = '<div class="alert alert-danger">' + data.error_login + '</div>';
                            $('#email').val('');
                            $('#password').val('');
                            $('#loginBtn').html('Login');
                        }
                        if(data.success) {
                            //html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#loginBtn').html('Data ditemukan..');
                            //$('#loginBtn').prop('disabled', true);
                            location.reload(true);
                        }
                        $('#loginBtn').removeAttr('disabled');
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
            });
        });
    </script>

</body>
</html>