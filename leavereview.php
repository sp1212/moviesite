<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css" />

    <title>Home</title>
</head>

<body>
    <header>
        <div id="top-navbar-placeholder">
            <?php include ("top-navbar.php"); ?>
        </div>
    </header>

    <main>
        <div class="row bcr-name">
            <h1>Reviewing: <?= $thisData[0]["title"] ?></h1>
            <div class = "col-4">
            <?php
                    echo "<img height=\"25%\" src=\"" . $thisData[0]["posterPath"] . "\"></img>"
            ?>
            </div>
            <div class = "col-6" style ="margin-top: 50px">
                <form id="form1" method="post" action="?command=leaveReview">
                    <h6><?= $_SESSION["firstName"]?>'s Review:<h6>
                    <textarea class = "shadow form-control" type="leavereview" id="leavereview" name = "leavereview" placeholder = "Enter your review here" maxlength="100"><?= $message ?></textarea>
                    <?php 
                        if (strcmp($error_msg, "") != 0)
                        {
                            echo "<div class='alert alert-danger'>" . $error_msg . "</div>";
                        }
                    ?>
                    <button type="submit" class = "btn btn-primary" form="form1" style = "margin-top: 50px" value="Post Review">Post Review</button>
                </form> 
                <div class="container justify-content-center mt-5 border-left border-right">
                    <h1>All Reviews</h1>
                    <?= $reviews ?>
                    <?php
                        if (strcmp($likeError, "") != 0)
                        {
                            echo "<div class='alert alert-danger'>" . $likeError . "</div>";
                        }
                        if (strcmp($dislikeError, "") != 0)
                        {
                            echo "<div class='alert alert-danger'>" . $dislikeError . "</div>";
                        }
                    ?>
                    
            </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
        </script>
</body>

</html>