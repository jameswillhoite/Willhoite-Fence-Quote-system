<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once '../../libraries/MyProject/JFactory.php';
	JFactory::getSecurity()->allow(array(4), true);
	$baseURL = JFactory::getConfig()::BASE_URL;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="../../libraries/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../libraries/bootstrap/datepicker/css/bootstrap-datepicker3.min.css">
	<link rel="stylesheet" href="../../css/main.css">
	<link rel="stylesheet" href="../../css/main.responsive.css">
	<link rel="stylesheet" href="../../css/project.css">
	<link rel="stylesheet" href="../../css/project.responsive.css">
	<link rel="stylesheet" href="../../libraries/fontawesome/css/all.css">
	<link rel="shortcut icon" href="../../media/images/favicon.ico">
	<title>Home</title>
</head>
<body>
<script>
    if (typeof window.baseURL === "undefined") {
        window.baseURL = "<?php echo $baseURL; ?>";
    }
</script>
<!-- Navagation Bar -->
<nav class="navbar navbar-expand-sm navbar-light bg-light sticky-top">
	<a href="<?php echo $baseURL;?>/index.php" class="navbar-brand"><img class="img-fluid" src="../../media/images/twillithemes.png" /> </a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="nav">
		<div class="navbar-nav ml-auto d-sm-inline-flex">
            <div>
                <a href="users.php">
                    <button id="main" class="btn btn-default">
                        <i class="fas fa-users"></i><span class="hidden-xs ml-1">Users</span>
                    </button>
                </a>
            </div>
		</div>
	</div>
</nav>
<div id="main-content" class="container-fluid">
    <div class="overlay"></div>

    <div id="mainErrorMsg" class="row">
        <div id="error_msg" class="col-12 alert"></div>
    </div>

	<div id="main">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <label for="name">Full Name</label>
                <input type="text" id="name" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <label for="password2">Verify Password</label>
                <input type="password" id="password2" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 mt-1">
                <button type="button" class="btn btn-primary" onclick="addUser.addUser();">
                    Submit
                </button>
            </div>
        </div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="../../js/addUser.js"></script>
<script>
    addUser.init();
</script>
</body>
</html>