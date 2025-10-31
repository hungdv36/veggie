<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="{{ asset('assets/admin/vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap JS (cần Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('assets/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- NProgress -->
    <link href="{{ asset('assets/admin/vendors/nprogress/nprogress.css') }}" rel="stylesheet">

    <!-- iCheck -->
    <link href="{{ asset('assets/admin/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">

    <!-- JQVMap -->
    <link href="{{ asset('assets/admin/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet">

    <!-- Bootstrap daterangepicker -->
    <link href="{{ asset('assets/admin/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Custom Theme -->
    <link href="{{ asset('assets/admin/build/css/custom.min.css') }}" rel="stylesheet">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            @include('admin.partials.side-bar')
            @include('admin.partials.top-nagivation')

            <main>
                @yield('content')
            </main>

            @include('admin.partials.footer')
        </div>
    </div>

    <!-- ========================= JS ========================= -->

    <!-- jQuery (PHẢI load trước tất cả plugin) -->

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/admin/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Plugin khác -->
    <script src="{{ asset('assets/admin/vendors/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/nprogress/nprogress.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/Chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/gauge.js/dist/gauge.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/skycons/skycons.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/Flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/Flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/Flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/flot.curvedlines/curvedLines.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/DateJS/build/date.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{ asset('assets/admin/build/js/custom.min.js') }}"></script>

    <script>
        const upgradeUrl = "{{ route('admin.user.upgrade') }}";
        var updateStatusUrl = "{{ route('admin.user.updateStatus') }}";
        var toggleDeleteUrl = "{{ route('admin.user.toggleDelete') }}";
    </script>
    <!-- Custom JS -->


    <!-- CSRF token cho AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <style>
        .file-input {
            display: none;
            /* Ẩn input gốc */
        }

        .custom-file-label {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .file-name {
            margin-left: 10px;
            font-style: italic;
            color: #555;
        }

        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .main-content {
            /* div bọc nội dung page */
            flex: 1 0 auto;
            /* chiếm không gian còn lại */
        }

        footer {
            flex-shrink: 0;
            /* luôn nằm dưới */
        }
    </style>
    @stack('scripts')
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description', {
            height: 150,
            removeButtons: 'PasteFromWord'
        });
    </script>
    <script>
        $(document).on("change", "input[id^='imageInput-']", function() {
            let input = $(this);
            let id = input.attr("id").replace("imageInput-", "");
            console.log("File input change:", id);

            let label = $("label[for='imageInput-" + id + "']");
            let oldImg = label.find(".old-image");
            let newPreview = $("#imagePreview-" + id);

            console.log("Label found:", label.length, " | Old image:", oldImg.length, " | New preview:", newPreview
                .length);

            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    oldImg.hide();
                    newPreview.attr("src", e.target.result).show();
                    console.log("Preview updated for", id);
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                newPreview.hide();
                oldImg.show();
            }
        });
    </script>

    <!-- Toastr session + error -->
    <script>
        $(document).ready(function() {
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error({!! json_encode($error) !!}, "Lỗi", {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 5000
                    });
                @endforeach
            @endif

            @if (session('success'))
                toastr.success({!! json_encode(session('success')) !!}, "Thành công", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
            @endif

            @if (session('error'))
                toastr.error({!! json_encode(session('error')) !!}, "Lỗi", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
            @endif
        });
    </script>

    @stack('scripts')
    <script>
        console.log("Script loaded");
        $(document).on("click", ".btn-update-product", function() {
            console.log("Clicked!", $(this).data("id"));
        });
    </script>
</body>

</html>
