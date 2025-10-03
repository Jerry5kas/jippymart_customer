<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      <?php if(str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true'){ ?> dir="rtl" <?php } ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

@if(isset($seoData))
    <!-- SEO Meta Tags -->
    @include('partials.seo')
@else
    <!-- Fallback Meta Tags -->
    <meta name="description" content="<?php echo @$_COOKIE['application_name']; ?>">
    <meta name="author" content="<?php echo @$_COOKIE['application_name']; ?>">
    <link rel="icon" type="image/png" href="<?php echo str_replace('images/','images%2F',@$_COOKIE['favicon']); ?>">
    <title><?php echo @$_COOKIE['meta_title']; ?></title>
@endif
<!-- jQuery FIRST -->
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap, plugins, and your custom scripts AFTER -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/slick/slick.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/slick/slick-theme.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/slick/slick-lightbox.css')}}"/>
    <link href="{{asset('vendor/icons/feather.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <?php if(str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true'){ ?>
    <link href="{{asset('vendor/bootstrap/css/bootstrap-rtl.min.css')}}" rel="stylesheet">
    <?php } ?>
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <?php if(str_replace('_', '-', app()->getLocale()) == 'ar' || @$_COOKIE['is_rtl'] == 'true'){ ?>
    <link href="{{asset('css/style_rtl.css')}}" rel="stylesheet">
    <?php } ?>
    <link href="{{asset('vendor/sidebar/demo.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script type="text/javascript">
        var section_colorman = '<?php echo @$_COOKIE['section_color']; ?>';
        var application_name = '<?php echo @$_COOKIE['application_name']; ?>';
        var meta_title = '<?php echo @$_COOKIE['meta_title']; ?>';
    </script>
    <!-- Font Awesome CDN - Latest version -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="{{ asset('vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700;900&display=swap" rel="stylesheet">
    
    <style>
      html, body, .font-sans, * {
        font-family: 'Outfit', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif' !important;
      }
      
      /* Icon display fixes */
      .fas, .fa, .feather {
        display: inline-block !important;
        font-style: normal !important;
        font-variant: normal !important;
        text-rendering: auto !important;
        -webkit-font-smoothing: antialiased !important;
        -moz-osx-font-smoothing: grayscale !important;
      }
      
      /* Ensure icons are visible */
      .input-group-text i,
      .form-check-label i,
      .btn i {
        opacity: 1 !important;
        visibility: visible !important;
      }
      
      /* Feather icon specific fixes */
      .feather {
        font-family: 'Feather' !important;
        font-weight: normal !important;
        font-style: normal !important;
        line-height: 1 !important;
      }
      
      /* Font Awesome specific fixes */
      .fas, .fa {
        font-family: 'Font Awesome 6 Free' !important;
        font-weight: 900 !important;
      }
      
      /* Ensure proper icon rendering */
      .fas.fa-search:before { content: "\f002"; }
      .fas.fa-clock:before { content: "\f017"; }
      .fas.fa-truck:before { content: "\f0d1"; }
      .fas.fa-percent:before { content: "\f295"; }
      .fas.fa-sliders-h:before { content: "\f3de"; }
      .fas.fa-times:before { content: "\f00d"; }
    </style>
</head>
<body class="fixed-bottom-bar">
    @yield('content')
</body>
</html>
