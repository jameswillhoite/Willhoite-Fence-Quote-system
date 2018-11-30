<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once '../../libraries/MyProject/JFactory.php';
	$baseURL = JFactory::getConfig()::BASE_URL;
	//Make sure the User is logged in and allowed to access the page
	JFactory::getSecurity()->allow(array(4), true);

	require_once PROJECT_MODEL . '/addUser.php';
	$model = new ProjectModelAddUser();
	$users = $model->getListOfUsers()->data;
	$groups = $model->getListOfGroups()->data;
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
	<title>List Users</title>
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
				<a href="<?php echo $baseURL;?>/views/users/users.php">
					<button id="main" class="btn btn-default">
						<i class="far fa-users"></i></i><span class="hidden-xs ml-1">Users</span>
					</button>
				</a>
			</div>
		</div>
	</div>
</nav>
<div id="main-content" class="container-fluid">
    <div id="mainErrorMsg" class="row">
        <div id="error_msg" class="col-12 alert"></div>
    </div>
	<div id="main">
        <div id="listOfUsers">
            <div class="row">
                <div class="col">
                    <h1 class="text-center">Users</h1>
                    <table id="users" class="table table-bordered table-responsive table-hover w-50 m-auto">
                        <thead>
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 40%;">Name</th>
                            <th style="width: 40%;">Email</th>
                            <th style="width: 25%;">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="editUser">
            <div class="row">
                <div class="col">
                    <h1 class="text-center">Edit User</h1>
                </div>
            </div>
            <!-- Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="userTab" data-toggle="tab" href="#user-tab" role="tab" aria-controls="user-tab" aria-selected="true">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#groups" role="tab" aria-controls="groups" aria-selected="false">Groups</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
        <!-- User Tab -->
                <div class="tab-pane fade show active" id="user-tab" role="tabpanel">
                    <div class="row mt-2">
                        <div class="col">
                            <button type="button" class="btn btn-primary" onclick="listUsers.updateUser();">Update</button>
                            <button type="button" class="btn btn-default btn-outline-secondary ml-1" onclick="listUsers.closeUser();">Close</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <label for="name">Name</label>
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
                </div>

        <!-- Group Tab -->
                <div class="tab-pane fade" id="groups" role="tabpanel">
                    <div class="row mt-2">
                        <div class="col">
                            <?php foreach ($groups as $g) { ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="<?php echo $g['groupID'];?>" id="groupID<?php echo $g['groupID'];?>">
                                <label for="groupID<?php echo $g['groupID'];?>" class="form-check-label"><?php echo $g['name'];?></label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="../../js/listUsers.js"></script>
<script>
    <?php foreach ($users as $v) { ?>
    listUsers.addRow("<?php echo $v['userID'];?>", "<?php echo addslashes($v['name']);?>", "<?php echo addslashes($v['email']);?>", <?php echo json_encode($v['groups']);?>);
    <?php } ?>
    listUsers.init();
</script>
</body>
</html>