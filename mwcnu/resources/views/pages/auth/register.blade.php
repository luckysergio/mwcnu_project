<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MWCNU - Register</title>

    
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

    
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .bg-success-custom {
            background: linear-gradient(90deg, #28a745 0%, #218838 100%);
        }

        .form-control-user {
            border-radius: 10rem;
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            z-index: 10;
        }
    </style>
</head>

<body class="bg-success-custom">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.15/dist/sweetalert2.all.min.js"></script>

                        @if(session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: @json(session('success')),
                                    didClose: () => {
                                        window.location.href = "/";
                                    }
                                });
                            </script>
                        @endif

                        @if($errors->any())
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    html: `{!! implode('<br>', $errors->all()) !!}`
                                });
                            </script>
                        @endif

                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block p-5 text-center">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo MWCNU" class="img-fluid"
                                    style="max-height: 300px;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900">Silahkan isi</h1>
                                    </div>
                                    <form class="user" action="/register" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="inputname"
                                                name="name" placeholder="Nama lengkap.." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="inputEmail"
                                                name="email" placeholder="Email..." required>
                                        </div>
                                        <div class="form-group position-relative">
                                            <input type="password" class="form-control form-control-user"
                                                id="inputPassword" name="password" placeholder="Password" required>
                                            <span toggle="#inputPassword" class="fas fa-eye password-toggle"></span>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-user btn-block">
                                            Buat akun
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small text-success" href="/">Login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- JS Assets -->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

    <!-- Password Toggle Script -->
    <script>
        $(document).on('click', '.password-toggle', function () {
            let input = $('#inputPassword');
            let icon = $(this);
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    </script>

</body>

</html>