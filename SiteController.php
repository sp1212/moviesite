<?php
class SiteController {
    private $command;

    private $db;

    public function __construct($command) {
        $this->command = $command;
        $this->db = new Database();
    }

    public function run() {
        if (session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }

        if (!isset($_SESSION["username"]) && $this->command == "createaccount")
        {
            $this->command == "createaccount"; // repetitive but just continue
        }
        else if (isset($_SESSION["username"]) && $this->command == "createaccount")
        {
            $this->command = "home";
        }
        else if (isset($_SESSION["username"]) && $this->command == "login")
        {
            $this->command = "home";
        }
        else if (!isset($_SESSION["username"]))
        {
            $this->command = "login";
        }

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

        if (isset($_POST["username"])) {
            $data = $this->db->query("select * from Users where userName = ?;", "s", $_POST["username"]);
            if ($data === false) {
                $error_msg = "Error checking for user.";
            }
            else if (!empty($data)) {
                if (password_verify($_POST["password"], $data[0]["password"])) {
                    $_SESSION["username"] = $data[0]["userName"];
                    header("Location: ?command=home");
                } else {
                    $error_msg = "Invalid password.";
                }
            }
            else { // empty, no user found
                    $error_msg = "Account not found.";
            }
        }

        include ("login.php");
    }

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
                if ($insert === false) {
                    $error_msg = "Error inserting user";
                } else {
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

        include("search.php");
    }

    public function profile() {
        include ("profile.php");
    }

    public function logout() {
        session_destroy();
        header("Location: ?command=home");
    }

}