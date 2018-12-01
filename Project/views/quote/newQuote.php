<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include_once "../../defines.php";
    require_once PROJECT_ROOT . "/model/project.php";
    JFactory::getSecurity()->allow(array(2), true);
    $model = new ProjectModelProject();
    $styles = $model->getStyleAll()->data;
    $postTops = $model->getPostTops()->data;
    $miscPrices = $model->getMiscPrices()->data;
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
    <link rel="stylesheet" href="../../css/main.css?v=<?php echo md5(date("YmdHis"));?>">
    <link rel="stylesheet" href="../../css/main.responsive.css">
    <link rel="stylesheet" href="../../css/project.css">
    <link rel="stylesheet" href="../../css/project.responsive.css">
    <link rel="stylesheet" href="../../libraries/fontawesome/css/all.css">
    <link rel="shortcut icon" href="../../media/images/favicon.ico">
    <title>Quote Fence Job</title>
</head>
<body>
<?php include '../../libraries/MyProject/html/mainNav.php';?>
<script>
    if (typeof window.baseURL === "undefined") {
        window.baseURL = "<?php echo $baseURL; ?>";
    }
</script>
<div class="container-fluid sticky-top">
    <div class="overview">
        <h4>New Quote</h4>
        <div id="menu-links">

            <button id="generateQuote" class="btn btn-default" onclick="quote.generateQuote();">
                <i class="fas fa-file-pdf"><span class="hidden-xs ml-1">Quote</span> </i>
            </button>

            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle col-xs-12" href="#" role="button" data-toggle="dropdown" aria-expanded="false">More</button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" onclick="quote.addStyleMeasurement();">Add Style</a>
                    <a class="dropdown-item" href="#" onclick="quote.showEmailModal();">Email Quote</a>
                </div>
            </div>

            <a href="<?php echo $baseURL;?>/views/quote/default.php">
                <button id="main" class="btn btn-default">
                    <i class="far fa-file-alt"></i><span class="hidden-xs ml-1">Quote Home</span>
                </button>
            </a>

        </div>
    </div>
</div>
<!-- Navagation Bar
<nav class="navbar navbar-light bg-white sticky-top">
    <div class="navbar-brand">New Quote</div>
    <div class="navbar-nav ml-auto">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle col-xs-12" href="#" role="button" data-toggle="dropdown" aria-expanded="false">More</button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" onclick="quote.addStyleMeasurement();">Add Style</a>
            </div>
        </div>

        <div class="input-group">
            <button id="generateQuote" class="btn btn-default btn-outline-secondary" onclick="quote.generateQuote();">
                <i class="fas fa-file-pdf"><span class="hidden-xs ml-1">Quote</span> </i>
            </button>
            <button type="button" class="btn btn-default btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" onclick="quote.showEmailModal();">Email Quote</a>
            </div>
        </div>

        <div>
            <a href="<?php echo $baseURL;?>/views/quote/default.php">
                <button id="main" class="btn btn-default">
                    <i class="far fa-file-alt"></i><span class="hidden-xs ml-1">Quote Home</span>
                </button>
            </a>
        </div>
    </div>
