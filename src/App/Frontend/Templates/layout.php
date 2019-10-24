<?php
use function OpenFram\escape_to_html as h;
use function OpenFram\u;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="apple-icon.png">
    <link rel="icon" sizes="32x32" href="favicon.ico" type="image/png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>
        <?php h($title) ?>
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>
    <!-- Extra details for Live View on GitHub Pages -->
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://www.creative-tim.com/product/material-kit"/>
    <!--  Social tags      -->
    <meta name="keywords"
          content="creative tim, creativetim, bootstrap 4, bootstrap 4 ui kit, bootstrap 4 kit, material, material kit, material template, creative tim, html kit, html css template, web template, bootstrap, bootstrap 4, css3 template, frontend, responsive bootstrap template, bootstrap ui kit, responsive ui kit">
    <meta name="description"
          content="Start Your Development With A Badass Bootstrap 4 UI Kit inspired by Material Design.">
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Material Kit by Creative Tim">
    <meta itemprop="description"
          content="Start Your Development With A Badass Bootstrap 4 UI Kit inspired by Material Design.">
    <meta itemprop="image"
          content="https://s3.amazonaws.com/creativetim_bucket/products/38/original/opt_mk_thumbnail.jpg">
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@creativetim">
    <meta name="twitter:title" content="Material Kit by Creative Tim">
    <meta name="twitter:description"
          content="Start Your Development With A Badass Bootstrap 4 UI Kit inspired by Material Design.">
    <meta name="twitter:creator" content="@creativetim">
    <meta name="twitter:image"
          content="https://s3.amazonaws.com/creativetim_bucket/products/38/original/opt_mk_thumbnail.jpg">
    <!-- Open Graph data -->
    <meta property="fb:app_id" content="655968634437471">
    <meta property="og:title" content="Material Kit by Creative Tim"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="https://demos.creative-tim.com/material-kit/index.html"/>
    <meta property="og:image"
          content="https://s3.amazonaws.com/creativetim_bucket/products/38/original/opt_mk_thumbnail.jpg"/>
    <meta property="og:description"
          content="Start Your Development With A Badass Bootstrap 4 UI Kit inspired by Material Design."/>
    <meta property="og:site_name" content="Creative Tim"/>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
          href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->

    <link href="../assets/css/material-kit.min.css" rel="stylesheet"/>
    <link href="../assets/css/home-page.css" rel="stylesheet"/>

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <!-- <link href="../assets/demo/demo.css" rel="stylesheet" /> -->
    <!-- Google Tag Manager -->
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NKDMSK6');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body class="<?php h($pageType) ?> sidebar-collapse">
<!-- Extra details for Live View on GitHub Pages -->
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->


<?php include 'navigation.php'; ?>


<?php if ($currentUser->hasFlash()) { ?>
    <div class="alert alert-success">
        <div class="container">
            <div class="alert-icon">
                <i class="material-icons">check</i>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="material-icons">clear</i></span>
            </button>
            <b>Success Alert:</b> <?php h($currentUser->getFlash()) ?>
        </div>
    </div>

<?php } ?>


<?= $content ?>


<footer class="footer footer-default">
    <div class="container">
        <nav class="float-left">
            <ul>
                <li>
                    <a href="admin/">
                        Connexion
                    </a>
                </li>
                <li>
                    <a href="/">
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="post/">
                        Articles
                    </a>
                </li>
                <li>
                    <a href="/">
                        Licenses
                    </a>
                </li>
            </ul>
        </nav>
        <div class="copyright float-right">
            &copy;
            <script>
                document.write(new Date().getFullYear())
            </script>
            , made with <i class="material-icons">favorite</i> by
            Tiguercha Djillali
        </div>
    </div>
</footer>


<!--   Core JS Files   -->
<script src="../assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="../assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="../assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="../assets/js/plugins/moment.min.js"></script>
<!--    Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<script src="../assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="../assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
<!--  Google Maps Plugin    -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGat1sgDZ-3y6fFe6HD7QUziVC6jlJNog"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!--    Plugin for Sharrre btn -->
<script src="../assets/js/plugins/jquery.sharrre.js" type="text/javascript"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/material-kit.min.js?v=2.0.6" type="text/javascript"></script>
<script>
    $(document).ready(function () {


    });


    // Facebook Pixel Code Don't Delete
    !function (f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function () {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window,
        document, 'script', '//connect.facebook.net/en_US/fbevents.js');

    try {
        fbq('init', '111649226022273');
        fbq('track', "PageView");

    } catch (err) {
        console.log('Facebook Track Error:', err);
    }
</script>
<noscript>
    <img height="1" width="1" style="display:none"
         src="https://www.facebook.com/tr?id=111649226022273&ev=PageView&noscript=1"/>
</noscript>
</body>

</html>
