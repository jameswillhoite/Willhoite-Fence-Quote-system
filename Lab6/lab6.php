<!DOCTYPE html>
<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
    require_once 'game.php';
    $game = new SaveGame();
    $stats = $game->getStats();
    if($stats->error) {
        die($stats->error_msg);
    }
    $stats = $stats->data;
    $baseURL = str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_URI']);
?>
<!--
Lab6
James Willhoite
Description:
Create a Game in jQuery.
-->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="../Project/libraries/bootstrap/css/bootstrap.min.css">

    <title>Lab 6 | James Willhoite</title>

    <style>
        img {
            position: absolute;
            z-index: 998;
        }
        body > div.container-fluid {
            height: 100%;
            overflow-x: hidden;
            overflow-y: hidden;
        }
        div#main {
            cursor: url('images/finger.png'), pointer;
        }
        div#scoreBoard {
            background-color: grey;
            min-width: 150px;
            min-height: 50px;
            position: absolute;
            display: none;
            text-align: center;
            vertical-align: middle;
            color: white;
            z-index: 100;
        }
        div#scoreBoard span {
            display: block;
            height: 99%;
            margin: auto;
            color: white;
        }
        span.bubbleText {
            position: absolute;
            display: none;
            z-index: 998;
        }
        div#enterName {
            display: none;
        }
        button#submitScore {
            display: none;
        }


    </style>


</head>
<body>
<div id="main" class="container-fluid">
    <div id="scoreBoard" >Time<span id="time"></span>Score<span id="score"></span></div>
    <div id="howToPlay" class="mt-5">
        <div class="row">
            <div class="col-12 text-center">
                <p>Object of the game is to pop as many bubbles as you can before the time runs out. You only have 2 minutes. Beware of the falling needles as they vary in point deduction.</p>
                <p>Please choose your skill level below</p>
                <p>
                    <button class="btn btn-primary" onclick="game.setSkillLevel('beginner');">Beginner</button>
                    <button class="btn btn-primary" onclick="game.setSkillLevel('intermediate');">Intermediate</button>
                    <button class="btn btn-primary" onclick="game.setSkillLevel('hard');">Hard</button>
                    <button class="btn btn-primary" onclick="game.setSkillLevel('insane');">Insane</button>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 text-center m-auto">
                <table id="statsBoard" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th colspan="4" class="text-center">High Scores</th>
                    </tr>
                    <tr>
                        <th>Name</th><th>Bubbles Popped</th><th>Bubbles Lost</th><th>Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($stats as $stat) { ?>
                    <tr>
                        <td><?php echo addslashes($stat['name']);?></td>
                        <td><?php echo $stat['bubblesPopped'];?></td>
                        <td><?php echo $stat['bubblesLost'];?></td>
                        <td><?php echo $stat['score'];?></td>
                    </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="gameOverModal" class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Game Over</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="enterName" class="row">
                        <div class="col">
                            <form class="form-group">
                                <p>New High Score!!! Please enter your name.</p>
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name">
                                <div class="invalid-feedback">Please enter your name</div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p>Bubbles Popped: <span id="bubblesPopped"></span></p>
                            <p>Bubbles Lost: <span id="bubblesLost"></span></p>
                            <p>Score: <span id="score"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submitScore" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script src="../Project/libraries/bootstrap/js/bootstrap.min.js"></script>
<script src="lab6.js"></script>
<script>
    $(document).ready(function() {
        window.baseURL = "<?php echo $baseURL?>";
        var temp;
        <?php foreach ($stats as $stat) {?>
        temp = {
            id: <?php echo (int)$stat['id'];?>,
            name: "<?php echo addslashes($stat['name']);?>",
            bubblesPopped: <?php echo (int)$stat['bubblesPopped'];?>,
            bubblesLost: <?php echo (int)$stat['bubblesLost'];?>,
            score: <?php echo (int)$stat['score'];?>
        };
        game.stats.push(temp);
        <?php } ?>
        game.init();

    });
</script>
</body>
</html>