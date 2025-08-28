<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>
        {{ setting('login_title') . ' ' . ucwords(setting('sebutan_desa')) . ($desa['nama_desa'] ? ' ' . $desa['nama_desa'] : '') . get_dynamic_title_page_from_path() }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('css/login-form-elements.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('css/daftar-form-elements.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('css/siteman_mandiri.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.bar.css') }}" media="screen">
    <!-- Pop Up install css -->
    <link rel="stylesheet" href="{{ asset('css/popup-install.css') }}">
    <!-- Manifest json -->
    <link rel="manifest" href="{{ asset('manifest.json') }}"/>
    <!-- bootstrap datetimepicker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datetimepicker.min.css') }}">
    @if (is_file('desa/pengaturan/siteman/siteman_mandiri.css'))
        <link rel="stylesheet" href="{{ base_url('desa/pengaturan/siteman/siteman_mandiri.css') }}">
    @endif
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
    <!-- Google Font -->
    @if (cek_koneksi_internet())
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @endif
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>

    @if ($cek_anjungan)
        <!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
        <link rel="stylesheet" href="{{ asset('css/keyboard.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/mandiri-keyboard.css') }}">
    @endif

    @include('admin.layouts.components.token')

    <style type="text/css">
        body.login {
            background-image: url('{{ default_file(LATAR_LOGIN . setting('latar_login_mandiri'), DEFAULT_LATAR_KEHADIRAN) }}');
        }
    </style>
</head>

