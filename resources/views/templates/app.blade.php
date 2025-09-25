<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Metas -->
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ url('/myhr/images/logo.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ url('/myhr/images/logo.png') }}" />
    <!-- Font -->
    <link rel="stylesheet" href="{{ url('/myhr/fonts/fonts.css') }}" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{ url('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ url('/myhr/fonts/icons-alipay.css') }}">
    <link rel="stylesheet" href="{{ url('/myhr/styles/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('/myhr/styles/swiper-bundle.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/myhr/styles/styles.css') }}" />
    <link rel="manifest" href="{{ url('/myhr/_manifest.json') }}" data-pwa-version="set_in_manifest_and_pwa_js">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ url('/myhr/app/icons/icon-192x192.png') }}">
    <link rel="stylesheet" href="{{ url('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ url('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('https://unpkg.com/leaflet@1.8.0/dist/leaflet.css') }}"
        integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
        crossorigin="" />
    <script src="{{ url('https://unpkg.com/leaflet@1.8.0/dist/leaflet.js') }}"
        integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
        crossorigin=""></script>
    <link rel="stylesheet" type="text/css" href="{{ url('clock/dist/bootstrap-clockpicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/custom.css') }}">
    <style>
    .select2-container .select2-selection--single {
        height: 45px;
        line-height: 45px;
    }

    .select2-container .select2-selecphption--single .select2-selection__rendered {
        line-height: 45px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }

    .select2-results__option {
        line-height: 45px;
    }

    .select2-selection__choice {
        line-height: 45px;
    }
    </style>
    @stack('style')
</head>

<body>
    <div class="preload preload-container">
        <div class="preload-logo"></div>
    </div>

    @if (Request::is('dashboard*'))
    <div class="app-header">
        <div class="tf-container">
            <div class="tf-topbar d-flex justify-content-between align-items-center">
                <a class="user-info d-flex justify-content-between align-items-center" href="{{ url('/my-profile') }}">
                    @if(auth()->user()->foto_karyawan == null)
                    <img src="{{ url('assets/img/foto_default.jpg') }}" alt="image">
                    @else
                    <img src="{{ url('/storage/'.auth()->user()->foto_karyawan) }}" alt="image">
                    @endif

                    <div class="content">
                        <h4 class="white_color">{{ auth()->user()->name }}</h4>
                        <p class="white_color fw_4">{{ auth()->user()->Jabatan->nama_jabatan }}</p>
                    </div>
                </a>
                <div class="d-flex align-items-center gap-4">
                    <a href="javascript:void(0);" id="btn-popup-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path
                                d="M7.25687 5.89462C8.06884 5.35208 9.02346 5.0625 10 5.0625C11.3095 5.0625 12.5654 5.5827 13.4913 6.50866C14.4173 7.43462 14.9375 8.6905 14.9375 10C14.9375 10.9765 14.6479 11.9312 14.1054 12.7431C13.5628 13.5551 12.7917 14.188 11.8895 14.5617C10.9873 14.9354 9.99452 15.0331 9.03674 14.8426C8.07896 14.6521 7.19918 14.1819 6.50866 13.4913C5.81814 12.8008 5.34789 11.921 5.15737 10.9633C4.96686 10.0055 5.06464 9.01271 5.43835 8.1105C5.81205 7.20829 6.44491 6.43716 7.25687 5.89462ZM8.29857 12.5464C8.80219 12.8829 9.3943 13.0625 10 13.0625C10.8122 13.0625 11.5912 12.7398 12.1655 12.1655C12.7398 11.5912 13.0625 10.8122 13.0625 10C13.0625 9.3943 12.8829 8.80219 12.5464 8.29857C12.2099 7.79494 11.7316 7.40241 11.172 7.17062C10.6124 6.93883 9.99661 6.87818 9.40254 6.99635C8.80847 7.11451 8.26279 7.40619 7.83449 7.83449C7.40619 8.26279 7.11451 8.80847 6.99635 9.40254C6.87818 9.99661 6.93883 10.6124 7.17062 11.172C7.40241 11.7316 7.79494 12.2099 8.29857 12.5464ZM24.7431 14.1054C23.9312 14.6479 22.9765 14.9375 22 14.9375C20.6905 14.9375 19.4346 14.4173 18.5087 13.4913C17.5827 12.5654 17.0625 11.3095 17.0625 10C17.0625 9.02346 17.3521 8.06884 17.8946 7.25687C18.4372 6.44491 19.2083 5.81205 20.1105 5.43835C21.0127 5.06464 22.0055 4.96686 22.9633 5.15737C23.921 5.34789 24.8008 5.81814 25.4913 6.50866C26.1819 7.19918 26.6521 8.07896 26.8426 9.03674C27.0331 9.99452 26.9354 10.9873 26.5617 11.8895C26.1879 12.7917 25.5551 13.5628 24.7431 14.1054ZM23.7014 7.45363C23.1978 7.11712 22.6057 6.9375 22 6.9375C21.1878 6.9375 20.4088 7.26016 19.8345 7.83449C19.2602 8.40882 18.9375 9.18778 18.9375 10C18.9375 10.6057 19.1171 11.1978 19.4536 11.7014C19.7901 12.2051 20.2684 12.5976 20.828 12.8294C21.3876 13.0612 22.0034 13.1218 22.5975 13.0037C23.1915 12.8855 23.7372 12.5938 24.1655 12.1655C24.5938 11.7372 24.8855 11.1915 25.0037 10.5975C25.1218 10.0034 25.0612 9.38763 24.8294 8.82803C24.5976 8.26844 24.2051 7.79014 23.7014 7.45363ZM7.25687 17.8946C8.06884 17.3521 9.02346 17.0625 10 17.0625C11.3095 17.0625 12.5654 17.5827 13.4913 18.5087C14.4173 19.4346 14.9375 20.6905 14.9375 22C14.9375 22.9765 14.6479 23.9312 14.1054 24.7431C13.5628 25.5551 12.7917 26.1879 11.8895 26.5617C10.9873 26.9354 9.99452 27.0331 9.03674 26.8426C8.07896 26.6521 7.19918 26.1819 6.50866 25.4913C5.81814 24.8008 5.34789 23.921 5.15737 22.9633C4.96686 22.0055 5.06464 21.0127 5.43835 20.1105C5.81205 19.2083 6.44491 18.4372 7.25687 17.8946ZM8.29857 24.5464C8.80219 24.8829 9.3943 25.0625 10 25.0625C10.8122 25.0625 11.5912 24.7398 12.1655 24.1655C12.7398 23.5912 13.0625 22.8122 13.0625 22C13.0625 21.3943 12.8829 20.8022 12.5464 20.2986C12.2099 19.7949 11.7316 19.4024 11.172 19.1706C10.6124 18.9388 9.99661 18.8782 9.40254 18.9963C8.80847 19.1145 8.26279 19.4062 7.83449 19.8345C7.40619 20.2628 7.11451 20.8085 6.99635 21.4025C6.87818 21.9966 6.93883 22.6124 7.17062 23.172C7.40241 23.7316 7.79494 24.2099 8.29857 24.5464ZM19.2569 17.8946C20.0688 17.3521 21.0235 17.0625 22 17.0625C23.3095 17.0625 24.5654 17.5827 25.4913 18.5087C26.4173 19.4346 26.9375 20.6905 26.9375 22C26.9375 22.9765 26.6479 23.9312 26.1054 24.7431C25.5628 25.5551 24.7917 26.1879 23.8895 26.5617C22.9873 26.9354 21.9945 27.0331 21.0367 26.8426C20.079 26.6521 19.1992 26.1819 18.5087 25.4913C17.8181 24.8008 17.3479 23.921 17.1574 22.9633C16.9669 22.0055 17.0646 21.0127 17.4383 20.1105C17.8121 19.2083 18.4449 18.4372 19.2569 17.8946ZM20.2986 24.5464C20.8022 24.8829 21.3943 25.0625 22 25.0625C22.8122 25.0625 23.5912 24.7398 24.1655 24.1655C24.7398 23.5912 25.0625 22.8122 25.0625 22C25.0625 21.3943 24.8829 20.8022 24.5464 20.2986C24.2099 19.7949 23.7316 19.4024 23.172 19.1706C22.6124 18.9388 21.9966 18.8782 21.4025 18.9963C20.8085 19.1145 20.2628 19.4062 19.8345 19.8345C19.4062 20.2628 19.1145 20.8085 18.9963 21.4025C18.8782 21.9966 18.9388 22.6124 19.1706 23.172C19.4024 23.7316 19.7949 24.2099 20.2986 24.5464Z"
                                fill="white" stroke="white" stroke-width="0.125" />
                        </svg>
                    </a>
                    <a href="{{ url('/notifications') }}" class="icon-notification1">
                        @if (auth()->user()->notifications()->whereNull('read_at')->count() > 0)
                        <span>{{ auth()->user()->notifications()->whereNull('read_at')->count() }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="app-header st1">
        <div class="tf-container">
            <div class="tf-topbar d-flex justify-content-center align-items-center">
                <a href="/dashboard" class="back-btn2">
                    <i class="icon-left white_color"></i>
                </a>
                <h3 class="white_color">{{ $title }}</h3>
            </div>
            <div class="tf-topbar d-flex justify-content-between align-items-center">
                <a class="user-info d-flex justify-content-between align-items-center"></a>
                <div class="d-flex align-items-center gap-4">
                    <a href="javascript:void(0);" id="btn-popup-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path
                                d="M7.25687 5.89462C8.06884 5.35208 9.02346 5.0625 10 5.0625C11.3095 5.0625 12.5654 5.5827 13.4913 6.50866C14.4173 7.43462 14.9375 8.6905 14.9375 10C14.9375 10.9765 14.6479 11.9312 14.1054 12.7431C13.5628 13.5551 12.7917 14.188 11.8895 14.5617C10.9873 14.9354 9.99452 15.0331 9.03674 14.8426C8.07896 14.6521 7.19918 14.1819 6.50866 13.4913C5.81814 12.8008 5.34789 11.921 5.15737 10.9633C4.96686 10.0055 5.06464 9.01271 5.43835 8.1105C5.81205 7.20829 6.44491 6.43716 7.25687 5.89462ZM8.29857 12.5464C8.80219 12.8829 9.3943 13.0625 10 13.0625C10.8122 13.0625 11.5912 12.7398 12.1655 12.1655C12.7398 11.5912 13.0625 10.8122 13.0625 10C13.0625 9.3943 12.8829 8.80219 12.5464 8.29857C12.2099 7.79494 11.7316 7.40241 11.172 7.17062C10.6124 6.93883 9.99661 6.87818 9.40254 6.99635C8.80847 7.11451 8.26279 7.40619 7.83449 7.83449C7.40619 8.26279 7.11451 8.80847 6.99635 9.40254C6.87818 9.99661 6.93883 10.6124 7.17062 11.172C7.40241 11.7316 7.79494 12.2099 8.29857 12.5464ZM24.7431 14.1054C23.9312 14.6479 22.9765 14.9375 22 14.9375C20.6905 14.9375 19.4346 14.4173 18.5087 13.4913C17.5827 12.5654 17.0625 11.3095 17.0625 10C17.0625 9.02346 17.3521 8.06884 17.8946 7.25687C18.4372 6.44491 19.2083 5.81205 20.1105 5.43835C21.0127 5.06464 22.0055 4.96686 22.9633 5.15737C23.921 5.34789 24.8008 5.81814 25.4913 6.50866C26.1819 7.19918 26.6521 8.07896 26.8426 9.03674C27.0331 9.99452 26.9354 10.9873 26.5617 11.8895C26.1879 12.7917 25.5551 13.5628 24.7431 14.1054ZM23.7014 7.45363C23.1978 7.11712 22.6057 6.9375 22 6.9375C21.1878 6.9375 20.4088 7.26016 19.8345 7.83449C19.2602 8.40882 18.9375 9.18778 18.9375 10C18.9375 10.6057 19.1171 11.1978 19.4536 11.7014C19.7901 12.2051 20.2684 12.5976 20.828 12.8294C21.3876 13.0612 22.0034 13.1218 22.5975 13.0037C23.1915 12.8855 23.7372 12.5938 24.1655 12.1655C24.5938 11.7372 24.8855 11.1915 25.0037 10.5975C25.1218 10.0034 25.0612 9.38763 24.8294 8.82803C24.5976 8.26844 24.2051 7.79014 23.7014 7.45363ZM7.25687 17.8946C8.06884 17.3521 9.02346 17.0625 10 17.0625C11.3095 17.0625 12.5654 17.5827 13.4913 18.5087C14.4173 19.4346 14.9375 20.6905 14.9375 22C14.9375 22.9765 14.6479 23.9312 14.1054 24.7431C13.5628 25.5551 12.7917 26.1879 11.8895 26.5617C10.9873 26.9354 9.99452 27.0331 9.03674 26.8426C8.07896 26.6521 7.19918 26.1819 6.50866 25.4913C5.81814 24.8008 5.34789 23.921 5.15737 22.9633C4.96686 22.0055 5.06464 21.0127 5.43835 20.1105C5.81205 19.2083 6.44491 18.4372 7.25687 17.8946ZM8.29857 24.5464C8.80219 24.8829 9.3943 25.0625 10 25.0625C10.8122 25.0625 11.5912 24.7398 12.1655 24.1655C12.7398 23.5912 13.0625 22.8122 13.0625 22C13.0625 21.3943 12.8829 20.8022 12.5464 20.2986C12.2099 19.7949 11.7316 19.4024 11.172 19.1706C10.6124 18.9388 9.99661 18.8782 9.40254 18.9963C8.80847 19.1145 8.26279 19.4062 7.83449 19.8345C7.40619 20.2628 7.11451 20.8085 6.99635 21.4025C6.87818 21.9966 6.93883 22.6124 7.17062 23.172C7.40241 23.7316 7.79494 24.2099 8.29857 24.5464ZM19.2569 17.8946C20.0688 17.3521 21.0235 17.0625 22 17.0625C23.3095 17.0625 24.5654 17.5827 25.4913 18.5087C26.4173 19.4346 26.9375 20.6905 26.9375 22C26.9375 22.9765 26.6479 23.9312 26.1054 24.7431C25.5628 25.5551 24.7917 26.1879 23.8895 26.5617C22.9873 26.9354 21.9945 27.0331 21.0367 26.8426C20.079 26.6521 19.1992 26.1819 18.5087 25.4913C17.8181 24.8008 17.3479 23.921 17.1574 22.9633C16.9669 22.0055 17.0646 21.0127 17.4383 20.1105C17.8121 19.2083 18.4449 18.4372 19.2569 17.8946ZM20.2986 24.5464C20.8022 24.8829 21.3943 25.0625 22 25.0625C22.8122 25.0625 23.5912 24.7398 24.1655 24.1655C24.7398 23.5912 25.0625 22.8122 25.0625 22C25.0625 21.3943 24.8829 20.8022 24.5464 20.2986C24.2099 19.7949 23.7316 19.4024 23.172 19.1706C22.6124 18.9388 21.9966 18.8782 21.4025 18.9963C20.8085 19.1145 20.2628 19.4062 19.8345 19.8345C19.4062 20.2628 19.1145 20.8085 18.9963 21.4025C18.8782 21.9966 18.9388 22.6124 19.1706 23.172C19.4024 23.7316 19.7949 24.2099 20.2986 24.5464Z"
                                fill="white" stroke="white" stroke-width="0.125" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @yield('container')

    <div class="bottom-navigation-bar">
        <div class="tf-container">
            <ul class="tf-navigation-bar">
                <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
                    <form id="beranda" action="{{ url('/clear-form-session') }}" method="POST">
                        @csrf
                        <a class="btn fw_6 d-flex justify-content-center align-items-center flex-column"
                            href="javascript:$('#beranda').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="22"
                                version="1.1"
                                style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                viewBox="0 0 500.97 501.15" xmlns:xlink="http://www.w3.org/1999/xlink"
                                xmlns:xodm="http://www.corel.com/coreldraw/odm/2003">
                                <defs>
                                    <style type="text/css">
                                    <![CDATA[
                                    .fil1 {
                                        fill: #403DA9
                                    }

                                    .fil0 {
                                        fill: #533DEA
                                    }
                                    ]]>
                                    </style>
                                </defs>
                                <g id="Layer_x0020_1">
                                    <metadata id="CorelCorpID_0Corel-Layer" />
                                    <g id="_1844892475952">
                                        <path class="fil0"
                                            d="M-0 249.55c0,13.44 7.44,19.01 20.13,19.01 9.07,0 25.11,-18.96 31.03,-24.88l199.33 -199.89c5.88,3.93 106.17,105.66 114.34,113.78 5.11,5.08 8.28,9.42 13.42,14.54 5.13,5.11 9.42,9.42 14.54,14.54 9.78,9.78 18.74,18.74 28.52,28.52l42.49 42.49c6.92,6.93 9.11,10.9 20.41,10.9 10.12,0 16.77,-7.78 16.77,-19.01 0,-10.82 -16.94,-24.21 -27.12,-34.39 -22.38,-22.38 -202.66,-204.06 -211.03,-210.55 -18.09,-14.02 -32.1,6.94 -47.85,22.69 -10.71,10.71 -20.6,20.6 -31.31,31.31 -14.01,14.01 -173.74,172.62 -179.24,179.71 -2.41,3.12 -4.43,5.48 -4.43,11.23z" />
                                        <path class="fil1"
                                            d="M53.68 302.11l0 116.3c0,15.11 7.66,33.7 16.12,45.39 14.52,20.07 41.17,37.36 66.63,37.36l228.12 0c14.36,0 34.52,-7.88 45.39,-16.11 7.63,-5.78 15.14,-12.99 20.76,-20.61 8.71,-11.8 16.59,-30.6 16.59,-46.03l0 -116.3c0,-19.01 -33.89,-22.55 -35.79,-0l0 93.94c0,24.32 -1.15,36.46 -12.63,50 -9.23,10.88 -23.58,19.33 -43.29,19.33l-210.23 0c-19.71,0 -34.06,-8.46 -43.29,-19.33 -11.48,-13.53 -12.63,-25.67 -12.63,-50l0.01 -93.94c-1.9,-22.55 -35.79,-19.01 -35.79,0z" />
                                    </g>
                                </g>
                            </svg>
                            <h5>Beranda</h5>
                        </a>
                    </form>
                </li>
                <li class="{{ Request::is('my-absen*') ? 'active' : '' }}">
                    <a class="fw_4 d-flex justify-content-center align-items-center flex-column"
                        href="{{ url('/my-absen') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="22"
                            version="1.1"
                            style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                            viewBox="0 0 632.61 580.26" xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:xodm="http://www.corel.com/coreldraw/odm/2003">
                            <defs>
                                <style type="text/css">
                                <![CDATA[
                                .fil0 {
                                    fill: #533DEA
                                }
                                ]]>
                                </style>
                            </defs>
                            <g id="Layer_x0020_1">
                                <metadata id="CorelCorpID_0Corel-Layer" />
                                <g id="_1845734787344">
                                    <path class="fil0"
                                        d="M119.04 234.61zm0 0zm0 0c0.47,-21.03 23.43,-60.47 36.54,-79.5l26.71 -31.31c107.93,-99.76 275.31,-84.5 357.95,38.96 31.77,47.47 50.29,113.87 31.85,187.89 -15.55,62.42 -52.15,106.88 -104.93,141.96 -3.43,2.28 -5.26,3.27 -9.46,5.35 -24.7,12.22 -35,17.57 -64.9,23.98 -66.84,14.32 -140.52,-3.95 -191.9,-45.76l-23.54 -20.89c-2.94,-3.18 -3.54,-4.8 -6.77,-8.05 -3.23,-3.25 -3.99,-4.54 -6.82,-7.99 -4.47,-5.46 -8.32,-11.81 -12.89,-16.74 -4.56,-4.93 -11.66,-8.36 -19.5,-8.31 -15.71,0.11 -28.84,13.16 -25.65,32.47 1.9,11.49 29.63,41.85 37.7,49.95 3,3.01 4.5,4.64 7.72,7.1 14.04,10.71 18.19,18.12 41.12,31.71 51.67,30.6 93.27,44.49 160.1,44.83 64.84,0.33 145.08,-32.76 187.31,-75.62 3.35,-3.4 4.47,-4.02 7.93,-6.88 5.85,-4.85 27.97,-32.6 33.65,-40.41 14.46,-19.91 31.95,-54.15 38.71,-78.56 11.81,-42.62 12.71,-58.25 12.65,-104.71 -0.06,-42.92 -19.41,-97.59 -41.16,-134.13 -13.55,-22.76 -21.31,-31.07 -37.59,-48.82 -35.04,-38.21 -77.4,-62.92 -127.29,-78.86 -99.7,-31.86 -211.39,0.53 -283.34,72.48 -15.73,15.73 -27.71,30.05 -39.52,49.36 -11.4,18.64 -33.48,60.45 -35.28,82 -0.88,-1.01 -1.17,-1.32 -2.21,-2.73l-6.43 -9.62c-9.64,-14.46 -15.38,-28.61 -33.47,-29.19 -19.25,-0.61 -30.13,18.78 -25.07,35.89 1.39,4.7 46.83,72.56 57.8,89.1 18.6,28.03 34.9,16.4 53.58,3.94l75.2 -50.71c29.63,-24.89 -3.91,-61.04 -30.31,-43.89 -4.4,2.86 -8.34,5.64 -12.55,8.43 -8.63,5.72 -16.55,12.33 -25.92,17.29z" />
                                    <path class="fil0"
                                        d="M316.55 153.14l0 140.73c0,10.07 15.31,23.33 21.29,29.32 8.94,8.94 16.98,16.98 25.92,25.92 8.94,8.94 16.98,16.98 25.92,25.92 11.68,11.68 16.15,21.29 34.26,21.29 7.31,0 13.91,-4.29 17.62,-8.3 21.99,-23.77 -10.79,-47.21 -30.27,-66.69l-31.48 -31.48c-15.45,-15.45 -10.18,-8.54 -10.18,-54.01 0,-14.04 1.39,-77.18 -1,-85.42 -9.53,-32.97 -52.09,-19.2 -52.09,2.71z" />
                                </g>
                            </g>
                        </svg>
                        <h5>History Absen</h5>
                    </a>
                </li>
                <li>
                    <a class="fw_4 d-flex justify-content-center align-items-center flex-column"
                        href="{{ url('/absen') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="30" height="30"
                            version="1.1"
                            style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                            viewBox="0 0 416.97 416.97" xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:xodm="http://www.corel.com/coreldraw/odm/2003">
                            <defs>
                                <style type="text/css">
                                <![CDATA[
                                .fil0 {
                                    fill: #533DEA
                                }
                                ]]>
                                </style>
                            </defs>
                            <g id="Layer_x0020_1">
                                <metadata id="CorelCorpID_0Corel-Layer" />
                                <g id="_1809035179712">
                                    <path class="fil0"
                                        d="M71.14 317.18l0 50.39 274.69 0c0,-36.54 3.46,-71.42 -12.51,-103.1 -14.24,-28.23 -35.67,-52.38 -64.29,-66.14 -37.14,-17.86 -83.33,-18.8 -120.32,-0.21 -30.11,15.14 -49.77,36.06 -64.73,65.69 -6.58,13.04 -12.84,33.79 -12.84,53.36z" />
                                    <path class="fil0"
                                        d="M140.31 104.74c0,18.97 9.3,36.6 19.01,46.2 22.05,21.81 51.77,27.46 79.15,14.42 14.24,-6.78 25.33,-19.13 31.94,-33.28 11.82,-25.33 5.76,-55.81 -12.75,-74.54 -44.28,-44.82 -117.35,-12.89 -117.35,47.2z" />
                                    <path class="fil0"
                                        d="M331.01 12.85l73.12 0 0 73.12c0,6.58 12.85,8.51 12.85,-0.99l0 -80.04c0,-2.3 -2.64,-4.94 -4.94,-4.94l-80.04 0c-9.5,0 -7.57,12.85 -0.99,12.85z" />
                                    <path class="fil0"
                                        d="M-0 4.94l0 80.04c0,9.5 12.85,7.57 12.85,0.99l0 -73.12 73.12 0c6.58,0 8.51,-12.85 -0.99,-12.85l-80.04 0c-2.3,0 -4.94,2.64 -4.94,4.94z" />
                                    <path class="fil0"
                                        d="M404.13 331.01l0 73.12 -73.12 0c-6.58,0 -8.51,12.85 0.99,12.85l80.04 0c2.3,0 4.94,-2.64 4.94,-4.94l0 -80.03c0,-9.5 -12.85,-7.57 -12.85,-0.99z" />
                                    <path class="fil0"
                                        d="M-0 332l0 80.03c0,2.3 2.64,4.94 4.94,4.94l80.04 0c9.5,0 7.57,-12.85 0.99,-12.85l-73.12 0 0 -73.12c0,-6.58 -12.85,-8.51 -12.85,0.99z" />
                                </g>
                            </g>
                        </svg>
                        <h5>Absen</h5>
                    </a>
                </li>
                <li class="{{ Request::is('my-dinas-luar*') ? 'active' : '' }}"><a
                        class="fw_4 d-flex justify-content-center align-items-center flex-column"
                        href="{{ url('/my-dinas-luar') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="22"
                            version="1.1"
                            style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                            viewBox="0 0 195.66 246.71" xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:xodm="http://www.corel.com/coreldraw/odm/2003">
                            <defs>
                                <style type="text/css">
                                <![CDATA[
                                .fil0 {
                                    fill: #533DEA
                                }
                                ]]>
                                </style>
                            </defs>
                            <g id="Layer_x0020_1">
                                <metadata id="CorelCorpID_0Corel-Layer" />
                                <g id="_1845407812832">
                                    <path class="fil0"
                                        d="M93.58 176.52c5.73,3.84 17.07,19.14 21.27,19.14l4.25 0c0,3.5 -0.8,8.51 4.25,8.51 5.06,0 4.25,-5.01 4.25,-8.51l25.52 0c0,3.5 -0.8,8.51 4.25,8.51 5.06,0 4.25,-5.01 4.25,-8.51 8.19,0 6.14,-0.02 15.29,-9.17 2.58,-2.58 7.66,-8.24 10.23,-9.97l0 49.45c0,5.75 1.08,12.23 -4.79,12.23l-84.01 0c-5.72,0 -4.79,-6.04 -4.79,-11.7l0 -49.98zm6.38 -6.38l80.82 0c-0.78,1.17 -15.9,17.01 -17.55,17.01l-45.73 0c-1.65,0 -16.8,-15.9 -17.55,-17.01zm40.41 -17.01c5.21,0 10.69,4.18 11.7,8.51l-23.39 0c1.01,-4.32 6.48,-8.51 11.7,-8.51zm-20.74 8.51l-22.86 0c-5.85,0 -11.7,5.85 -11.7,11.7l0 61.68c0,5.85 5.85,11.7 11.7,11.7l87.2 0c5.85,0 11.7,-5.85 11.7,-11.7l0 -61.68c0,-5.85 -5.85,-11.7 -11.7,-11.7l-22.86 0c-0.18,-8.11 -10.53,-17.01 -19.14,-17.01l-3.19 0c-8.62,0 -18.96,8.91 -19.14,17.01z" />
                                    <path class="fil0"
                                        d="M60.61 8.51l23.39 0c-1.91,8.21 -15.09,12.1 -21.6,3.52 -1.56,-2.06 -1.3,-2.4 -1.8,-3.52zm-60.61 11.17l0 171.74c0,2.57 1.68,4.25 4.25,4.25 4.66,0 4.28,-3.86 4.26,-8.51l-0 -140.9c0,-15.96 -4.5,-37.75 13.82,-37.75l29.24 0c0.18,8.11 10.53,17.01 19.14,17.01l3.19 0c8.62,0 18.96,-8.91 19.14,-17.01l29.24 0c15.01,0 13.82,14.03 13.82,22.33l0 90.92c0,6.08 -1.65,14.36 4.25,14.36 2.57,0 4.25,-1.68 4.25,-4.25l0 -112.19c0,-9.81 -9.86,-19.67 -19.67,-19.67l-105.27 0c-9.81,0 -19.67,9.86 -19.67,19.67z" />
                                    <path class="fil0"
                                        d="M72.31 93.58c3,0 8.72,1.27 11,2.3 1.88,0.85 2.9,1.25 4.61,2.31 6.48,4 12.93,12.37 13.64,20.92l-58.49 0c1.16,-13.9 15.3,-25.52 29.24,-25.52zm-12.76 -21.27c0,-10.69 13.46,-17.15 21.69,-8.92 11.74,11.74 -6.11,29.59 -17.85,17.85 -1.72,-1.73 -3.84,-5.73 -3.84,-8.92zm-8.51 -1.6c0,5.4 0.56,10.09 4.17,14.44 1.22,1.47 1.69,1.16 2.21,3.11 -10.98,2.56 -23.39,19.18 -23.39,32.43 0,2.7 -0.43,6.91 4.25,6.91l68.06 0c11.41,0 -0.83,-35.08 -19.14,-39.35 1.05,-3.94 6.38,-3.71 6.38,-17.55 0,-9.81 -9.86,-19.67 -19.67,-19.67l-3.19 0c-9.81,0 -19.67,9.86 -19.67,19.67z" />
                                    <path class="fil0"
                                        d="M-0 225.44c0,12.17 9.91,21.27 19.67,21.27l52.64 0c2.57,0 4.25,-1.68 4.25,-4.25 0,-4.23 -3.14,-4.3 -7.44,-4.26l-35.1 0.01c-10.45,0 -22.12,1.84 -25.01,-10.08 -0.82,-3.37 -0.26,-6.93 -4.76,-6.93 -2.57,0 -4.25,1.68 -4.25,4.25z" />
                                    <path class="fil0"
                                        d="M-0 208.42c0,5.71 8.51,5.71 8.51,0 0,-5.71 -8.51,-5.71 -8.51,0z" />
                                </g>
                            </g>
                        </svg>
                        <!-- <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12.25" cy="12" r="9.5"
                                stroke="{{ Request::is('my-dinas-luar*') ? '#0000FF' : '#717171' }}" />
                            <path
                                d="M17.033 11.5318C17.2298 11.3316 17.2993 11.0377 17.2144 10.7646C17.1293 10.4914 16.9076 10.2964 16.6353 10.255L14.214 9.88781C14.1109 9.87213 14.0218 9.80462 13.9758 9.70702L12.8933 7.41717C12.7717 7.15989 12.525 7 12.2501 7C11.9754 7 11.7287 7.15989 11.6071 7.41717L10.5244 9.70723C10.4784 9.80483 10.3891 9.87234 10.286 9.88802L7.86469 10.2552C7.59257 10.2964 7.3707 10.4916 7.2856 10.7648C7.2007 11.038 7.27018 11.3318 7.46702 11.532L9.2189 13.3144C9.29359 13.3905 9.32783 13.5 9.31021 13.607L8.89692 16.1239C8.86027 16.3454 8.91594 16.5609 9.0533 16.7308C9.26676 16.9956 9.6394 17.0763 9.93735 16.9128L12.1027 15.7244C12.1932 15.6749 12.3072 15.6753 12.3975 15.7244L14.563 16.9128C14.6684 16.9707 14.7807 17 14.8966 17C15.1083 17 15.3089 16.9018 15.4469 16.7308C15.5845 16.5609 15.6399 16.345 15.6033 16.1239L15.1898 13.607C15.1722 13.4998 15.2064 13.3905 15.2811 13.3144L17.033 11.5318Z"
                                stroke="{{ Request::is('my-dinas-luar*') ? '#0000FF' : '#717171' }}"
                                stroke-width="1.25" />
                        </svg> -->
                        <h5>History Dinas</h5>
                    </a> </li>
                <li class="{{ Request::is('my-profile*') ? 'active' : '' }}"><a
                        class="fw_4 d-flex justify-content-center align-items-center flex-column"
                        href="{{ url('/my-profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="20" height="22"
                            version="1.1"
                            style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                            viewBox="0 0 619.96 619.96" xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:xodm="http://www.corel.com/coreldraw/odm/2003">
                            <defs>
                                <style type="text/css">
                                <![CDATA[
                                .fil0 {
                                    fill: #403DA9
                                }
                                ]]>
                                </style>
                            </defs>
                            <g id="Layer_x0020_1">
                                <metadata id="CorelCorpID_0Corel-Layer" />
                                <path class="fil0"
                                    d="M135.62 519.46c0,-18.85 11.58,-48.36 18.56,-61.36 11.53,-21.48 26.25,-39.68 44.93,-54.36 27.5,-21.62 66.96,-39.27 102.4,-39.27 34.12,0 50.33,1.96 81.48,15.39 44.55,19.21 79.06,59.97 94.25,105.54 2.72,8.17 7.11,21.98 7.11,32.85 0,5.21 -30.36,23.52 -33.67,25.67 -12.26,7.94 -25.24,13.83 -39.5,19.84 -31.29,13.19 -65.89,18.05 -100.1,19.86l-27.43 -1.5c-25.63,-3.32 -49.83,-8.2 -73.15,-17.66 -19.82,-8.04 -41.45,-19.18 -58.12,-31.48 -2.27,-1.68 -16.76,-12.02 -16.76,-13.51zm157.7 -519.46l33.43 0c19.59,1.07 39.34,4.02 59.05,8.95 10.41,2.6 19.07,5.35 28.79,8.75 24.7,8.64 53.76,23.8 74.1,38.51 60.8,43.98 104.45,105.21 122.58,178.93 5.03,20.47 7.91,41.33 8.7,62.23l0 23.69c-1.19,31.81 -7.16,63.4 -17.7,93.52 -3.22,9.2 -6.77,18.29 -10.95,26.58 -36.23,71.88 -78.95,114.53 -150.95,150.56 -36.42,18.23 -80.92,27.64 -125.7,28.24l-9.44 0c-45.03,-0.64 -89.81,-10.19 -126.43,-28.65 -71.88,-36.23 -114.53,-78.95 -150.56,-150.95 -21.47,-42.92 -28.24,-89.82 -28.24,-138.86 0,-58.43 22.44,-123.55 56.21,-170.22 56.94,-78.71 143.73,-126.23 237.11,-131.28zm-76.57 237.33c0,-20.23 0.3,-28.51 9.67,-47.24 21.92,-43.76 75.3,-64.08 120.31,-44 20.64,9.21 36.27,23.04 46.42,43.19 17.22,34.21 12.94,72.82 -11.34,102.92 -29.3,36.32 -83.11,44.77 -124.63,17.31 -3.02,-2 -3.65,-2.37 -6.21,-4.68 -11.34,-10.22 -17.51,-15.94 -25.36,-31.55 -4.52,-8.98 -8.85,-22.96 -8.85,-35.95zm-37.54 -8.48c0,38.28 17.16,80.16 44.1,101.21 3.59,2.81 8.79,7.91 12.81,8.98 -2.06,2.81 -30.16,13.55 -45.5,24.73l-19.71 15.41c-14.04,11.31 -16.55,16.17 -26.52,26.76 -5.3,5.64 -17.88,25.13 -21.28,32 -2.35,4.74 -3.83,6.22 -5.85,11.1 -5.49,13.29 -7.89,17.42 -12.3,32.51 -1.66,5.68 0.58,3.62 -3.26,6.43l-33.49 -51.28c-26.99,-53.43 -38.91,-114.04 -27.39,-174.28 4.53,-23.67 10.58,-44.22 20.51,-65.46 1.66,-3.56 2.4,-6.19 4.01,-9.31 12.57,-24.51 31.95,-51.64 51.3,-71 29.84,-29.84 67.47,-52.65 108.11,-66.26 17.74,-5.94 41.71,-11.3 61.07,-12.8 3.92,-0.3 7.93,0.2 11.87,-0.24 57.83,-6.41 133.02,20.23 177.66,55.18 4.8,3.76 8.87,6.87 13.47,10.74 9.53,8.02 16.59,16.5 24.89,24.75l21.83 27.82c3.4,4.62 6.51,9.99 9.4,14.81l16.72 32.92c23.94,58.84 29.34,127.34 9.1,188 -4.54,13.6 -8.69,23.38 -13.94,35.7l-27.97 45.89c-2.77,3.35 -9.63,11.08 -10.62,14.8 -3.35,-2.45 -6.2,-16.68 -9.67,-25.44 -1.74,-4.4 -3.22,-7.36 -5.19,-11.76 -11.19,-24.92 -32.37,-53.81 -53.3,-71.42 -12.51,-10.52 -27.44,-20.95 -42.02,-28.21 -4.13,-2.05 -22.66,-9.98 -24.22,-12.11 3.62,-0.97 9.08,-5.69 12.3,-8.28 9.75,-7.87 21.57,-23.72 27.62,-34.14 20.25,-34.87 22.77,-80.23 5.29,-117.82 -2.27,-4.89 -2.65,-6.41 -5.17,-10.57 -14.23,-23.42 -31.02,-40.82 -56.57,-53.62 -72.02,-36.07 -163.91,4.04 -184.24,86.62 -2.08,8.44 -3.86,17.51 -3.86,27.62z" />
                            </g>
                        </svg>
                        <h5>Profile</h5>
                    </a> </li>
            </ul>
        </div>
    </div>
    @php
    $settings = App\Models\settings::first();
    @endphp

    <div class="tf-panel left">
        <div class="panel_overlay"></div>
        <div class="panel-box panel-left panel-sidebar">
            <div class="header-sidebar bg_white_color is-fixed">
                <div class="tf-container">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ url('/') }}" class="sidebar-logo">
                            <img src="@if (is_file(url('storage/'.$settings->logo))) {{ url('storage/'.$settings->logo) }}
                                    @else {{ url('/assets/img/neoma_logo.jpg') }}
                                    @endif" alt="logo perusahaan" style="height:20px; width: 20px">
                            <h5 class="ml-1">Absensi</h5>
                        </a>
                        <a href="javascript:void(0);" class="clear-panel"> <i class="icon-close1"></i> </a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="tf-container">
                    <div class="box-content">

                        <ul class="box-nav">
                            <li class="nav-title">MENU</li>
                            <li>
                                <a href="{{ url('/dashboard') }}" class="nav-link">
                                    <i class="fas fa-home"
                                        style="{{ Request::is('dashboard*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('dashboard*') ? 'color: blue' : '' }}">Home</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-profile') }}" class="nav-link">
                                    <i class="fas fa-user"
                                        style="{{ Request::is('my-profile*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('my-profile*') ? 'color: blue' : '' }}">My
                                        Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/pegawai') }}" class="nav-link">
                                    <i class="fas fa-users"
                                        style="{{ Request::is('pegawai*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('pegawai*') ? 'color: blue' : '' }}">Pegawai</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/payroll') }}" class="nav-link">
                                    <i class="fa fa-file-invoice-dollar"
                                        style="{{ Request::is('payroll*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('payroll*') ? 'color: blue' : '' }}">Payroll</span>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ url('/my-dokumen') }}" class="nav-link">
                                    <i class="fa fa-folder-open" style="{{ Request::is('my-dokumen*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('my-dokumen*') ? 'color: blue' : '' }}">Dokumen</span>
                                </a>
                            </li> -->
                            <!-- <li>
                                <a href="{{ url('/kasbon') }}" class="nav-link">
                                    <i class="fa fa-comments-dollar"
                                        style="{{ Request::is('kasbon*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('kasbon*') ? 'color: blue' : '' }}">Kasbon</span>
                                </a>
                            </li> -->
                            <li>
                                <a href="{{ url('/cuti') }}" class="nav-link">
                                    <i class="fa fa-hourglass-half"
                                        style="{{ Request::is('cuti*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('cuti*') ? 'color: blue' : '' }}">Cuti / Izin</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/absen') }}" class="nav-link">
                                    <i class="fa fa-camera"
                                        style="{{ Request::is('absen*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('absen*') ? 'color: blue' : '' }}">Absensi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-absen') }}" class="nav-link">
                                    <i class="fa fa-table"
                                        style="{{ Request::is('my-absen*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('my-absen*') ? 'color: blue' : '' }}">History
                                        Absen</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/dinas-luar') }}" class="nav-link">
                                    <i class="fa fa-stopwatch"
                                        style="{{ Request::is('dinas-luar*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('dinas-luar*') ? 'color: blue' : '' }}">Dinas
                                        Luar</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-dinas-luar') }}" class="nav-link">
                                    <i class="fa fa-user-secret"
                                        style="{{ Request::is('my-dinas-luar*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('my-dinas-luar*') ? 'color: blue' : '' }}">History Dinas
                                        Luar</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/lembur') }}" class="nav-link">
                                    <i class="fa fa-user-clock"
                                        style="{{ Request::is('lembur*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('lembur*') ? 'color: blue' : '' }}">Lembur</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-lembur') }}" class="nav-link">
                                    <i class="fa fa-business-time"
                                        style="{{ Request::is('my-lembur*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('my-lembur*') ? 'color: blue' : '' }}">History
                                        Lembur</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/oncall') }}" class="nav-link">
                                    <i class="fas fa-envelope-open-text"
                                        style="{{ Request::is('oncall*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('oncall*') ? 'color: blue' : '' }}">Oncall</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/my-oncall') }}" class="nav-link">
                                    <i class="fa fa-baby"
                                        style="{{ Request::is('my-oncall*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('my-oncall*') ? 'color: blue' : '' }}">History
                                        Oncall</span>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ url('/pengajuan-absensi') }}" class="nav-link">
                                    <i class="fas fa-envelope-open-text"
                                        style="{{ Request::is('pengajuan-absensi*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('pengajuan-absensi*') ? 'color: blue' : '' }}">Pengajuan
                                        Absensi</span>
                                </a>
                                </li> -->
                            <li>
                                <a href="{{ url('/notifications') }}" class="nav-link">
                                    <i class="fas fa-bell"
                                        style="{{ Request::is('notifications*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span
                                        style="{{ Request::is('notifications*') ? 'color: blue' : '' }}">Notifications</span>
                                </a>
                            </li>
                            <li>
                                <a class="nav-link" href="javascript:void(0);" onclick="confirmLogout()">
                                    <i class="fas fa-sign-out-alt"
                                        style="{{ Request::is('logout*') ? 'color: blue' : 'color: black' }}"></i>
                                    <span style="{{ Request::is('logout*') ? 'color: blue' : '' }}">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script type="text/javascript" src="{{ url('/myhr/javascript/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/myhr/javascript/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/myhr/javascript/swiper-bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/myhr/javascript/swiper.js') }}"></script>
    <script type="text/javascript" src="{{ url('/myhr/javascript/main.js') }}"></script>
    <script src="{{ url('https://cdn.jsdelivr.net/npm/flatpickr') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/clock/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        config = {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
        }

        flatpickr("input[type=datetime-local]", config)
        flatpickr("input[type=datetime]", {})
    })

    $(function() {

        $('#tablePayroll').DataTable({
            "responsive": true,
            "paging": false,
            "info": false,
            "scrollCollapse": true,
            "autoWidth": false,
            'searching': false
        });
        $('#tableOvertime').DataTable({
            "responsive": true,
            "paging": false,
            "info": false,
            "scrollCollapse": true,
            "autoWidth": false,
            'searching': false
        });
        $('#tableOncall').DataTable({
            "responsive": true,
            "paging": false,
            "info": false,
            "scrollCollapse": true,
            "autoWidth": false,
            'searching': false
        });
        $("#tableprint").DataTable({
            "responsive": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            dom: 'flrtip'
            // "buttons": ["excel", "pdf", "print"]
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tableprint_wrapper .col-md-6:eq(0)');


    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ url('/push/bin/push.js') }}"></script>
    <script src="{{ url('/js/app.js') }}"></script>
    <script src="{{ url('/js/custom.js') }}"></script>
    <script>
    window.Echo.channel("messages").listen("NotifApproval", (event) => {
        var user_id = {
            {
                auth() - > user() - > id
            }
        };
        if (event.user_id == user_id) {
            if (event.type == "Approved") {
                Swal.fire({
                    icon: "success",
                    title: "Approved",
                    text: event.notif,
                    footer: "<a href=" + event.url + ">View Application</a>",
                });
            } else if (event.type == "Approval") {
                Swal.fire({
                    icon: "info",
                    title: "",
                    text: event.notif,
                    footer: "<a href=" + event.url + ">View Application</a>",
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Rejected",
                    text: event.notif,
                    footer: "<a href=" + event.url + ">View Application</a>",
                });
            }
            Push.create(event.notif);
        }
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Back button (popstate) event listener
        window.addEventListener("popstate", function() {
            // Reload the page or trigger data fetching
            location.reload(); // Forces the page to reload and display updated data
        });

        // Custom behavior for your "back-btn" element to ensure proper handling
        const backButton = document.querySelector(".back-btn");
        if (backButton) {
            backButton.addEventListener("click", function(e) {
                e.preventDefault(); // Prevent default behavior of the link
                history.back(); // Go back to the previous page
            });
        }
    });
    </script>
    @include('templates.image-modal')
    @include('sweetalert::alert')
    @include('templates.alertFunction')
    @stack('script')

</body>

</html>
