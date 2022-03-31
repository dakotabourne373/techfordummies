# TechForDummies

## About
You stumble across this random project, what even is this???

## How To Setup
### Prerequsites
This project requires the use of [XAMPP](https://www.apachefriends.org/download.html), link included.
### Process
1. Clone the repo to a folder within `C:/xampp/htdocs/`
2. Change the variable within `index.php` in the root of the project, instructions are in the file, and I will include them below as well.
3. Turn on `Apache` and `MySQL` in the XAMPP control panel
4. import the SQL file in the root of the project in your `localhost/phpmyadmin`
   1. This will automatically create a database and include the base data for the project
5. Navigate to the base url in your browser, and the app should be working!

### Updating the url variable
This variable has been the bane of my existence since taking cs 4640, if the variable isn't correct, then the css will break and nothing except the index page will load. <br>
As such, here are the instructions: <br>
```
Change this variable to match how the url would look on your localhost's browser

e.g. on my xampp I would access it as localhost/techfordummies/, 
so i set the variable to "/techfordummies/"

if your system has sub folders you need to include them

e.g. if you access this project with localhost/cs4750/project/blue/green/, 
you would set the variable equal to "/cs4750/project/blue/green/"
```