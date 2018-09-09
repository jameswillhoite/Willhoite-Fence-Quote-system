<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="libraries/bootstrap/datepicker/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="css/project.css">
    <link rel="stylesheet" href="css/project.responsive.css">
    <link rel="stylesheet" href="libraries/fontawesome/css/all.css">
    <title>Quote Fence Job</title>
</head>
<body>
<div id="main-content">
    <div class="overlay"></div>
    <!-- Navagation Bar -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="overview">
                    <h4>New Quote</h4>

                    <div id="menu-links">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">More</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="quote.addStyleMeasurement();">Add Style</a>
                            </div>
                        </div>

                        <button type="button" class="btn btn-default"><span class="fas fa-file-contract"></span>&nbsp;<span class="hidden-xs">Quote</span> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div id="error_msg" class="col-12"></div>
    </div>

    <div class="container-fluid">
        <ul class="nav nav-tabs" id="formSelection" role="tablist">
            <li class="nav-item col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                <a class="nav-link active" id="customerInfo-Tab" data-toggle="tab" href="#customerInfo" role="tab" aria-controls="customerInfo" aria-selected="true">Customer Info</a>
            </li>
            <li class="nav-item col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                <a class="nav-link" id="measurements-Tab" data-toggle="tab" href="#measurements" role="tab" aria-controls="measurements" aria-selected="false">Measurements</a>
            </li>
            <li class="nav-item col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                <a class="nav-link" id="styles-Tab" data-toggle="tab" href="#styles" role="tab" aria-controls="styles" aria-selected="false">Styles</a>
            </li>
            <li class="nav-item col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                <a class="nav-link" id="markings-Tab" data-toggle="tab" href="#marking" role="tab" aria-controls="marking" aria-selected="false">Markings (OUPS)</a>
            </li>
            <li class="nav-item col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                <a class="nav-link" id="pictures-Tab" data-toggle="tab" href="#pictures" role="tab" aria-controls="pictures" aria-selected="false">Attach Pictures</a>
            </li>
            <li class="nav-item col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                <a class="nav-link" id="drawing-Tab" data-toggle="tab" href="#draw" role="tab" aria-controls="draw" aria-selected="false">Add Drawing</a>
            </li>
        </ul>

        <div class="tab-content" id="formSelectionContent">

            <div class="tab-pane fade show active" id="customerInfo" role="tabpanel" aria-labelledby="customerInfo-Tab">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                        <label for="contractDate">Date</label>
                        <input type="date" id="contractDate" class="form-control" required />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                        <label for="jobNumber">Job Number</label>
                        <input type="text" id="jobNumber" class="form-control" placeholder="Auto Generated" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                        <label for="customerName">Customer Name</label>
                        <input type="text" id="customerName" class="form-control" pattern="[a-zA-Z\s\.]{5,}" autocomplete="off" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="address">Address</label>
                        <input type="text" id="address" class="form-control" autocomplete="off" required/>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="city">City</label>
                        <input type="text" id="city" class="form-control" autocomplete="off" required />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="taxCity">Tax City</label>
                        <input type="text" id="taxCity" class="form-control" autocomplete="off" />
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-3 col-xs-12">
                        <label for="state">ST</label>
                        <input type="text" id="state" class="form-control" pattern="[a-zA-Z]*" maxlength="2" value="OH" autocomplete="off" required />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                        <label for="zip">Zip</label>
                        <input type="number" id="zip" class="form-control" pattern="[0-9]*" maxlength="10" autocomplete="off" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="phoneChoice">Phone</label>
                        <div class="input-group">
                            <select id="phoneChoice" class="input-group-addon form-control">
                                <option value="Cell">Cell</option>
                                <option value="Husband's Cell">Husband's Cell</option>
                                <option value="Wife's Cell">Wife's Cell</option>
                                <option value="Home Phone">Home Phone</option>
                            </select>
                            <input type="tel" id="phoneChoice" class="form-control" pattern="[0-9]*" maxlength="12" autocomplete="off" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" autocomplete="off" />
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

            <div class="tab-pane fade" id="marking" role="tabpanel" aria-labelledby="markings-Tab"></div>

            <div class="tab-pane fade" id="pictures" role="tabpanel" aria-labelledby="pictures-Tab"></div>

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
    <button id="scrollToTop" title="Go to top">Top&nbsp;<span class="fas fa-arrow-up" style="color: white;"></span> </button>
</div>



<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="libraries/bootstrap/datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="js/quote.js"></script>
<script>
    jQuery(document).ready(function() {
        quote.init();
    });

</script>
</body>
</html>