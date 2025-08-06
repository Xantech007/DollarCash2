<?php
//connection to mysql database

$host = "sql100.infinityfree.com";  //database host
$username = "if0_39194652";  //database user
$password = "uwVUy56kqsco";    //database password
$database = "if0_39194652_dollarcash2";  //database name

$con = mysqli_connect("$host","$username","$password","$database");

if(!$con)
{
    echo 'error in connection';
}




