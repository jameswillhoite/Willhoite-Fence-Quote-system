<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include_once "../../defines.php";
	require_once PROJECT_ROOT . "/model/project.php";
	$baseURL = JFactory::getConfig()::BASE_URL;
	//Check to make sure the user is logged in
	$security = JFactory::getSecurity();
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
	<title>Quote Main Page</title>
</head>
<body>
<?php include '../../libraries/MyProject/html/mainNav.php';?>
<script>
    if (typeof window.baseURL === "undefined") {
        window.baseURL = "<?php echo $baseURL; ?>";
    }
    window.newQuote = window.baseURL + "/views/quote/newQuote.php";
    window.search = window.baseURL + "/views/quote/search.php";
</script>
<!-- Navagation Bar -->
<div class="container-fluid sticky-top">
    <div class="overview">
        <h4>Quote Home</h4>
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
	<div class="overlay"></div>


	<div class="clearfix"></div>

	<div id="mainErrorMsg" class="row">
		<div id="error_msg" class="col-12 alert"></div>
	</div>

	<div id="main" class="container">
		<div class="row">
			<?php if($security->allow(2)) { ?>
				<div class="mainMenuSelectOptions" data-id="newQuote">
					<div class="mainMenuSelectOptionImg">
                        <i class="far fa-file-alt"></i>
					</div>
					<div class="mainMenuSelectOptionDesc">
						<span>New Quote</span>
					</div>
				</div>
			<?php  } ?>
			<div class="mainMenuSelectOptions" data-id="search">
				<div class="mainMenuSelectOptionImg">
					<i class="fas fa-search"></i>
				</div>
				<div class="mainMenuSelectOptionDesc">
					<span>Search</span>
				</div>
			</div>
		</div>
	</div>
</div>



<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="../../libraries/bootstrap/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="../../libraries/MyProject/js/scrollToTop.min.js"></script>
<script src="../../js/quote-default.js"></script>
<script>
    jQuery(document).ready(function() {
        quoteDefault.init();
    });

</script>
</body>
</html>