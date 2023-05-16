<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo $description ?>">
	<meta name="keywords" content="<?php echo $keywords ?>">
	
	<title><?php echo $title ?> | <?php echo $description ?></title>
    
	<link type="image/png" sizes="16x16" rel="icon" href="/application/public/img/ico/16.png">
	<link type="image/png" sizes="32x32" rel="icon" href="/application/public/img/ico/32.png">
	<link type="image/png" sizes="96x96" rel="icon" href="/application/public/img/ico/96.png">
	<link type="image/png" sizes="120x120" rel="icon" href="/application/public/img/ico/120.png">

    <link href="/application/public/css/main.css" rel="stylesheet">
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	
	<script src="/application/public/js/jquery.min.js"></script>
	<script src="/application/public/js/jquery.form.min.js"></script>
	<script src="/application/public/js/jquery.flot.min.js"></script>
	<script src="/application/public/js/jquery.flot.time.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="/application/public/js/main.js"></script>
	
	<!--<link href="https://unpkg.com/ionicons@4.5.10-1/dist/css/ionicons.min.css" rel="stylesheet">-->
	<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
</head>
<body>
	<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
	  <div class="container">
		  <a class="navbar-brand" href="/"><img src="/application/public/img/logo.svg" width=100px ></img></a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarHeader">
			<?php if($logged == true): ?>
			<ul class="navbar-nav ml-auto">
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarHeaderDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<ion-icon name="card"></ion-icon> 0<?php echo $user_balance ?> points
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarHeaderDropdown">
				  <a class="dropdown-item" href="/">Profile</a>
				  <a class="dropdown-item" href="/account/pay">Payment</a>
				  <a class="dropdown-item" href="/account/invoices">Payment history</a>
				  <div class="dropdown-divider"></div>
				  <a class="dropdown-item" href="/account/logout">Logout</a>
				</div>
			  </li>
			</ul>
			<?php else: ?>
			<ul class="navbar-nav"><li class="nav-item">
				<a class="nav-link" href="/account/login">Login <ion-icon name="log-in"></ion-icon></a>
			</li></ul>
			<?php endif; ?>
		  </div>
	  </div>
	</nav>
	
    <div class="container">
    	<div class="row">
			<?php if($logged == true): ?>
    		<div class="col-lg-3">
    			<div id="userNavMode">
					<h4 class="text-muted mt-3"></h4>
					<div class="list-group">
					  <a href="/projects/index" class="list-group-item list-group-item-action<?php if($activesection == "projects" && $activeitem == "index"): ?> active<?php endif; ?>"><ion-icon name="cloud"></ion-icon> My projects</a>
					  <a href="/projects/logs" class="list-group-item list-group-item-action<?php if($activesection == "logs" && $activeitem == "index"): ?> active<?php endif; ?>"><ion-icon name="list"></ion-icon> Logs</a>
					  <a href="/projects/bans" class="list-group-item list-group-item-action<?php if($activesection == "bans" && $activeitem == "index"): ?> active<?php endif; ?>"><ion-icon name="remove-circle"></ion-icon> Ban-list</a>
					</div>
					<h4 class="text-muted mt-3"></h4>
					<div class="list-group">
					  <a href="mailto:support@rockac.com?subject=RockAC Client Support&body=E-mail: <?php echo $email; ?>%0D%0AProblem: " class="list-group-item list-group-item-action"><ion-icon name="headset"></ion-icon> Support</a>
					  <a href="/account/pay" class="list-group-item list-group-item-action<?php if($activesection == "account" && $activeitem == "pay"): ?> active<?php endif; ?>"><ion-icon name="card"></ion-icon> Payment</a>
					  <a href="/account/invoices" class="list-group-item list-group-item-action<?php if($activesection == "account" && $activeitem == "invoices"): ?> active<?php endif; ?>"><ion-icon name="list"></ion-icon> Payment history</a>
					  <a href="/account/edit" class="list-group-item list-group-item-action<?php if($activesection == "account" && $activeitem == "edit"): ?> active<?php endif; ?>"><ion-icon name="settings"></ion-icon> Settings</a>
					</div>
					<?php if($user_access_level == 1): ?>
					<h4 class="text-muted mt-3"></h4>
					<div class="list-group">
						<a href="/admin/projects/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "projects"): ?> active<?php endif; ?>"><ion-icon name="cloud"></ion-icon> Все проекты</a>
					    <a href="/admin/partners/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "partners"): ?> active<?php endif; ?>"><ion-icon name="people"></ion-icon> Все партнёры</a>
					</div>
					<h4 class="text-muted mt-3"></h4>
					<div class="list-group">
						<!-- LOGS -->
						<a href="/admin/logs/module/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "modulelogs"): ?> active<?php endif; ?>"><ion-icon name="list"></ion-icon> Логи модулей</a>
						<a href="/admin/logs/memory/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "memorylogs"): ?> active<?php endif; ?>"><ion-icon name="list"></ion-icon> Логи памяти</a>
						<a href="/admin/logs/cleo/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "cleologs"): ?> active<?php endif; ?>"><ion-icon name="list"></ion-icon> Логи CLEO</a>
						<a href="/admin/logs/sf/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "sflogs"): ?> active<?php endif; ?>"><ion-icon name="list"></ion-icon> Логи SAMPFUNCS</a>
						<a href="/admin/logs/debugger/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "debuggerlogs"): ?> active<?php endif; ?>"><ion-icon name="list"></ion-icon> Логи дебаггеров</a>
						<a href="/admin/logs/process/index" class="list-group-item list-group-item-action<?php if($activesection == "admin" && $activeitem == "processlogs"): ?> active<?php endif; ?>"><ion-icon name="list"></ion-icon> Логи процессов</a>
					</div>
						</br>
					<?php endif; ?> 
				</div>
    		</div>
			<?php endif; ?>
    		<div id="content" class="col-lg-9 pt-2">
				<?php if(isset($error)): ?><div class="alert alert-danger"><strong>Error!</strong> <?php echo $error ?></div><?php endif; ?> 
				<?php if(isset($warning)): ?><div class="alert alert-warning"><strong>Alert!</strong> <?php echo $warning ?></div><?php endif; ?> 
				<?php if(isset($success)): ?><div class="alert alert-success"><strong>Success!</strong> <?php echo $success ?></div><?php endif; ?> 
