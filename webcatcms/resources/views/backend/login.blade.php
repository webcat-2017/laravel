<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('backend.title_login') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Login css -->
    <link rel="stylesheet" href="{{ mix('css/login.css') }}">
</head>
<body class="hold-transition login-page bg-dark">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card bg-dark">
        <div class="card-header text-center bg-dark">
            <a href="#" class="h1"><b class="text-secondary">WEBCAT</b><span class="text-lg">CMS</span></a>
        </div>
        <div class="card-body bg-dark">
            <p class="login-box-msg text-secondary">{{ __('backend.title_login') }}</p>

            <form id="login_form" action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control bg-dark border-secondary" placeholder="{{__('backend.placeholder_email')}}">
                    <div class="input-group-append">
                        <div class="input-group-text bg-dark border-secondary">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" class="form-control bg-dark border-secondary" placeholder="{{__('backend.placeholder_password')}}">
                    <div class="input-group-append">
                        <div class="input-group-text bg-dark border-secondary">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-secondary  text-secondary text-sm">
                            <input type="checkbox" id="remember">
                            <label for="remember" >
                                {{__('backend.remember_me')}}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-block border-secondary text-secondary">{{__('backend.login_btn')}}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->
<script src="{{ mix('js/login.js') }}"></script>
<script>
    $(function () {
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif

        $('#login_form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: {{$length_password}}
                },
                terms: {
                    required: true
                },
            },
            messages: {
                email: {
                    required: "{{__('validation.please_enter_email')}}",
                    email: "{{__('validation.please_vaild_email')}}"
                },
                password: {
                    required: "{{__('validation.please_enter_password')}}",
                    minlength: "{{__('validation.please_vaild_password', ['length' => $length_password])}}"
                },
                terms: "Please accept our terms"
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.input-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
</body>
</html>
