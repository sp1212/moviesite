<?php
class SiteController {
    private $command;

    private $db;

    public function __construct($command) {
        $this->command = $command;
        $this->db = new Database();
    }

    public function run() {
        // start a user session if one doesn't exist
        if (session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }

        // allow user to go to createaccount page if not logged in
        if (!isset($_SESSION["username"]) && $this->command == "createaccount")
        {
            $this->command == "createaccount"; // repetitive but just continue
        }
        // disallow a logged-in user from accessing the createaccount page
        else if (isset($_SESSION["username"]) && $this->command == "createaccount")
        {
            $this->command = "home";
        }
        // disallow a logged-in user from accessing the login page
        else if (isset($_SESSION["username"]) && $this->command == "login")
        {
            $this->command = "home";
        }
        // if not logged in and trying to access a core site page (thus not caught by statements above), redirect to login
        else if (!isset($_SESSION["username"]))
        {
            $this->command = "login";
        }

        // run a specific function below based on the given command
        // command is often seen appended to the url ex."?command=search"
        // href="?command=search" can be used to run a certain command through html links
        switch($this->command) {
            case "home":
                $this->home();
                break;
            case "profile":
                $this->profile();
                break;
            case "createaccount":
                $this->createaccount();
                break;
            case "search":
                $this->search();
                break;
            case "logout":
                $this->logout();
            case "login":
            default:
                $this->login();
                break;
        }
    }
    

    // Display the login page (and handle login logic)
    public function login() {
        $error_msg = "";

        // if a username was entered
        if (isset($_POST["username"])) {
            // look for a db entry with the given username
            $data = $this->db->query("select * from Users where userName = ?;", "s", $_POST["username"]);
            $nameData = $this->db->query("select * from RealNames where userName = ?;", "s", $_POST["username"]);
            // error checking
            if ($data === false || $nameData === false) {
                $error_msg = "Error checking for user.";
            }
            // if an entry was found in the db for that username
            else if (!empty($data)) {
                // attempt to verify the password for that given username
                if (password_verify($_POST["password"], $data[0]["password"])) {
                    // set username within the session to the username entered in the form, also found in the db
                    $_SESSION["username"] = $data[0]["userName"];
                    $_SESSION["firstName"] = $nameData[0]["firstName"];
                    $_SESSION["lastName"] = $nameData[0]["lastName"];
                    // redirect to home
                    // this type of redirect not always necessary, but it is here
                    // because the session variable would otherwise not be updated until the next refresh
                    header("Location: ?command=home");
                } else {
                    // found a user in the db with that username but had the wrong password
                    $error_msg = "Invalid password.";
                }
            }
            else { // empty, no user found
                    $error_msg = "Account not found.";
            }
        }

        include ("login.php");
    }

    // similar to login functionality, but for creating a new account
    public function createaccount() {
        $error_msg = "";

        if (isset($_POST["username"])) {
            $data = $this->db->query("select * from Users where userName = ?;", "s", $_POST["username"]);
            if ($data === false) {
                $error_msg = "Error checking for user.";
            }
            else if (!empty($data)) {
                $error_msg = "Username already exists.";
            }
            else if ($_POST["password"] != $_POST["passwordconf"]) {
                $error_msg = "Password confirmation didn't match.";
            }
            else { // empty, no user found
                $insert = $this->db->query("insert into Users (userName, password) values (?, ?);", 
                        "ss", $_POST["username"], 
                        password_hash($_POST["password"], PASSWORD_DEFAULT));
                $insert2 = $this->db->query("insert into RealNames (userName, firstName, lastName) values (?, ?, ?);", 
                        "sss", $_POST["username"], $_POST["firstName"], $_POST["lastName"]);
                if ($insert === false || $insert2 === false) {
                    $error_msg = "Error inserting user";
                } else {
                    $_SESSION["username"] = $_POST["username"];
                    $_SESSION["firstName"] = $_POST["firstName"];
                    $_SESSION["lastName"] = $_POST["lastName"];
                    $_SESSION["username"] = $_POST["username"];
                    header("Location: ?command=home");
                }
            }
        }

        include ("createaccount.php");
    }

