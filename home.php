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
            <?php
                echo "<h2>Welcome, " . $_SESSION["firstName"]."</h2>"
            ?>

            <h4>Click on the search tab to see a catalog of movies. From there, you can rate and review movies,
                 as well as add them to your watchlist.
            </h4>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
        </script>
</body>

</html>