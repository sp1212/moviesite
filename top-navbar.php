<!--Top navigation bar-->
<nav class="navbar navbar-expand-lg navbar-light nav-blue">
    <div class="container-fluid">
        <!--Site name / logo spot-->
        <a class="navbar-brand brand-text" href="?command=home">Moviesite</a>

        <!--Expand button used for smaller screens-->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!--Main links-->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="?command=home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="?command=search">Search</a>
                </li>
            </ul>

            <!--User info and profile links-->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <?php
                        if (isset($_SESSION["username"]))
                        {
                            echo "<a class=\"dropdown-item\" aria-current=\"page\" href=\"?command=profile\">View Profile</a>\n";
                            echo "<a class=\"dropdown-item\" aria-current=\"page\" href=\"?command=logout\">Logout</a>";
                        }
                        else
                        {
                            echo "<a class=\"dropdown-item\" aria-current=\"page\" href=\"?command=login\">Login</a>";
                            echo "<a class=\"dropdown-item\" aria-current=\"page\" href=\"?command=createaccount\">Create Acc</a>";
                        }
                    ?>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>