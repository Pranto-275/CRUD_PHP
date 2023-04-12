<?php

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "crud_test";


$conn = mysqli_connect($hostname,$username,$password,$dbname);

if(!$conn){
    die("connection error".mysqli_error());
}

