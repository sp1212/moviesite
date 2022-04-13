<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css" />

    <title>Movie Search</title>
</head>

<body>
    <header>
        <div id="top-navbar-placeholder">
            <?php include ("top-navbar.php"); ?>
        </div>
    </header>

    <main>
        <div class="row bcr-name">
            <h1>Movie Search</h1>
        </div>
        <div class="row justify-content-center bcr-name">
            <div class="col-4">
                <form action="?command=search" method="post">
                    <div class="mb-3">
                        <label for="search" class="form-label">Title Search</label>
                        <input type="search" class="form-control" id="search" name="search"/>
                    </div>
                    <?php 
                        if (strcmp($error_msg, "") != 0)
                        {
                            echo "<div class='alert alert-danger'>" . $error_msg . "</div>";
                        }
                    ?>
                    <div class="text-center">                
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row justify-content-center" style="text-align: center">
                <?php
                    // loop through the array of movies returned into $data from the db query in SiteController.php under search()
                    for ($i = 0; $i < count($data); $i++)
                    {
                        echo "<div class=\"card\" style=\"width: 12rem; margin: 1rem;\">
                        <form method=\"post\">
                        <button class=\"text-decoration-none\" style=\"background: none; border: none;\" action=\"?command=movie\" type=\"submit\" name=\"moviecard\" value=" . $data[$i]["imdbId"] .">        
                                <img class=\"card-img-top\" src=\"" . $data[$i]["posterPath"] . "\" alt=\"Card image cap\">
                                <div class=\"card-body\">
                                    <h5 class=\"card-title\">" . $data[$i]["title"] . "</h5>
                                </div>
                                <ul class=\"list-group list-group-flush\">
                                    <li class=\"list-group-item\">" . $data[$i]["genre"] . "</li>
                                    <li class=\"list-group-item\">" . $data[$i]["runtime"] . " minutes" . "</li>
                                    <li class=\"list-group-item\">" . $data[$i]["releaseDate"] . "</li>
                                    <li class=\"list-group-item\">
                        </button>
                        </form>
                                        <form method=\"post\">
                                            <button type=\"submit\" class=\"btn btn-warning\" name=\"favorite\" value=" . $data[$i]["imdbId"] .">Favorite</button>
                                        </form>
                                    </li>
                                    <li class=\"list-group-item\">
                                        <form method=\"post\">
                                            <button type=\"submit\" class=\"btn btn-info\" name=\"watchlist\" value=" . $data[$i]["imdbId"] .">Watchlist</button>
                                        </form>
                                    </li>
                                    <li class=\"list-group-item\">
                                        <form method=\"post\">
                                            <label for=\"rating\">Rating:</label>
                                            <select id=\"rating\" name=\"rating\">
                                                <option value=\"5\">5</option>
                                                <option value=\"4\">4</option>
                                                <option value=\"3\">3</option>
                                                <option value=\"2\">2</option>
                                                <option value=\"1\">1</option>
                                            </select>
                                            <button type=\"submit\" class=\"btn btn-secondary\" name=\"rate\" value=" . $data[$i]["imdbId"] .">Rate</button>
                                        </form>
                                    </li>
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