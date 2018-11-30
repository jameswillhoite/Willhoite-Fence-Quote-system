<?php
	$baseURL = str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_URI']);
	$msg = ($_GET['msg'] && !empty($_GET['msg'])) ? htmlspecialchars($_GET['msg']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="libraries/bootstrap/datepicker/css/bootstrap-datepicker3.min.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/main.responsive.css">
	<link rel="stylesheet" href="css/project.css">
	<link rel="stylesheet" href="css/project.responsive.css">
	<link rel="stylesheet" href="libraries/fontawesome/css/all.css">
	<link rel="shortcut icon" href="media/images/favicon.ico">
	<title>Login</title>
</head>
<body>
<script>
    if (typeof window.baseURL === "undefined") {
        window.baseURL = "<?php echo $baseURL; ?>";
    }
</script>

<div id="main-content" class="container-fluid">
    <div class="overlay"></div>
    <div class="row">
        <div class="d-block m-auto w-75 mb-2">
            <img src="media/images/logo.png" class="img-fluid">
        </div>
    </div>
	<div id="login" class="container w-50">
        <div id="mainErrorMsg" class="row">
            <div id="error_msg" class="col-12 mb-3 alert"></div>
        </div>

        <div class="row">
            <div class="col">
                <label for="username">User</label>
                <input type="email" id="username" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col">
                <button type="button" class="btn btn-primary" onclick="login.login();">Login</button>
            </div>
        </div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/login.js"></script>
<script>
    <?php if($msg) { ?>
        login.displayErrorMsg("<?php echo $msg;?>", "danger");
    <?php } ?>

    login.init();
</script>
</body>
</html>