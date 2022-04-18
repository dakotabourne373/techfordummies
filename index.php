<?php
//Dakota Bourne and Matthew Reid
spl_autoload_register(function($classname) {
    include "classes/$classname.php";
});

session_start();

/*
Change this variable to match how the url would look on your localhost's browser
e.g. on my xampp I would access it as localhost/techfordummies/, so i set the variable to "/techfordummies/"
if your system has sub folders you need to include them
e.g. if you access this project with localhost/cs4750/project/blue/green/, you would set the variable equal to "/cs4750/project/blue/green/"
*/
$url = "/techfordummies/";

// Parse the URL
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
//TODO: CHANGE THIS
$path = str_replace($url, "", $path);
$parts = explode("/", $path);
$temp = [];

$remote = new Controller($url);
for($i = 1; $i < count($parts); $i++){
    if($parts[$i] != ""){
        $temp[] = $parts[$i];
    }
}
$remote->run($parts[0], ...$temp);