</nav>-->
<div id="main-content" class="container-fluid">
    <div class="overlay"></div>


    <div class="clearfix"></div>

    <div id="mainErrorMsg" class="row">
        <div id="error_msg" class="col-12 alert"></div>
    </div>

    <div id="main" class="container">
        <ul class="nav nav-tabs" id="formSelection" role="tablist">
            <li class="nav-item col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                <a class="nav-link active" id="customerInfo-Tab" data-toggle="tab" href="#customerInfo" role="tab" aria-controls="customerInfo" aria-selected="true"><i class="fas fa-user mr-1"></i>Customer Info</a>
            </li>
            <li class="nav-item col-lg-3 col-md-3 col-sm-12 col-xs-12 text-center">
                <a class="nav-link" id="measurements-Tab" data-toggle="tab" href="#measurements" role="tab" aria-controls="measurements" aria-selected="false"><i class="fas fa-ruler mr-1"></i>Measurements</a>
            </li>
            <li class="nav-item col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                <a class="nav-link" id="styles-Tab" data-toggle="tab" href="#styles" role="tab" aria-controls="styles" aria-selected="false"><i class="fas icon-picket-fence mr-1"></i>Styles</a>
            </li>
            <li class="nav-item col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                <a class="nav-link" id="pictures-Tab" data-toggle="tab" href="#pictures" role="tab" aria-controls="pictures" aria-selected="false"><i class="fas fa-image mr-1"></i>Attach Pictures</a>
            </li>
            <!--
            <li class="nav-item col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center">
                <a class="nav-link" id="drawing-Tab" data-toggle="tab" href="#draw" role="tab" aria-controls="draw" aria-selected="false"><i class="fas fa-drafting-compass mr-1 "></i>Add Drawing</a>
            </li>
            -->
        </ul>

        <div class="tab-content" id="formSelectionContent">

            <div class="tab-pane fade show active" id="customerInfo" role="tabpanel" aria-labelledby="customerInfo-Tab">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-4">
                        <label for="contractDate">Date</label>
                        <input type="text" id="contractDate" class="form-control" required />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-4">
                        <label for="jobNumber">Job Number</label>
                        <input type="text" id="jobNumber" class="form-control" placeholder="Auto Generated" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-4">
                        <label for="customerName">Customer Name</label>
                        <input type="text" id="customerName" class="form-control" pattern="[a-zA-Z\s\.]{5,}" autocomplete="off" required />
                        <div id="customerNameAutoComplete" class="autocomplete-items"></div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 d-none">
                        <span class="d-block mb-1">Name
                            <i id="chooseCustomer" class="fas fa-exchange-alt hand" title="Change Customer"></i>
                            <i id="editCustomer" class="ml-1 fas fa-edit hand" title="Edit Customer"></i>
                        </span>
                        <span class="bold-text" id="selectedCustomerName"></span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 d-none">
                        <span class="d-block mb-1">Phone</span>
                        <span class="bold-text" id="selectedCustomerPhone"></span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 d-none">
                        <span class="d-block mb-1">Email</span>
                        <span class="bold-text" id="selectedCustomerEmail"></span>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label for="address">Address</label>
                        <input type="text" id="address" class="form-control" autocomplete="off" required/>
                        <div id="customerAddressAutoComplete" class="autocomplete-items"></div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 d-none">
                        <span class="d-block mb-1">Address
                            <i id="chooseAddress" class="fas fa-exchange-alt hand" title="Change Address"></i>
                        </span>
                        <span class="bold-text" id="selectedCustomerAddress"></span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 d-none">
                        <span class="d-block mb-1">City</span>
                        <span class="bold-text" id="selectedCustomerCity"></span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 d-none">
                        <span class="d-block mb-1">Tax City</span>
                        <span class="bold-text" id="selectedCustomerTaxCity"></span>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-4 col-xs-12 d-none">
                        <span class="d-block mb-1">ST</span>
                        <span class="bold-text" id="selectedCustomerState"></span>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 d-none">
                        <span class="d-block mb-1">Zip</span>
                        <span class="bold-text" id="selectedCustomerZip"></span>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="measurements" role="tabpanel" aria-labelledby="measurements-Tab">
                <div id="stylesDiv" class="row">
                    <!-- Measurements will be inserted in here -->
                </div>
            </div>

            <div class="tab-pane fade" id="styles" role="tabpanel" aria-labelledby="styles-Tab">
                <div id="stylesDiv" class="row">
                    <!-- Styles will be inserted in here -->
                </div>
            </div>

            <div class="tab-pane fade" id="pictures" role="tabpanel" aria-labelledby="pictures-Tab">
                <div class="row">
                    <div class="col-12">
                        <fieldset>
                            <legend>Upload Image</legend>
                            <input class="form-control" id="uploadPicture" type="file" accept="image/*; capture=camera" />
                        </fieldset>

                    </div>
                </div>
                <div id="addedPictures" class="row">

                </div>
            </div>

            <div class="tab-pane fade" id="draw" role="tabpanel" aria-labelledby="drawing-Tab">
                <div id="canvas">
                    <div id="canvasTools" class="row">
                        <div class="col-2">
                            <label for="dTool">Drawing Tool</label>
                            <select id="dTool" class="form-control">
                                <option value="line" selected>Line</option>
                                <option value="rect">Rectangle</option>
                                <option value="pencil">Pencil</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="size">Line Width</label>
                            <select id="size" class="form-control">
                                <option value="1" selected>1</option>
                                <?php for($i = 2; $i < 11; $i++) {?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-3">
                            <button id="erase" class="btn-outline-secondary"><span class="fas fa-eraser"></span></button>
                        </div>
                    </div>
                    <div class="row">
                        <div id="fenceDrawingDiv" class="col-6"></div>
                        <div id="showXY""></div>
                    </div>
                </div>

        </div>
    </div>

    <!-- Add Customer Modal -->
    <div id="addCustomerModal" class="modal" tabindex="-1" role="dialog" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="error_msg" class="col-12 alert"></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text" id="fullNameLabel">Full Name</span> </div>
                                <input type="text" id="fullName" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-prepend"><select id="phoneType" class="form-control">
                                        <option value="cell">Cell</option>
                                        <option value="home">Home</option>
                                        <option value="work">Work</option>
                                    </select>
                                </div>
                                <input type="tel" id="phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">E-Mail</span> </div>
                                <input type="email" id="email" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="save" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div data-keyboa>

    <!-- Add Customer Address Modal -->
    <div id="addCustomerAddressModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="error_msg" class="col-12 alert"></div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Address</span> </div>
                                <input type="text" id="address" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">City</span> </div>
                                <input type="text" id="city" class="form-control" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">ST</span> </div>
                                <input type="text" id="state" class="form-control" value="OH" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Zip</span> </div>
                                <input type="number" id="zip" class="form-control no-spinner" pattern="/d*" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="addAddress" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Quote in Email -->
    <div id="emailQuoteModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Email Quote</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="error_msg" class="col-12 alert"></div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="emailAddress">E-mail Address</label>
                            <input id="emailAddress" type="email" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" class="form-control" value="Your Quote from Willhoite & Son's Fence Service">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="message">Message</label>
                            <textarea id="message" class="form-control">Thank you for allowing me to talk with you today. As requested, here is the quote for your fence. Any questions please don't hesitate to contact me.

Have a great day!
<?php echo JFactory::getUser()->name;?></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="sendEmail" type="button" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>


    <button id="scrollToTop" title="Go to top"><span class="fas fa-caret-up"></span> </button>
</div>



<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="../../libraries/bootstrap/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="../../js/quote.js"></script>
<script>
    jQuery(document).ready(function() {
        var temp;
        window.baseURL = "<?php echo $baseURL; ?>";

        <?php foreach ($styles as $style) { ?>
            temp = quote.MasterStyleList.addMasterStyle(
                <?php echo (int)$style['styleID'];?>,
                "<?php echo addslashes($style['style']);?>",
                "<?php echo addslashes($style['type']);?>",
                <?php echo $style['postSpacing'];?>
            );
            <?php foreach ($style['heights'] as $h) { ?>
            temp.addHeightPrice(<?php echo (int)$h['heightID'];?>, "<?php echo addslashes($h['height']);?>", <?php echo $h['pricePerFoot'];?>);
                <?php foreach ($h['gates'] as $gate) { ?>
                temp.addGateAndPrice(<?php echo (int)$gate['gateID'];?>, <?php echo (int)$h['heightID'];?>, "<?php echo $gate['gateWidth'];?>", "<?php echo $gate['gatePrice'];?>");
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php foreach ($postTops as $top) { ?>
            temp = {
                id: <?php echo (int)$top['id'];?>,
                description: "<?php echo addslashes($top['description']);?>",
                price: <?php echo (float)$top['price'];?>
            };
            quote.postTops.push(temp);
        <?php } ?>

        <?php foreach($miscPrices as $mp) { ?>
            temp = {
                id: <?php echo (int)$mp['miscID'];?>,
                description: "<?php echo addslashes($mp['description']);?>",
                price: <?php echo (double)$mp['price'];?>
            };
            quote.miscPrices.push(temp);
        <?php } ?>


        quote.init();
    });

</script>
</body>
</html>