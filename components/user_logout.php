<?php
session_start();
include 'connect.php';

setcookie('user_id' , '' , time() -1 , '/');
header('Location: ../home.php');


?>