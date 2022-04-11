<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css" />
    
    <title>Profile</title>
</head>

<body>
    <header>
        <!--Top navigation bar-->
        <div id="top-navbar-placeholder">
            <?php include ("top-navbar.php"); ?>
        </div>
    </header>

    <main>
        <div class="row bcr-name">
            <h2>User Profile</h2>
        </div>
        <div class="row">
            <div class="col-lg-2">

            </div>
            <div class="col">
                <div class="row bcr-name">
                    <h4><?=$_SESSION['username']?></h4>
                </div>
            </div>
            <div class="col-lg-2">
            </div>
        </div>
        <h4>Your Watchlist</h4>
        <div class="row justify-content-center" style="text-align: center">
                <?php
                    // loop through the array of movies returned into $data from the db query in SiteController.php under search()
                    for ($i = 0; $i < count($data); $i++)
                    {
                        echo "<div class=\"card\" style=\"width: 12rem; margin: 1rem;\">
                                <img class=\"card-img-top\" src=\"" . $data[$i]["posterPath"] . "\" alt=\"Card image cap\">
                                <div class=\"card-body\">
                                    <h5 class=\"card-title\">" . $data[$i]["title"] . "</h5>
                                </div>
                                <ul class=\"list-group list-group-flush\">
                                    <li class=\"list-group-item\">" . $data[$i]["genre"] . "</li>
                                    <li class=\"list-group-item\">" . $data[$i]["runtime"] . " minutes" . "</li>
                                    <li class=\"list-group-item\">" . $data[$i]["releaseDate"] . "</li>
                                    <form method=\"post\">
                                        <button type=\"submit\" class=\"btn btn-danger\" name=\"removeButton\" value=" . $data[$i]["imdbId"] .">Remove</button>
                                    </form>
                                </ul>
                            </div>";

                    }
                ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
        </script>
</body>

</html>