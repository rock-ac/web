<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rock Anti-Cheat</title>
    <meta name="description" content="<?php echo $description ?>">
	  <meta name="keywords" content="<?php echo $keywords ?>">

    <link type="image/png" sizes="16x16" rel="icon" href="/application/public/img/ico/16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="/application/public/img/ico/32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="/application/public/img/ico/96.png">
    <link type="image/png" sizes="120x120" rel="icon" href="/application/public/img/ico/120.png">

	  <link href="/application/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/application/public/css/style.css" rel="stylesheet">
  </head>

  <body>
    <div class="navbar-nav col-lg-8 mx-auto p-4 py-md-5" style="height: 85vh;">
        <header class="d-flex align-items-center pb-3 mb-5">
          <a href="/" class="navbar-brand mx-auto d-flex align-items-center text-dark text-decoration-none">
            <span class="fs-4"><img src="/application/public/img/logo.svg" width=150px ></span>
          </a>
        </header>
        <div class="spacer"></div>
        <h1 onclick="goToCP()" style="cursor: pointer;" class="text-center main-text text">Multicomplex SA-MP anti-cheat system</h1>
        <h5 onclick="goToCP()" style="cursor: pointer;" class="text-center" style="font-size: 1.5vw;">Innovative technologies and infallible verdicts. Respond to join.</h5>
    </div>
    <hr class="col-3 col-md-4 mb-5 mx-auto">
    <script src="/application/public/js/bootstrap.bundle.min.js"></script>
    <script>
      function goToCP() {
        document.location.href="/projects/index";
      }
    </script>
  </body>
</html>
