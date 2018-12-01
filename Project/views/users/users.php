<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	require_once '../../libraries/MyProject/JFactory.php';
	$baseURL = JFactory::getConfig()::BASE_URL;
	//Make sure the User is logged in and allowed to access the page
	JFactory::getSecurity()->allow(array(3), true);
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
<?php include '../../libraries/MyProject/html/mainNav.php';?>
<script>
    if (typeof window.baseURL === "undefined") {
        window.baseURL = "<?php echo $baseURL; ?>";
    }
    window.addUser = baseURL + "/views/users/addUser.php";
    window.listUsers = baseURL + "/views/users/listUsers.php";
</script>
<!-- Navagation Bar -->
<div class="container-fluid sticky-top">
    <div class="overview">
        <h4>Users</h4>
        <div id="menu-links">

            <a href="<?php echo $baseURL;?>/index.php">
                <button id="main" class="btn btn-default">
                    <i class="fas fa-home"></i><span class="hidden-xs ml-1">Home</span>
                </button>
            </a>

        </div>
    </div>
</div>

<div id="main-content" class="container-fluid">
	<div id="main">
		<div class="col">
			<div class="mainMenuSelectOptions" data-id="addUser">
				<div class="mainMenuSelectOptionImg">
					<i class="far fa-user"></i>
				</div>
				<div class="mainMenuSelectOptionDesc">
					<span>Add User</span>
				</div>
			</div>
            <div class="mainMenuSelectOptions" data-id="listUsers">
                <div class="mainMenuSelectOptionImg">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="mainMenuSelectOptionDesc">
                    <span>List Users</span>
                </div>
            </div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="../../js/users-main.js"></script>
<script>
    users.init();
</script>
</body>
</html>