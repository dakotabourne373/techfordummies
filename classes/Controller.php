<?php
// Dakota Bourne - db2nb
// Matthew Reid - mrr7rn

class Controller
{
    private $db;
    private $url;
    // private $url = "/db2nb/techfordummies/";

    public function __construct($newURL)
    {
        $this->db = new Database();
        $this->url = $newURL;
    }

    public function isLoggedIn()
    {
        if (!isset($_SESSION["username"])) {
            // $url .= "index.html";
            header("Location: {$this->url}index/");
            exit();
        }
    }

    public function run($command, ...$params)
    {
        switch ($command) {
            case "login":
                $this->login();
                break;
            case "signup":
                $this->signup();
                break;
            case "newPost":
                $this->isLoggedIn();
                $this->newPost();
                break;
            case "post":
                $this->post();
                break;
            case "api":
                $this->api(...$params);
                break;
                // case "jsonout":
                //     $this->isLoggedIn();
                //     $this->jsonViewer();
                //     break;
            case "profile":
                $this->profile();
                break;
            case "logout":
                $this->logout();
            case "index":
            case "posts":
            default:
                $this->index();
                break;
        }
    }

    private function post()
    {
        include "templates/post.php";
    }

    private function newPost()
    {
        include "templates/newPost.php";
    }

    private function index()
    {
        include "templates/home.php";
    }
    private function api(...$params)
    {
        switch ($params[0]) {
            case "delProfile":
                $this->isLoggedIn();
                $this->delProfile();
                break;
                // case "editProfile":
                //     $this->isLoggedIn();
                //     $this->editProfile();
                //     break;
            case "getProfile":
                $this->getProfile();
                break;
            case "getPost":
                $this->getPost();
                break;
            case "getCategories":
                $this->getCategories();
                break;
            case "addComment":
                $this->isLoggedIn();
                $this->addComment();
                break;
            case "addLikeComment":
                $this->addLikeComment();
                break;
            case "removeLikeComment":
                $this->removeLikeComment();
                break;
            case "addLike":
                $this->addLike();
                break;
            case "removeLike":
                $this->removeLike();
                break;
            case "addBookmark":
                $this->addBookmark();
                break;
            case "removeBookmark":
                $this->removeBookmark();
                break;
            case "deletePost":
                $this->deletePost();
                break;
            case "unfollowUser":
                $this->unfollowUser();
                break;
            case "followUser":
                $this->followUser();
                break;
            case "updateBio":
                $this->updateBio();
                break;
            case "removeCategoryLike":
                $this->removeCategoryLike();
                break;
            case "addCategoryLike":
                $this->addCategoryLike();
                break;
            case "getCategorizedPosts":
                if (isset($_GET["category"])) {
                    $this->getCategorizedPosts($_GET["category"]);
                } else {
                    $this->getPosts();
                }
                break;
            case "createPost":
                $this->isLoggedIn();
                $this->createPost();
                break;
            default:
                $this->index();
                break;
        }
    }

