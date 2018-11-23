<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$baseURL = $baseURL = str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_URI']);
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
<script>
    if (typeof window.baseURL === "undefined") {
        window.baseURL = "<?php echo $baseURL; ?>";
    }
	window.newQuote = window.baseURL + "views/quote/newQuote.php";
	window.users = window.baseURL + "views/users/users.php";
</script>
<!-- Navagation Bar -->
<nav class="navbar navbar-expand-sm navbar-light bg-light sticky-top">
    <a href="index.php" class="navbar-brand"><img class="img-fluid" src="media/images/twillithemes.png" /> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
        <div class="navbar-nav ml-auto d-sm-inline-flex">
        </div>
    </div>
</nav>
<div id="main-content" class="container-fluid">
	<div id="main">
		<div class="col">
			<div class="mainMenuSelectOptions" data-id="newQuote">
				<div class="mainMenuSelectOptionImg">
					<i class="far fa-file-alt"></i>
				</div>
				<div class="mainMenuSelectOptionDesc">
					<span>New Quote</span>
				</div>
			</div>
            <div class="mainMenuSelectOptions" data-id="users">
                <div class="mainMenuSelectOptionImg">
                    <i class="far fa-users"></i>
                </div>
                <div class="mainMenuSelectOptionDesc">
                    <span>Users</span>
                </div>
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
</script>
</body>
</html>