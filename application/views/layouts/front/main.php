<?php
$profile = getProfileWeb();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>
        <?php echo $profile['title'] ?>
    </title>
    <meta content="" name="description">

    <meta content="" name="keywords">

    <link href="<?php echo base_url() ?>assets/front/<?php echo base_url() ?>assets/front/assets/img/apple-touch-icon.png" rel="apple-touch-icon">


    <!-- Favicons -->
    <link rel="icon" type="image/png" href="<?php echo base_url($profile['icon']) ?>">
    <link href="<?php echo base_url() ?>assets/front/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?php echo base_url() ?>assets/front/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/front/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/front/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/front/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/front/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/front/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?php echo base_url() ?>assets/front/assets/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <link rel="manifest" href="<?php echo base_url('manifest.json') ?>" />

    <link rel="shortcut icon" type="image/png" href="<?php echo base_url($profile['icon']) ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('/icon-192x192.png') ?>" type="image/png">
    <meta name="theme-color" content="#30475e">
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

            <a href="<?php echo base_url() ?>" class="logo d-flex align-items-center">
                <img src="<?php echo base_url($profile['logo']) ?>">
                <!--<span><?php echo $profile['title'] ?></span>-->
            </a>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                    <li class="dropdown"><a href="<?php echo base_url('#kegiatan')?>"><span>Kegiatan</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="<?php echo base_url('#kegiatan')?>" onclick="filterOnMenu('E-Diklat')">E-DIKLAT</a></li>
                            <li><a href="<?php echo base_url('#kegiatan')?>" onclick="filterOnMenu('Seminar')">Seminar</a></li>
                            <li><a href="<?php echo base_url('#kegiatan')?>" onclick="filterOnMenu('Webinar')">Webinar</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link scrollto" href="<?php echo base_url('#cek-sertifikat')?>">Cek Sertifikat</a></li>
                    <li><a class="getstarted scrollto" href="<?php echo base_url('authentication/login')?>">Masuk</a></li>
                    <li><a class="getstarted scrollto" href="<?php echo base_url('authentication/register')?>">Daftar</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <?php
    if (!isset($_view)) {
        echo "Content not set";
    } else {
        $this->load->view($_view);
    }
    ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?php echo base_url() ?>assets/front/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="<?php echo base_url() ?>assets/front/assets/vendor/aos/aos.js"></script>
    <script src="<?php echo base_url() ?>assets/front/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>assets/front/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="<?php echo base_url() ?>assets/front/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="<?php echo base_url() ?>assets/front/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo base_url() ?>assets/front/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="<?php echo base_url() ?>assets/front/assets/js/main.js"></script>
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