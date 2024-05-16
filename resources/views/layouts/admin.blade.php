<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Lotus Thé</title>
    {{-- Icon --}}
    <link rel="shortcut icon" type="image/png"
        href="https://bizweb.dktcdn.net/100/461/240/themes/870680/assets/icon_why_2.png?1666679797652" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ url('/rsrc') }}/plugins/fontawesome-free/css/all.min.css">

    {{-- Theme style bonus --}}
    <link rel="stylesheet" href="{{ url('/rsrc') }}/dist/css/main-sidebar.css">
    {{-- Toast CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Booostrap CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('/rsrc') }}/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ url('/rsrc/dist/css/list-content.css') }}">
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url()->route('dashboard') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url()->route('order.list') }}" class="nav-link">Order</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url()->route('dashboard') }}" class="brand-link">
                <img src="{{ url('/rsrc') }}/dist/img/AdminLotusLogo.jpg" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Lotus Thé Admin</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url()->route('dashboard') }}" class="nav-link">
                                <i class="nav-icon nav-icon-main fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        @php
                            $allowedRoleManager = ['super', 'admin', 'manager'];
                        @endphp

                        @if (in_array(Auth::user()->role, $allowedRoleManager))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-sliders-h nav-icon nav-icon-main"></i>
                                    <p>
                                        Slide
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('slide.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('slide.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fab fa-blogger-b nav-icon nav-icon-main"></i>
                                    <p>
                                        Bài viết
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('post.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('post.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endif

                        @php
                            $allowedRoleSalesManager = ['super', 'admin', 'sales_manager'];
                        @endphp

                        @if (in_array(Auth::user()->role, $allowedRoleSalesManager))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon nav-icon-main fas fa-folder"></i>
                                    <p>
                                        Danh mục
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('category.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('category.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-palette nav-icon nav-icon-main"></i>
                                    <p>
                                        Màu sắc
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('color.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('color.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-mug-hot nav-icon nav-icon-main"></i>
                                    <p>
                                        Nhãn hiệu
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('brand.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('brand.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-leaf nav-icon nav-icon-main"></i>
                                    <p>
                                        Sản phẩm
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('product.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('product.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-sticky-note nav-icon nav-icon-main"></i>
                                    <p>
                                        Phiếu giảm giá
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('coupon.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('coupon.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-images nav-icon nav-icon-main"></i>
                                    <p>
                                        Hình ảnh
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('image.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm theo sản phẩm</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('image.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-tags nav-icon nav-icon-main"></i>
                                    <p>
                                        Thẻ
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('tag.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('tag.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                    <li class="ml-3 nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="fas fa-paperclip nav-icon"></i>
                                            <p>
                                                Chi tiết thẻ
                                                <i class="fas fa-angle-left right"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="{{ url()->route('productTag.add') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Thêm mới</p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ url()->route('productTag.list') }}" class="nav-link">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Danh sách</p>
                                                </a>
                                            </li>

                                        </ul>
                                    </li>

                                </ul>
                            </li>
                        @endif

                        @php
                            $allowedRoleAdmin = ['super', 'admin'];
                        @endphp

                        @if (in_array(Auth::user()->role, $allowedRoleAdmin))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-shipping-fast nav-icon-main"></i>
                                    <p>
                                        Bán hàng
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('order.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách đơn hàng</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('bill.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Danh sách hoá đơn
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('user.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Danh sách người dùng
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url()->route('cart.list') }}" class="nav-link">
                                    <i class="fas fa-shopping-cart nav-icon nav-icon-main"></i>
                                    <p>
                                        Giỏ hàng
                                    </p>
                                </a>
                            </li>
                        @endif

                        @php
                            $allowedRoleSuperAdmin = ['super'];
                        @endphp
                        @if (in_array(Auth::user()->role, $allowedRoleSuperAdmin))
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-user-shield nav-icon-main"></i>
                                    <p>
                                        Quản trị viên
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url()->route('admin.add') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Thêm mới</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url()->route('admin.list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Danh sách</p>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        @endif
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
                <!-- Sidebar user panel (optional) -->
                <div class="space-user"></div>
                <div class="dropdown-divider"></div>
                <div class="user-panel mb-2 pb-2 d-flex nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <div class="image">
                            @php
                                $urlAvatar = getUrlAvatarAuth(Auth::user()->avatar);
                            @endphp
                            <img src="{{ $urlAvatar }}" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <span class="pl-3">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right pt-2">
                        <a class="dropdown-item" href="{{ route('admin.edit', Auth::user()->id) }}">
                            {{ __('Thông tin tài khoản') }}
                        </a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('admin.editPassword', Auth::user()->id) }}">
                            {{ __('Thay đổi mật khẩu') }}
                        </a>

                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                        </a>
                    </div>
                </div>
                <!-- SidebarSearch Form -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content">
                @yield('content')
            </div>
        </div>
        <!-- Main Footer -->
        <footer class="main-footer">
            <span class="title-brand">
                <i class="fas fa-mug-hot"></i>
                <strong>Lotus Thé</strong>
                <i>- Connecting people and nature, elevating the essence of Vietnamese tea.</i>
            </span>

            <div class="float-right d-none d-sm-inline-block">
                <b>Lotus Thé</b> 2023
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ url('/rsrc') }}/plugins/jquery/jquery.min.js"></script>
    <script src="{{ url('/rsrc') }}/plugins/jquery/main.js"></script>
    <!-- Bootstrap -->
    <script src="{{ url('/rsrc') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="{{ url('/rsrc') }}/dist/js/adminlte.js"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="{{ url('/rsrc') }}/plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ url('/rsrc') }}/dist/js/demo.js"></script>
    <script src="{{ url('/rsrc') }}/dist/js/app.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ url('/rsrc') }}/dist/js/pages/dashboard3.js"></script>
    {{-- Import Tiny  --}}
    <script src="https://cdn.tiny.cloud/1/59en1thrciifq1lp6hnhxjbsln9i43chuyaftpnhfw34mp2r/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        var editor_config = {
            path_absolute: "http://localhost/project/sabujcha/truongnx_2023_03_07_backend/public/",

            selector: 'textarea',
            relative_urls: false,
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table directionality", "emoticons template paste textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            file_picker_callback: function(callback, value, meta) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
                    'body')[0].clientWidth;
                var y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
                if (meta.filetype == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.openUrl({
                    url: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: "yes",
                    close_previous: "no",
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };

        tinymce.init(editor_config);
    </script>
    {{-- End Import Tiny --}}

    {{-- Toast CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- Has status --}}
    @if (Session::has('statusSuccess'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success("{{ Session::get('statusSuccess') }}", "Success!", {
                timeOut: 4000
            });
        </script>
    @elseif(Session::has('statusFail'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.error("{{ Session::get('statusFail') }}", "Error!", {
                timeOut: 4000
            });
        </script>
    @endif
    @if (Session::has('welcome'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.info("Xin chào đã đến với Lotus Thé Admin Shop, hân hạnh!", "Welcome!", {
                timeOut: 4000
            });
        </script>
    @endif

</body>

</html>