    public function home() {
        include("home.php");
    }

    public function search() {
        $error_msg = "";

        if (isset($_POST["search"]))
        {
            $param = "%{$_POST["search"]}%";
            $data = $this->db->query("select * from Movies where title like ?;", "s", $param);
            if ($data === false) {
                $error_msg = "Error finding movies.";
            }
            else if (!empty($data)) {
                // successful search
            }
            else { // empty, no movies found for specified search
                    $error_msg = "No movies containing \"" . $_POST["search"] . "\" were found.";
            }
        }
        else
        {
            // no search term entered, so return all movies
            $data = $this->db->query("select * from Movies;");
            if ($data === false) {
                $error_msg = "Error finding movies.";
            }
        }

        if (isset($_POST["favorite"]))
        {
            $findFav = $this->db->query("SELECT * FROM Favorites WHERE userName = ?;", "s", $_SESSION["username"]);
            if ($findFav === false) {
                $error_msg = "Error finding favorite movie.";
            }
            // if user doesn't already have a favorite set
            else if (empty($findFav))
            {
                $insertFav = $this->db->query("INSERT INTO Favorites (userName, imdbId) VALUES (?, ?);", "ss", $_SESSION["username"], $_POST["favorite"]);
                if ($insertFav === false) {
                    $error_msg = "Error favoriting movie.";
                }
            }
            // if user already has a favorite set, modify existing entry instead of adding a new entry
            else
            {
                $setFav = $this->db->query("UPDATE Favorites SET imdbID = ? where userName = ?;", "ss", $_POST["favorite"], $_SESSION["username"]);
                if ($setFav === false) {
                    $error_msg = "Error favoriting movie.";
                }
            }
        }

        if (isset($_POST["watchlist"]))
        {
            $findMovie = $this->db->query("SELECT * FROM Watchlists WHERE userName = ? AND imdbId = ?;", "ss", $_SESSION["username"], $_POST["watchlist"]);
            if ($findMovie === false) {
                $error_msg = "Error finding movie.";
            }
            // if user doesn't already have the movie on their watchlist
            else if (empty($findMovie))
            {
                $insertMovie = $this->db->query("INSERT INTO Watchlists (userName, imdbId) VALUES (?, ?);", "ss", $_SESSION["username"], $_POST["watchlist"]);
                if ($insertMovie === false) {
                    $error_msg = "Error watchlisting movie.";
                }
            }
        }

        include("search.php");
    }

    public function profile() {
        $data = $this->db->query("select * from Movies natural join Watchlists where userName = ?;", "s", $_SESSION['username']);
        if($data === false) {
            $error_msg = "Error finding movies.";
        }

        if(isset($_POST["removeButton"])) {
            //remove the movie from the user's watchlist that is associated with the returned imdb id
            $data = $this->db->query("delete from Watchlists where userName = ? AND imdbId = ?;", "ss", $_SESSION["username"], $_POST["removeButton"]);
            if($data === false) {
                $error_msg = "The movie couldn't be removed from your watchlist.";
            }
            header("Location: ?command=profile");
        }

        $favMovie = $this->db->query("select * from Movies natural join Favorites where userName = ?;", "s", $_SESSION["username"]);
        if($favMovie === false) {
            $error_msg = "Error finding favorite movie.";
        }

        if(isset($_POST["removeRating"])) {
            //remove the user's rating of the given movie
            $dataTemp = $this->db->query("delete from Rates where userName = ? AND imdbId = ?;", "ss", $_SESSION["username"], $_POST["removeRating"]);
            if($dataTemp === false) {
                $error_msg = "Your rating could not be removed.";
            }
            header("Location: ?command=profile");
        }

        $ratingsData = $this->db->query("select * from Movies natural join Rates where userName = ?;", "s", $_SESSION['username']);
        if($ratingsData === false) {
            $error_msg = "Error finding movies.";
        }

        include ("profile.php");
    }

    public function logout() {
        session_destroy();
        header("Location: ?command=home");
    }

}