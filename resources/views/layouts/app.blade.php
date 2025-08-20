<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('/') }}assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ config('app.name') }} | @yield('title')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ secure_asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ secure_asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ secure_asset('assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ secure_asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ secure_asset('assets/css/demo.css') }}" />

    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ secure_asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ secure_asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Custom CSS for Sticky Footer -->
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .layout-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .layout-page {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        footer.content-footer {
            margin-top: auto;
        }

        #toast-container .toast {
            font-size: 0.875rem;
            padding: 0.5rem;
            max-width: 300px;
        }

        #toast-container .toast-header {
            font-size: 0.8rem;
            padding: 0.4rem 0.6rem;
        }

        #toast-container .toast-body {
            padding: 0.5rem 0.6rem;
        }
    </style>

    <!-- Helpers -->
    <script src="{{ secure_asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ secure_asset('assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                Copyright &copy;
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , <b>{{ config('app.name') }}</b>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ secure_asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ secure_asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ secure_asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ secure_asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ secure_asset('assets/vendor/js/menu.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Vendors JS -->
    <script src="{{ secure_asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ secure_asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ secure_asset('assets/js/dashboards-analytics.js') }}"></script>

    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

    <script>
        function showToast(type = 'info', message = '', title = '') {
            const typeColors = {
                success: 'bg-success text-white',
                error: 'bg-danger text-white',
                warning: 'bg-warning text-dark',
                info: 'bg-primary text-white'
            };

            const toastColor = typeColors[type] || typeColors.info;

            const toastEl = document.createElement('div');
            toastEl.className = `toast align-items-center border-0 ${toastColor}`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');

            toastEl.innerHTML = `
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <strong class="me-auto">${title || type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;

            document.getElementById('toast-container').appendChild(toastEl);

            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            });
            toast.show();

            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        }
    </script>
    @stack('scripts')

</body>

</html>
