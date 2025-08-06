<?php
//connection to mysql database

$host = "sql100.infinityfree.com";  //database host
$username = "if0_39643342";  //database user
$password = "Xzsc3xgKIDOU";    //database password
$database = "if0_39643342_dollarcash2";  //database name

$con = mysqli_connect("$host","$username","$password","$database");

if(!$con)
{
    echo 'error in connection';
}




