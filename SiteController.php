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
            case "movie":
                $this->movie();
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
            case "leaveReview":
                $this->leaveReview(); 
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
        setcookie("currentMovie",'', time() - 3600, '/');

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

        if(isset($_POST["moviecard"])) {
            //echo $_POST["moviecard"] . "pressed";
            //header("Location: ?command=movie");
            $this->movie($_POST["moviecard"]);
            return;
        }
        if(isset($_POST["review"])){
            setcookie("currentMovie", $_POST["review"], time() + 3600, '/');
            header("Location: ?command=leaveReview");
            return; 
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

        if (isset($_POST["rate"]))
        {
            $findRating = $this->db->query("SELECT * FROM Rates WHERE userName = ? AND imdbId = ?;", "ss", $_SESSION["username"], $_POST["rate"]);
            if ($findRating === false) {
                $error_msg = "Error checking if movie has already been rated.";
            }
            // if user hasn't already rated the movie
            else if (empty($findRating))
            {
                $insertRating = $this->db->query("INSERT INTO Rates (userName, imdbId, rating) VALUES (?, ?, ?);", "sss", $_SESSION["username"], $_POST["rate"], $_POST["rating"]);
                if ($insertRating === false) {
                    $error_msg = "Error rating movie.";
                }
            }
            // if user already has a rating set, modify the existing rating
            else
            {
                $setRating = $this->db->query("UPDATE Rates SET rating = ? WHERE userName = ? AND imdbId = ?;", "sss", $_POST["rating"], $_SESSION["username"], $_POST["rate"]);
                if ($setRating === false) {
                    $error_msg = "Error rating movie.";
                }
            }
        }

        include("search.php");
    }

    public function profile() {
        $error_msg = "";

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

        $followers = $this->db->query("select * from Follows where followedUserName = ?;", "s", $_SESSION["username"]);
        if($followers === false) {
            $error_msg = "Error finding followers.";
        }

        $followed = $this->db->query("select * from Follows where followingUserName = ?;", "s", $_SESSION["username"]);
        if($followed === false) {
            $error_msg = "Error finding followed users.";
        }

        if(isset($_POST["removeFollowing"])) {
            // remove the user's following of the specified user
            $findFollowing = $this->db->query("delete from Follows where followingUserName = ? AND followedUserName = ?;", "ss", $_SESSION["username"], $_POST["removeFollowing"]);
            if($findFollowing === false) {
                $error_msg = "Your following could not be removed.";
            }
            header("Location: ?command=profile");
        }

        if(isset($_POST["follow"])) {
            $findFollow = $this->db->query("select * from Follows where followingUserName = ? and followedUserName = ?;", "ss", $_SESSION["username"], $_POST["follow"]);
            $findUser = $this->db->query("select * from Users where userName = ?;", "s", $_POST["follow"]);
            if($findFollow === false || $findUser === false) {
                $error_msg = "Follow find error.";
            }
            else if (empty($findUser))
            {
                $error_msg = "User: \"" . $_POST["follow"] . "\" not found.";
            }
            else if (empty($findFollow))
            {
                $insertFollow = $this->db->query("INSERT INTO Follows (followingUserName, followedUserName) VALUES (?, ?);", "ss", $_SESSION["username"], $_POST["follow"]);
                if($insertFollow === false) {
                    $error_msg = "Follow insertion error.";
                }
                header("Location: ?command=profile");
            }
            else
            {
                $error_msg = "Already following user: " . $_POST["follow"];
            }
        }

        include ("profile.php");
    }

    public function movie($imdbId) {

        //echo $imdbId;
        $data = $this->db->query("select * from Movies where imdbId = ?;", "s", $imdbId);
        if($data === false) {
            echo "Error fetching movie.";
        }
        $error_msg_reviews = "";
        $reviews = $this->db->query("select * from Reviews where imdbId = ?;", "s", $imdbId);
        if($reviews === false) {
            $error_msg_reviews = "An error has occured";
        } else if( empty($reviews)) {
            //no reviews
            $error_msg_reviews = "No reviews for this movie.";
        }

        $error_msg_rating = "";
        $avg_rating = $this->db->query("select AVG(rating) from Rates where imdbId = ?", "s", $imdbId);
        if($avg_rating === false) {
            $error_msg_rating = "An error has occured";
        } else if( empty($avg_rating[0]["AVG(rating)"])){
            //no reviews
            $error_msg_rating = "No ratings for this movie.";
        }



        include ("movie.php");
    }

    public function leaveReview(){
        $message = ""; 
        $error_msg = "";
        $thisData = $this->db->query("select * from Movies where imdbId = ?;", "s", $_COOKIE["currentMovie"]);
        if(isset($_COOKIE["currentMovie"])){
            $checkReviewExists = $this->db->query("select * from Reviews where imdbId = '" . $_COOKIE["currentMovie"] . "' and userName = '".  $_SESSION["username"] . "'");
            if(!empty($checkReviewExists)){
                $message = $checkReviewExists[0]["textContent"];
                if(isset($_POST["leavereview"])){
                    $update = $this->db->query("update Reviews set textContent = '" . $_POST["leavereview"] . "' where imdbId = '" . $_COOKIE["currentMovie"] . "' and userName = '".  $_SESSION["username"] . "'");
                    if($update == false){
                        $error_msg = "Error updating movie";
                    }
                    setcookie("currentMovie", "", time() - 3600, '/');
                    header("Location: ?command=search");
                }
            }
            elseif(empty($checkReviewExists)) {
                if(isset($_POST["leavereview"])){
                    $message = $_POST["leavereview"];
                    $insert = $this->db->query("insert into Reviews (imdbId, userName, textContent) values (?, ?, ?);", "sss", $_COOKIE["currentMovie"], $_SESSION["username"], $message);
                    if($insert == false){
                        $error_msg = "You have already reviewed this movie";
                    }
                    setcookie("currentMovie", "", time() - 3600, '/');
                    header("Location: ?command=search");
                }
            }
        }
        include ("leavereview.php");
    }

    public function logout() {
        session_destroy();
        header("Location: ?command=home");
    }

}