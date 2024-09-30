<?php
$profile = getProfileWeb();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url() ?>assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?php echo base_url($profile['icon']) ?>">
    <title>
        <?php echo $profile['title'] ?>
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="<?php echo base_url() ?>assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link href="<?php echo base_url() ?>assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="<?php echo base_url() ?>assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-avatar@1.0.3/dist/avatar.min.css" rel="stylesheet">


    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <link rel="manifest" href="<?php echo base_url('manifest.json') ?>" />

    <link rel="shortcut icon" type="image/png" href="<?php echo base_url($profile['icon']) ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('/icon-192x192.png') ?>" type="image/png">
    <meta name="theme-color" content="#30475e">

    <!-- remixicon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- scroll -->
    <style>
        body {
            height: 98vh !important;
            overflow-y: none;
        }

        body.modal-open {
            overflow: hidden;
        }

        ::-webkit-scrollbar {
            width: 20px;
        }

        ::-webkit-scrollbar-track {
            background-color: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #d6dee1;
            border-radius: 20px;
            border: 6px solid transparent;
            background-clip: content-box;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #a8bbbf;
        }
    </style>

    <!-- sweetalert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>

    <!-- select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- iconpicker -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/js/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css">
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

    <!-- editor.js -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/attaches@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script> -->

    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/balloon-block/ckeditor.js"></script> -->
    <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/decoupled-document/ckeditor.js"></script>
    <script src="https://www.jsdelivr.com/package/npm/ckeditor5-base64-upload-adapter"></script>

    <script>
        // custom


        function handleToast(icon, title) {
            var toastMixin = Swal.mixin({
                toast: true,
                icon: icon,
                title: title,
                animation: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            toastMixin.fire();
        }

        function breadcrumb(breadcrumb) {
            $("#breadcrumb").html(breadcrumb);
        }

        function toTop() {
            $('main').animate({
                scrollTop: 0
            }, 'fast');
        }

        function  loadingOn() {
            $("div.spanner").addClass("show");
            $("div.overlay").addClass("show");
        }

        function loadingOff()  {
            setTimeout(function() {
                $("div.spanner").removeClass("show");
                $("div.overlay").removeClass("show");
            }, 200)
        }
    </script>
    <script src="<?php echo base_url() ?>assets/js/plugins/perfect-scrollbar.min.js"></script>

    <!-- select2 -->
    <style>
        .select2-dropdown {
            border-top-right-radius: 0px;
            border-top-left-radius: 0px;
            border-bottom-right-radius: 0.5rem;
            border-bottom-left-radius: 0.5rem;
            border: 1px solid #d2d6da;
        }

        .select2-search__field {
            border: 1px solid #d2d6da;
            border-radius: 0.5rem;
        }

        .select2-container {
            padding: 0.35rem 0.075rem;
            margin: 0px;
        }

        .select2-selection {
            padding: 0px;
            margin: 0px;
            border: 0px !important;
        }

        .select2-selection__arrow {
            display: none;
        }

        .spanner {
            position: absolute;
            top: 50%;
            left: 0;
            background: #2a2a2a55;
            width: 100%;
            display: block;
            text-align: center;
            height: 100vh;
            color: #FFF;
            transform: translateY(-50%);
            z-index: 1000;
            visibility: hidden;
        }

        .overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            visibility: hidden;
        }

        .loader,
        .loader:before,
        .loader:after {
            border-radius: 50%;
            width: 2.5em;
            height: 2.5em;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation: load7 1.8s infinite ease-in-out;
            animation: load7 1.8s infinite ease-in-out;
        }

        .loader {
            color: #ffffff;
            font-size: 10px;
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-indent: -9999em;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation-delay: -0.16s;
            animation-delay: -0.16s;
        }

        .loader:before,
        .loader:after {
            content: '';
            position: absolute;
            top: 0;
        }

        .loader:before {
            left: -3.5em;
            -webkit-animation-delay: -0.32s;
            animation-delay: -0.32s;
        }

        .loader:after {
            left: 3.5em;
        }

        @-webkit-keyframes load7 {

            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }

            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }

        @keyframes load7 {

            0%,
            80%,
            100% {
                box-shadow: 0 2.5em 0 -1.3em;
            }

            40% {
                box-shadow: 0 2.5em 0 0;
            }
        }

        .show {
            visibility: visible;
        }

        .spanner,
        .overlay {
            opacity: 0;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            transition: all 0.3s;
        }

        .spanner.show,
        .overlay.show {
            opacity: 1
        }
    </style>
</head>

