<?php

define("DB_SERVER","localhost");
define("DB_USERNAME","root");
define("DB_PASSWORD","");
define("DB_NAME","library");

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link === FALSE){
    echo "not connected!";
    die("Error: could not connect!" . mysqli_connect_error());
}
else{
    echo " ";
}


