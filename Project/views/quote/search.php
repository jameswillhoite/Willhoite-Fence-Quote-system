<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include_once "../../defines.php";
	require_once PROJECT_ROOT . "/model/project.php";
	$baseURL = JFactory::getConfig()::BASE_URL;
	JFactory::getSecurity();
	//Get the Styles of fences
    $model = new ProjectModelProject();
    $styles = $model->getStyles()->data;
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
	<title>Search | View</title>

    <style>
        div#zipDiv, div#stylesDiv {
            display: none;
        }
    </style>
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
				<a href="<?php echo $baseURL;?>/views/quote/default.php">
					<button id="main" class="btn btn-default">
                        <i class="far fa-file-alt"></i><span class="hidden-xs ml-1">Quote Home</span>
					</button>
				</a>
			</div>
		</div>
	</div>
</nav>
<div id="main-content" class="container-fluid">
	<div class="overlay"></div>


	<div class="clearfix"></div>

	<div id="mainErrorMsg" class="row">
		<div id="error_msg" class="col-12 alert"></div>
	</div>

	<div id="main" class="container">
        <div class="row">
            <div class="col">
                <p id="dateSold"></p>
                <p id="customerName"></p>
            </div>
        </div>
	</div>

    <!-- Search Modal -->
    <div id="searchModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Search</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label for="searchBy">Search By</label>
                            <select id="searchBy" class="form-control">
                                <option value="jobID">Job Number</option>
                                <option value="customerName">Customer Name</option>
                                <option value="styleName">Style Name</option>
                                <option value="phone">Phone</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label for="searchValue">Search For</label>
                            <input type="text" id="searchValue" class="form-control" autocomplete="off">
                        </div>
                        <div id="stylesDiv" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label for="fenceStyles">Styles</label>
                            <select id="fenceStyles" class="form-control">
				                <?php foreach ($styles as $style) { ?>
                                    <option value="<?php echo $style['id'];?>"><?php echo $style['styleFence'];?></option>
				                <?php } ?>
                            </select>
                        </div>
                        <div id="zipDiv" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label for="zipCode">Zip Code <small style="font-size: 10px;">Optional</small></label>
                            <input type="number" id="zipCode" class="form-control no-spinner" autocomplete="off">
                        </div>
                    </div>

                    <div class="clearfix mt-2"></div>

                    <div class="row">
                        <div class="col">
                            <table id="searchTable" class="table table-responsive table-hover table-bordered table-light">
                                <thead>
                                <tr>
                                    <th style="width:6%;">Job Num</th><th>Customer Name</th><th>City</th><th>Zip</th><th>Style</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="clear" type="button" class="btn btn-secondary">Clear</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="search" type="button" class="btn btn-primary">Search</button>
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
<script src="../../libraries/MyProject/scrollToTop.min.js"></script>
<script src="../../js/quote-search.js"></script>
<script>
    jQuery(document).ready(function() {
        quoteSearch.init();
    });

</script>
</body>
</html>