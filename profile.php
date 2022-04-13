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
                    <h4><?=$_SESSION['firstName']?> <?=$_SESSION['lastName']?></h4>
                    <h4><?=$_SESSION['username']?></h4>
                </div>
            </div>
            <div class="col-lg-2">
            </div>
        </div>
        <h4>Favorite Movie</h4>
        <div class="row justify-content-center" style="text-align: center">
                <?php
                    if (empty($favMovie))
                    {
                        echo "Favorite movie not set.";
                    }
                    else
                    {
                        echo "<div class=\"card border-warning shadow-lg\" style=\"width: 12rem; margin: 1rem;\">
                                <img class=\"card-img-top\" src=\"" . $favMovie[0]["posterPath"] . "\" alt=\"Card image cap\">
                                <div class=\"card-body\">
                                    <h5 class=\"card-title\">" . $favMovie[0]["title"] . "</h5>
                                </div>
                                <ul class=\"list-group list-group-flush\">
                                    <li class=\"list-group-item\">" . $favMovie[0]["genre"] . "</li>
                                    <li class=\"list-group-item\">" . $favMovie[0]["runtime"] . " minutes" . "</li>
                                    <li class=\"list-group-item\">" . $favMovie[0]["releaseDate"] . "</li>
                                </ul>
                            </div>";
                    }
                ?>
        </div>
        <h4>Watchlist</h4>
        <div class="row justify-content-center" style="text-align: center">
                <?php
                    if (empty($data))
                    {
                        echo "Your watchlist is empty.";
                    }
                    else
                    {
                        // loop through the array of movies returned into $data from the db query in SiteController.php under search()
                        for ($i = 0; $i < count($data); $i++)
                        {
                            echo "<div class=\"card border-info shadow-lg\" style=\"width: 12rem; margin: 1rem;\">
                                    <img class=\"card-img-top\" src=\"" . $data[$i]["posterPath"] . "\" alt=\"Card image cap\">
                                    <div class=\"card-body\">
                                        <h5 class=\"card-title\">" . $data[$i]["title"] . "</h5>
                                    </div>
                                    <ul class=\"list-group list-group-flush\">
                                        <li class=\"list-group-item\">" . $data[$i]["genre"] . "</li>
                                        <li class=\"list-group-item\">" . $data[$i]["runtime"] . " minutes" . "</li>
                                        <li class=\"list-group-item\">" . $data[$i]["releaseDate"] . "</li>
                                        <li class=\"list-group-item\">
                                            <form method=\"post\">
                                                <button type=\"submit\" class=\"btn btn-danger\" name=\"removeButton\" value=" . $data[$i]["imdbId"] .">Remove</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>";

                        }
                    }
                ?>
        </div>
        <h4>Ratings</h4>
        <div class="row justify-content-center" style="text-align: center">
                <?php
                    if (empty($ratingsData))
                    {
                        echo "You haven't rated any movies.";
                    }
                    else
                    {
                        // loop through the array of movies returned into $ratingsData from the db query in SiteController.php under search()
                        for ($i = 0; $i < count($ratingsData); $i++)
                        {
                            echo "<div class=\"card shadow-lg\" style=\"width: 12rem; margin: 1rem;\">
                                    <img class=\"card-img-top\" src=\"" . $ratingsData[$i]["posterPath"] . "\" alt=\"Card image cap\">
                                    <div class=\"card-body\">
                                        <h5 class=\"card-title\">" . $ratingsData[$i]["title"] . "</h5>
                                    </div>
                                    <ul class=\"list-group list-group-flush\">
                                        <li class=\"list-group-item\">" . "Your Rating:  " . $ratingsData[$i]["rating"] . "</li>
                                        <li class=\"list-group-item\">
                                            <form method=\"post\">
                                                <button type=\"submit\" class=\"btn btn-danger\" name=\"removeRating\" value=" . $ratingsData[$i]["imdbId"] .">Remove</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>";

                        }
                    }
                ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
        </script>
</body>

</html>