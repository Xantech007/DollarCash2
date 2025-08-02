<?php
//connection to mysql database

$host = "sql100.infinityfree.com";  //database host
$username = "if0_39620551";  //database user
$password = "hxAHJIvx3h4wJ";    //database password
$database = "if0_39620551_dollarcash2";  //database name

$con = mysqli_connect("$host","$username","$password","$database");

if(!$con)
{
    echo 'error in connection';
}




