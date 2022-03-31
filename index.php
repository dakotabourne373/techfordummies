<?php
//Dakota Bourne and Matthew Reid
// https://cs4640.cs.virginia.edu/db2nb/connectuva/
// Built Angular is in templates/angular/
// Included the src for the angular app
spl_autoload_register(function($classname) {
    include "classes/$classname.php";
});

session_start();

// Parse the URL
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
//TODO: CHANGE THIS
$path = str_replace("/techfordummies/", "", $path);
$parts = explode("/", $path);
$temp = [];

$remote = new Controller();
for($i = 1; $i < count($parts); $i++){
    if($parts[$i] != ""){
        $temp[] = $parts[$i];
    }
}
$remote->run($parts[0], ...$temp);

?>