<?php
	/**
	 * Created by PhpStorm.
	 * User: jameswillhoitejr.
	 * Date: 12/1/18
	 * Time: 11:01 AM
	 */
	defined('PROJECT_ROOT') || define('PROJECT_ROOT', __DIR__ . '/../../..');
	include PROJECT_ROOT . '/defines.php';
	require_once PROJECT_ROOT . '/libraries/MyProject/JFactory.php';
	$user = JFactory::getUser();
	$baseURL = JFactory::getConfig()::BASE_URL;
	$security = JFactory::getSecurity();
	?>
<!-- Navagation Bar -->
<nav class="navbar navbar-expand-sm navbar-light bg-light">
	<a href="<?php echo $baseURL;?>/index.php" class="navbar-brand"><img class="img-fluid" src="<?PHP echo JFactory::getConfig()::BASE_URL;?>/media/images/twillithemes.png" /> </a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="nav">
		<div class="navbar-nav ml-auto d-sm-inline-flex">
			<div class="dropdown dropexpand-xs">
				<span class="navbar-text dropdown-toggle" data-toggle="dropdown">Welcome <?php echo $user->name;?> </span>
				<div class="dropdown-menu dropexpand-xs" style="z-index: 9999; width:100%">
					<a class="dropdown-item" href="<?php echo $baseURL;?>/index.php"><i class="fas fa-home"></i>Home</a>
					<a class="dropdown-item" href="<?php echo $baseURL;?>/views/quote/default.php"><i class="fas fa-file-alt"></i>Quote</a>
					<?php if($security->allow(3)) { ?>
					<a class="dropdown-item" href="<?php echo $baseURL;?>/views/users/users.php"><i class="fas fa-users"></i>Users</a>
					<?php } ?>
					<a class="dropdown-item" href="<?php echo $baseURL;?>/router.php?task=mainJS.logout"><i class="fas fa-sign-out-alt"></i>Log Out</a>
				</div>
			</div>
		</div>
	</div>
</nav>
