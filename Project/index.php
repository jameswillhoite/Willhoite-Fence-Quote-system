<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once 'libraries/MyProject/JFactory.php';
	//Check to make sure the user is logged in
	$security = JFactory::getSecurity();
	//Get the User
	$user = JFactory::getUser();
	//Get the session
    $session = JFactory::getSession();
    //Define the Base URL
	$baseURL = JFactory::getConfig()::BASE_URL;


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
	<title>Home</title>
</head>
<body>
<?php include 'libraries/MyProject/html/mainNav.php';?>
<script>
    if (typeof window.baseURL === "undefined") {
        window.baseURL = "<?php echo $baseURL; ?>";
    }
	window.quote = window.baseURL + "/views/quote/default.php";
	window.users = window.baseURL + "/views/users/users.php";
</script>
<div id="main-content" class="container-fluid">
    <div id="mainErrorMsg" class="row">
        <div id="error_msg" class="col-12 alert"></div>
    </div>
	<div id="main">
        <div class="row">
            <div class="col">
                <div class="mainMenuSelectOptions" data-id="quote">
                    <div class="mainMenuSelectOptionImg">
                        <i class="far fa-file-alt"></i>
                    </div>
                    <div class="mainMenuSelectOptionDesc">
                        <span>Quote</span>
                    </div>
                </div>
	        <?php if($security->allow(3)) { ?>
                <div class="mainMenuSelectOptions" data-id="users">
                    <div class="mainMenuSelectOptionImg">
                        <i class="far fa-users"></i>
                    </div>
                    <div class="mainMenuSelectOptionDesc">
                        <span>Users</span>
                    </div>
                </div>
            <?php } ?>
            </div>
		</div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/main.js"></script>
<script>
    main.init();

    <?php if($session->getVar('msg')) { ?>
    main.displayErrorMsg("<?php echo $session->getVar('msg');?>", "danger");
    <?php $session->unsetVar('msg'); }  ?>
</script>
</body>
</html>