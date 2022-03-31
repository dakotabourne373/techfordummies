<?php
// Dakota Bourne - db2nb
// Matthew Reid - mrr7rn

class Controller {
    private $db;
    private $url;
    // private $url = "/db2nb/techfordummies/";

    public function __construct($newURL) {
        $this->db = new Database();
        $this->url = $newURL;
    }

    public function isLoggedIn(){
        if(!isset($_SESSION["username"])){
            // $url .= "index.html";
            header("Location: {$this->url}index/");
            exit();
        }
    }

    public function run($command, ...$params) {
        switch($command) {
            case "login":
                $this->login();
                break;
            case "signup":
                $this->signup();
                break;
            case "api":
                // if($params[0] == "getProfileAng") {
                //     $this->getProfileAng();
                //     break;
                // }
                $this->isLoggedIn();
                $this->api(...$params);
                break;
            case "jsonout":
                $this->isLoggedIn();
                $this->jsonViewer();
                break;
            case "profile":
                $this->isLoggedIn();
                $this->profile();
                break;
            case "logout":
                $this->logout();
            case "index":
            default:
                $this->index();
                break;
        }
            
    }

    private function index(){
        include "templates/home.php";
    }
    private function api(...$params) {
        switch($params[0]) {
            case "delProfile":
                $this->delProfile();
                break;
            case "editProfile":
                $this->editProfile();
                break;
            case "getProfile":
                $this->getProfile();
                break;
            default:
                $this->index();
                break;
        }
    }

    private function delProfile() {
        $request = file_get_contents("php://input");
        $post = json_decode($request, true);
        if(!isset($_SESSION["userID"])){
            echo "Nice Try Dummy...";
            return;
        }
        if($_SESSION["userID"] != $_POST["uid"]) return;
        $stmt = $this->db->query("delete from user where uid = ?", "i", $_POST["uid"]);

        // if($stmt !== false){
        //     header("Location: {$this->url}logout/");
        //     return;
        // }
        $data["url"] = "{$this->url}logout/";
        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    
    private function getProfile() {
        $request = file_get_contents("php://input");
        $stuff = json_decode($request, true);
        $data = [];
        $error_msg = "";
        // if(!isset($_SESSION["userID"])){
        //     echo "Nice Try Dummy...";
        //     return;
        // }
        if(!isset($_POST["uid"])) return;
        // if(true/*isset($stuff["id"])*/){
        $ret = $this->db->query("select username, join_date, bio from user where uid = ?", "i", $_POST["uid"]);

        if($ret != false){
            $data["username"] = $ret[0]["username"];
            $data["join_date"] = $ret[0]["join_date"];
            $data["bio"] = $ret[0]["bio"];
            $data["error"] = "";
        }
        // }
        // $data["domwad"] = "djwandwa";
        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function editProfile() {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        if(!isset($_SESSION["userID"]))
            echo "Nice Try Dummy...";
        if(isset($data["name"])){
            $res = $this->db->query("update users set name = ? where id = ?", "si", $data["name"], $_SESSION["userID"]);
        }
    }

    private function jsonViewer(){
        $data = $this->db->query("select * from research where id = ?;", "i", $_GET["id"]);
        $item = [];
        $user = [];
        $message = "";
        if ($data === false) {
        // did not work
        $message = "<div class='alert alert-danger'>Error: could not find the given research item</div>";
        } else {
            // worked
            if (count($data)==0) {
                $message = "<div class='alert alert-danger'>Error: could not find the given research item</div>";
            }else{
                $item = $data[0];
                $data = $this->db->query("select * from users where id = ?", "i", $item["userID"]);
                
                if($data !== false){
                    if(isset($data[0])){
                        $user = $data[0];
                    }
                }
            }
        }
        $item["name"] = $user["name"];
        $item["uid"] = $user["id"];
        $item["email"] = $user["email"];
        
        header('Content-Type: application/json');
        echo json_encode($item, JSON_PRETTY_PRINT);
    }

    private function profile(){
        include "templates/profile.php";
    }

    private function logout(){
        session_destroy();
        session_start();
    }

    private function login(){
        if(isset($_SESSION["username"])){
            header("Location: {$this->url}index/");
            return;
        }
        $error_msg = "";
        if (isset($_POST["username"])) { /// validate the email coming in
            $data = $this->db->query("select * from user where username = ?;", "s", $_POST["username"]);
            if ($data === false) {
                $error_msg = "Error checking for user";
            } else {         
                if (!empty($data)) { 
                    // user was found!
                    
                    // validate the user's password
                    if (password_verify($_POST["password"], $data[0]["password"])) {
                        // Save user information into the session to use later
                        $_SESSION["username"] = $data[0]["username"];
                        $_SESSION["userID"] = $data[0]["uid"];

                        header("Location: {$this->url}index/");
                        return;
                    } else {
                        // User was found but entered an invalid password
                        $error_msg = "Invalid Password";
                    }
                }else{
                    $error_msg = "You are not currently signed up. Click on the sign up button below";
                }
            }
        }
        include "templates/login.php";
    }

    private function signup(){
        if(isset($_SESSION["username"])){
            header("Location: {$this->url}index/");
            return;
        }
        $error_msg = "";
        if (isset($_POST["name"])) { /// validate the email coming in
            $data = $this->db->query("select * from user where username = ?;", "s", $_POST["name"]);
            if ($data === false) {
                $error_msg = "Error checking for user";
            } else {         
                if (!empty($data)) {
                    // user was found!
                    $error_msg = "User already exists!";
                } else {
                    // user was not found, create an account
                    // NEVER store passwords into the database, use a secure hash instead:
                    $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
                    $insert = $this->db->query("insert into user (username, password) values (?, ?);", "ss", $_POST["name"], $hash);
                    if ($insert === false) {
                        $error_msg = "Error creating new user";
                    }else{
                    // Save user information into the session to use later
                    $_SESSION["username"] = $_POST["name"];
                    $_SESSION["userID"] = $this->db->insert_id();

                    header("Location: {$this->url}index/");
                    return;
                    }
                } 
            }
        }  
        include "templates/signup.php";
    }
}
?>