<body class="g-sidenav-show  bg-gray-100 scr">
    <div style="z-index: 15000 ;" class="overlay"></div>
    <div class="spanner">
        <div class="loader"></div>
    </div>
    <!-- Side -->
    <?php $this->load->view('layouts/back/side'); ?>
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <!-- Navbar -->
        <?php $this->load->view('layouts/back/nav'); ?>
        <div id="container" class="container-fluid py-4">
            <div class="row">

                <!-- content -->
                <?php
                if (!isset($_view)) {
                    echo "Content not set";
                } else {
                    $this->load->view($_view);
                }
                ?>

                <footer class="footer pt-3  ">
                    <div class="container-fluid">
                        <div class="row align-items-center justify-content-lg-between">
                            <div class="col-lg-12 mb-lg-0 mb-4">
                                <div class="copyright text-center text-sm text-muted text-lg-start">
                                    © <script>
                                        document.write(new Date().getFullYear())
                                    </script>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
    </main>
    <!--   Core JS Files   -->
    <script src="<?php echo base_url() ?>assets/js/core/popper.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/core/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="<?php echo base_url() ?>assets/js/plugins/chartjs.min.js"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
        $(".navbar-nav>li").each(function() {
            var navItem = $(this);
            if (navItem.children().hasClass('active')) {
                navItem.focus()
            }
        });
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="<?php echo base_url() ?>assets/js/soft-ui-dashboard.js?v=1.0.3"></script>
    <script src="<?= base_url() ?>assets/js/jquery-mask/jquery.mask.min.js"></script>
    <script src="<?= base_url() ?>assets/js/custom/rupiah.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        sidebarType("bg-white");
        // navbarFixed(true);
        // $('form').bind('keypress', false);
    </script>
        <style>
    .block__install {
    	 display: none;
    }
     /*@media (max-width: 768px) {*/
    	 .block__install {
    		 position: fixed;
    		 bottom: 0px;
    		 left: 0px;
    		 width: 100%;
    		 z-index: 999999;
    		 background: #fff;
    		 padding: 15px;
    	}
    	 .block__install .inner {
    		 display: flex;
    		 align-items: center;
    	}
    	 .block__install .inner .close {
    		 width: 32px;
    		 height: 32px;
    		 line-height: 32px;
    	}
    	 .block__install .inner .close img {
    		 width: 32px;
    		 height: 32px;
    	}
    	 .block__install .inner .logo {
    		 width: 48px;
    	}
    	 .block__install .inner .logo img {
    		 width: 42px;
    		 border-radius: 2px;
    	}
    	 .block__install .inner .name {
    		 padding-left: 10px;
    		 background-color: #fff;
    	}
    	 .block__install .inner .name span {
    		 display: block;
    	}
    	 .block__install .inner .name span.title {
    		 font-size: 1.125rem;
    		 line-height: 1;
    		 font-weight: 600;
    	}
    	 .block__install .inner .cta {
    		 margin-left: auto;
    	}
    	 .block__install.is-active {
    		 display: block;
    	}
    /*}*/
    </style>
    <div class="block__install" id="BlockInstall">
        <div class="inner">
            <div class="close" id="BlockInstallClose">
                <span>
                  x
                </span>
            </div>
            <div class="logo">
                <img src="<?php echo base_url($profile['icon'])?>" />
            </div>
            <div class="name">
                <span class=""><?php echo $profile['title']?></span>
            </div>
            <div class="cta">
                <button id="BlockInstallButton" class="btn btn-outline">Install</button>
            </div>
        </div>
    </div>
    <script>
        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(";");
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkCookie() {
            var user = getCookie("username");
            if (user != "") {
                alert("Welcome again " + user);
            } else {
                user = prompt("Please enter your name:", "");
                if (user != "" && user != null) {
                    setCookie("username", user, 365);
                }
            }
        }

        $(document).ready(function() {
            // When the user clicks on Close, we need to keep this in mind and not annoy him again
            $("#BlockInstallClose").on("click", function(e) {
                $("#BlockInstall").removeClass("is-active");
                setCookie("BlockInstallCookieHide", 1, 14);
            });
        });
    </script>
    <script>
        var BASE_URL = '<?= base_url() ?>';
        document.addEventListener('DOMContentLoaded', init, false);

        function init() {
            if ('serviceWorker' in navigator && navigator.onLine) {
                navigator.serviceWorker.register(BASE_URL + 'service-worker.js')
                    .then((reg) => {
                        console.log('Registrasi service worker Berhasil', reg);
                    }, (err) => {
                        console.error('Registrasi service worker Gagal', err);
                    });
            }
        }
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();

            let cookieBlockInstallCookieHide = getCookie("BlockInstallCookieHide");
            if (!cookieBlockInstallCookieHide) {
                $("#BlockInstall").addClass("is-active");
            }
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI notify the user they can install the PWA
            // showInstallPromotion();
            // Optionally, send analytics event that PWA install promo was shown.
            console.log(`'beforeinstallprompt' event was fired.`);
        });

        let buttonInstall = document.getElementById('BlockInstallButton');
        buttonInstall.addEventListener('click', async () => {
            // Hide the app provided install promotion
            // hideInstallPromotion();
            $("#BlockInstall").removeClass("is-active");

            // Show the install prompt
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            const {
                outcome
            } = await deferredPrompt.userChoice;
            // Optionally, send analytics event with outcome of user choice
            console.log(`User response to the install prompt: ${outcome}`);
            // We've used the prompt, and can't use it again, throw it away
            deferredPrompt = null;
        });
        window.addEventListener('appinstalled', () => {
            // Hide the app-provided install promotion
            hideInstallPromotion();
            // Clear the deferredPrompt so it can be garbage collected
            deferredPrompt = null;
            // Optionally, send analytics event to indicate successful install
            console.log('PWA was installed');
        });

        function getPWADisplayMode() {
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
            if (document.referrer.startsWith('android-app://')) {
                return 'twa';
            } else if (navigator.standalone || isStandalone) {
                return 'standalone';
            }
            return 'browser';
        }

        window.matchMedia('(display-mode: standalone)').addEventListener('change', (evt) => {
            let displayMode = 'browser';
            if (evt.matches) {
                displayMode = 'standalone';
            }
            // Log display mode change to analytics
            console.log('DISPLAY_MODE_CHANGED', displayMode);
        });
    </script>
</body>

</html>