    private function addCategoryLike()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("INSERT INTO CatLikes(uid, catID) VALUES (?, ?)", "ii", $_SESSION["userID"], $_POST["catID"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function removeCategoryLike()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("DELETE FROM CatLikes WHERE uid = ? AND catID = ?", "ii", $_SESSION["userID"], $_POST["catID"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function updateBio()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("UPDATE User SET bio = ? WHERE uid = ?", "si", $_POST["bio"], $_SESSION["userID"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function followUser()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("INSERT INTO FollowedUsers(uid, fuid) VALUES (?, ?)", "ii", $_SESSION["userID"], $_POST["uid"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function unfollowUser()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("DELETE FROM FollowedUsers WHERE uid = ? AND fuid = ?", "ii", $_SESSION["userID"], $_POST["uid"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function deletePost()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }
        if ($_POST["uid"] != $_SESSION["userID"]) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("DELETE FROM Post WHERE pid = ?", "i", $_POST["pid"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function addBookmark()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("INSERT INTO Bookmarks(uid, pid) VALUES (?, ?)", "ii", $_SESSION["userID"], $_POST["pid"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function removeBookmark()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("DELETE FROM Bookmarks WHERE uid = ? AND pid = ?", "ii", $_SESSION["userID"], $_POST["pid"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function addLike()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("INSERT INTO UserLikes(uid, pid) VALUES (?, ?)", "ii", $_SESSION["userID"], $_POST["pid"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function removeLike()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("DELETE FROM UserLikes WHERE uid = ? AND pid = ?", "ii", $_SESSION["userID"], $_POST["pid"]);

        if ($ret == false) {
            $data["error"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function addLikeComment()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("INSERT INTO CommentLikes(uid, cid) VALUES (?, ?)", "ii", $_SESSION["userID"], $_POST["cid"]);

        if ($ret == false) {
            $data["pid"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function removeLikeComment()
    {
        $data = [];
        $data["error"] = false;
        if (!isset($_SESSION["userID"])) {
            $data["error"] = true;
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        $ret = $this->db->query("DELETE FROM CommentLikes WHERE uid = ? AND cid = ?", "ii", $_SESSION["userID"], $_POST["cid"]);

        if ($ret == false) {
            $data["pid"] = true;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function addComment()
    {
        if (!isset($_SESSION["userID"])) return;

        $ret = $this->db->query("INSERT INTO Comment(pid, text) VALUES (?, ?)", "is", $_POST["pid"], $_POST["text"]);
        $ret = $this->db->query("INSERT INTO UserComments(uid, cid) VALUES (?, ?)", "ii", $_SESSION["userID"], $this->db->insert_id());

        header("Content-Type: application/json");
        echo json_encode($ret, JSON_PRETTY_PRINT);
    }

    private function getPost()
    {
        $data = [];
        if (!isset($_GET["id"])) {
            $data["error"] = "true";
        } else {
            //get post details
            $ret = $this->db->query("SELECT username, uid, date_posted, title, text, total_likes FROM Post NATURAL JOIN User WHERE pid = ?", "i", $_GET["id"]);

            if ($ret != false) {
                $data["post"] = $ret;
                //check if post is liked
                if (isset($_SESSION["userID"])) {
                    $ret = $this->db->query("SELECT * FROM UserLikes WHERE uid = ? AND pid = ?", "ii", $_SESSION["userID"], $_GET["id"]);
                    $data["post"]["liked"] = count($ret) > 0 ? true : false;
                } else {
                    $data["post"]["liked"] = false;
                }

                //check if post is bookmarked
                if (isset($_SESSION["userID"])) {
                    $ret = $this->db->query("SELECT * FROM Bookmarks WHERE uid = ? AND pid = ?", "ii", $_SESSION["userID"], $_GET["id"]);
                    $data["post"]["bookmarked"] = count($ret) > 0 ? true : false;
                } else {
                    $data["post"]["bookmarked"] = false;
                }
            } else {
                $data["error"] = true;
            }

            //get comments of the post
            $ret = $this->db->query("SELECT username, uid, date_posted, text, total_likes, cid FROM Comment NATURAL JOIN UserComments NATURAL JOIN User WHERE pid = ?", "i", $_GET["id"]);

            if ($ret != false) {
                $data["comments"] = $ret;
                for ($i = 0, $size = count($data["comments"]); $i < $size; $i++) {
                    if (isset($_SESSION["userID"])) {
                        $ret = $this->db->query("SELECT * FROM CommentLikes WHERE uid = ? AND cid = ?", "ii", $_SESSION["userID"], $data["comments"][$i]["cid"]);
                        $data["comments"][$i]["liked"] = count($ret) > 0 ? true : false;
                    } else {
                        $data["comments"][$i]["liked"] = false;
                    }
                }
            } else {
                $data["error"] = false;
            }
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function createPost()
    {
        if (!isset($_SESSION["userID"])) return;
        $data = [];

        $ret = $this->db->query("INSERT INTO Post(uid, catID, title, text) VALUES (?, ?, ?, ?)", "iiss", $_POST["uid"], $_POST["cid"], $_POST["title"], $_POST["text"]);

        if ($ret != false) {
            $data["pid"] = $this->db->insert_id();
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function getCategorizedPosts($category)
    {
        $ret = $this->db->query("SELECT username, uid, date_posted, title, total_likes, pid, cname FROM Post NATURAL JOIN User NATURAL JOIN Category where catID = ? ORDER BY date_posted ASC", "i", $category);

        if ($ret != false) {
            $data = $ret;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function getPosts()
    {
        $ret = $this->db->query("select * from post");

        if ($ret != false) {
            $data = $ret;
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function getCategories()
    {
        $ret = $this->db->query("select * from category");

        if ($ret != false) {
            $data = $ret;
        }

        if (!isset($_SESSION["userID"])) {
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        for ($i = 0; $i < count($data); $i++) {
            $ret = $this->db->query("SELECT * FROM CatLikes WHERE uid = ? AND catID = ?", "ii", $_SESSION["userID"], $data[$i]["catID"]);
            if ($ret != false) {
                $data[$i]["liked"] = true;
            }
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function delProfile()
    {
        $request = file_get_contents("php://input");
        $post = json_decode($request, true);
        if (!isset($_SESSION["userID"])) {
            echo "Nice Try Dummy...";
            return;
        }
        if ($_SESSION["userID"] != $_POST["uid"]) return;
        $stmt = $this->db->query("delete from user where uid = ?", "i", $_POST["uid"]);

        $data["url"] = "{$this->url}logout/";
        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function getProfile()
    {
        $request = file_get_contents("php://input");
        $stuff = json_decode($request, true);
        $data = [];
        $error_msg = "";

        if (!isset($_POST["uid"])) return;

        //Get profile information
        $ret = $this->db->query("select username, join_date, bio from user where uid = ?", "i", $_POST["uid"]);

        if ($ret != false) {
            $data["profile"] = $ret[0];
            $data["error0"] = "";
        }

        if (!isset($_SESSION["userID"])) {
            header("Content-Type: application/json");
            echo json_encode($data, JSON_PRETTY_PRINT);
            return;
        }

        //if the current profile is the signed in user
        if ($_SESSION["userID"] == $_POST["uid"]) {
            // Get the list of my posts
            $ret = $this->db->query("SELECT username, uid, date_posted, title, total_likes, pid FROM Post NATURAL JOIN User WHERE uid = ?", "i", $_POST["uid"]);


            if ($ret != false) {

                $data["myPosts"] = $ret;
                $data["error1"] = "";
            }

            //Get the list of saved Posts
            $ret = $this->db->query("SELECT username, Post.uid, date_posted, title, total_likes, Post.pid FROM Bookmarks INNER JOIN Post ON Bookmarks.pid=Post.pid INNER JOIN User ON Post.uid = User.uid WHERE Bookmarks.uid = ?", "i", $_POST["uid"]);

            if ($ret != false) {
                $data["savedPosts"] = $ret;
                $data["error2"] = "";
            }
        } else {
            //else, check if the profile is a followed user or no
            $ret = $this->db->query("SELECT * FROM FollowedUsers WHERE uid = ? AND fuid = ?", "ii", $_SESSION["userID"], $_POST["uid"]);

            if ($ret != false) {
                $data["isFollowed"] = count($ret) > 0 ? true : false;
            } else {
                $data["isFollowed"] = false;
            }
        }

        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function profile()
    {
        include "templates/profile.php";
    }

    private function logout()
    {
        session_destroy();
        session_start();
    }

    private function login()
    {
        if (isset($_SESSION["username"])) {
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
                } else {
                    $error_msg = "You are not currently signed up. Click on the sign up button below";
                }
            }
        }
        include "templates/login.php";
    }

    private function signup()
    {
        if (isset($_SESSION["username"])) {
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
                    } else {
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
