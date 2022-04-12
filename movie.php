<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css" />

    <title>Movie</title>
</head>

<body>
    <header>
        <div id="top-navbar-placeholder">
            <?php include ("top-navbar.php"); ?>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <?php
                    echo "<h1>" . $data[0]["title"]."</h1>
                            <img height=\"25%\" src=\"" . $data[0]["posterPath"] . "\"></img>"
                            
                    ?>
                </div>
                <div class="col-8">
                    <?php
                    echo "<h4>" . $data[0]["releaseDate"] . " &#183; " . $data[0]["genre"] . " &#183; " . $data[0]["runtime"] ." mins </h4>
                        <p>" . $data[0]["overview"] ."</p>
                        "
                    ?>
                </div>
            </div>
            <div class="row">
                <h1>Reviews</h1>
                <?php 
                        if (strcmp($error_msg_reviews, "") != 0)
                        {
                            echo "<div class='alert alert-danger'>" . $error_msg_reviews . "</div>";
                        } else {
                            for ($i = 0; $i < count($reviews); $i++) {
                                echo "<p>". $reviews[$i]["textContent"] . "</p>";
                            }
                            
                        }
                ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
        </script>
</body>

</html>