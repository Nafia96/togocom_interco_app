<?php

session_start();
$bd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

$uname = 'ekoue';
$pass = password_hash('dollar', PASSWORD_DEFAULT);
$rank = '3';

$insert_user = $bd->prepare('INSERT INTO aduba_users(user_name, password, rank) values(?, ?, ?)');
$insert_user->execute(array($uname, $pass, $rank));
    
?>