<body class="login">
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-4 form-box">
                        <div class="form-top">
                            <a href="{{ base_url('/') }}"><img src="{{ gambar_desa($desa['logo']) }}" alt="Lambang Desa" class="img-responsive" /></a>
                            <div class="login-footer-top">
                                <h1>LAYANAN MANDIRI<br />
                                    {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] }}</h1>
                                <h3>
                                    <br />{{ ucwords(setting('sebutan_kecamatan')) }} {{ $desa['nama_kecamatan'] }}, {{ ucwords(setting('sebutan_kabupaten')) }} {{ $desa['nama_kabupaten'] }}
                                    <br />
                                    <br />{{ $desa['alamat_kantor'] }}
                                    <br />Kodepos {{ $desa['kode_pos'] }}
                                    <br /><br />Silakan hubungi operator desa untuk mendapatkan kode PIN anda.
                                    <br />
                                    <br />{{ $desa['hp_kontak'] }}
                                    <br /><br /><br />IP Address: {{ request()->ip() }}
                                    <br />ID Pengunjung : <span id="pengunjung"></span>&nbsp;<span><a href="#" class="copy" title="Copy" style="color: white"><i class="fa fa-copy"></i></a></span>
                                    @if ($cek_anjungan)
                                        @if ($cek_anjungan['mac_address'])
                                            <br />Mac Address : {{ $cek_anjungan['mac_address'] }}
                                        @endif
                                        <br />Anjungan Mandiri
                                        {!! jecho($cek_anjungan['keyboard'] == 1, true, ' | Virtual Keyboard : Aktif') !!}
                                    @endif
                                </h3>
                            </div>
                        </div>
                        <div class="form-bottom">

                            @php
                                preg_match('/(\d+)/', $errors->first('email'), $matches);

                                $second = $matches[0] ?? 0;
                            @endphp

                            @if ($errors->any())
                                <div @if (!str_contains($errors->first('email'), 'Terlalu banyak upaya masuk.')) id="notif" @endif class="alert alert-danger">
                                    @foreach ($errors->all() as $item)
                                        @if (str_contains($item, 'Terlalu banyak upaya masuk.'))
                                            <p id="countdown">{{ $item }}</p>
                                        @else
                                            <p>{{ $item }}</p>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            @if ($notif = $ci->session->flashdata('notif'))
                                <div id="notif" class="alert alert-danger">
                                    <p>{{ $notif }}</p>
                                </div>
                            @endif

                            @yield('content')

                            <div class="login-footer-bottom">
                                <a href="https://github.com/OpenSID/OpenSID" class="content-color-secondary" rel="noopener noreferrer" target="_blank">OpenSID v<?= AmbilVersi() ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Popup Install Modern -->
    <div id="installPopup" class="install-popup is-hidden">
        <div class="install-content">
            <!-- Ikon SVG APK -->
            <div class="install-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-android2" viewBox="0 0 16 16">
            <path d="m10.213 1.471.691-1.26q.069-.124-.048-.192-.128-.057-.195.058l-.7 1.27A4.8 4.8 0 0 0 8.005.941q-1.032 0-1.956.404l-.7-1.27Q5.281-.037 5.154.02q-.117.069-.049.193l.691 1.259a4.25 4.25 0 0 0-1.673 1.476A3.7 3.7 0 0 0 3.5 5.02h9q0-1.125-.623-2.072a4.27 4.27 0 0 0-1.664-1.476ZM6.22 3.303a.37.37 0 0 1-.267.11.35.35 0 0 1-.263-.11.37.37 0 0 1-.107-.264.37.37 0 0 1 .107-.265.35.35 0 0 1 .263-.11q.155 0 .267.11a.36.36 0 0 1 .112.265.36.36 0 0 1-.112.264m4.101 0a.35.35 0 0 1-.262.11.37.37 0 0 1-.268-.11.36.36 0 0 1-.112-.264q0-.154.112-.265a.37.37 0 0 1 .268-.11q.155 0 .262.11a.37.37 0 0 1 .107.265q0 .153-.107.264M3.5 11.77q0 .441.311.75.311.306.76.307h.758l.01 2.182q0 .414.292.703a.96.96 0 0 0 .7.288.97.97 0 0 0 .71-.288.95.95 0 0 0 .292-.703v-2.182h1.343v2.182q0 .414.292.703a.97.97 0 0 0 .71.288.97.97 0 0 0 .71-.288.95.95 0 0 0 .292-.703v-2.182h.76q.436 0 .749-.308.31-.307.311-.75V5.365h-9zm10.495-6.587a.98.98 0 0 0-.702.278.9.9 0 0 0-.293.685v4.063q0 .406.293.69a.97.97 0 0 0 .702.284q.42 0 .712-.284a.92.92 0 0 0 .293-.69V6.146a.9.9 0 0 0-.293-.685 1 1 0 0 0-.712-.278m-12.702.283a1 1 0 0 1 .712-.283q.41 0 .702.283a.9.9 0 0 1 .293.68v4.063a.93.93 0 0 1-.288.69.97.97 0 0 1-.707.284 1 1 0 0 1-.712-.284.92.92 0 0 1-.293-.69V6.146q0-.396.293-.68"/>
            </svg>
            </div>
            <p class="install-text">Install aplikasi ini di perangkat Anda</p>
            <div class="install-actions">
            <button id="installBtn" class="btn-install">Install sekarang</button>
            <button id="closePopup" class="btn-close">Nanti Saja</button>
            </div>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi_cookie', ['cookie_name' => 'pengunjung'])
    @include('admin.layouts.components.aktifkan_cookie')
    <!-- service worker -->
    <script>navigator.serviceWorker.register("{{ asset('sw.js') }}")</script>
    <!-- Install js -->
    <script src="{{ asset('install.js') }}" defer></script>
    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- bootstrap Moment -->
    <script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
    <!-- bootstrap Date time picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/id.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <!-- Validasi -->
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>

    @if ($cek_anjungan)
        <!-- keyboard widget css & script -->
        <script src="{{ asset('js/jquery.keyboard.min.js') }}"></script>
        <script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ asset('js/jquery.keyboard.extension-all.min.js') }}"></script>
        <script src="{{ asset('front/js/mandiri-keyboard.js') }}"></script>
    @endif
    <script src="{{ asset('js/id_browser.js') }}"></script>
    <script>
        function start_countdown() {
            let totalSeconds = {{ $second }};
            const timer = setInterval(function() {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    location.reload();
                } else {
                    document.getElementById("countdown").innerHTML = `Terlalu banyak upaya masuk. Silahkan coba lagi dalam ${minutes} menit ${seconds} detik.`;
                    totalSeconds--;
                }
            }, 1000);
        }

        $(document).ready(function() {
            if ($('#pin').length) {
                $('#pin').focus();
            } else if ($('#tag').length) {
                $('#tag').focus();
            }

            if ($('#countdown').length) {
                start_countdown();
            }

            window.setTimeout(function() {
                $("#notif").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>

    @stack('script')
</body>

</html>
