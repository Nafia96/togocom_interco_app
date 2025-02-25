<?php

define("LIGNES_PAR_PAGE", 10);

session_start();
$bdd = new PDO('mysql:host=localhost;dbname=inter_traffic;','Denis', 'Kaes@2021');

if(!$_SESSION['pass']){
	header('Location: auth.php');
}

$query = "SELECT a_number, b_number, start_time, in_trunk_id, out_trunk_id, return_code, call_duration FROM live_record";
$queryCount = "SELECT COUNT(start_time) as count FROM live_record";
$params = [];
$sortable = ["a_number", "b_number", "start_time", "in_trunk_id", "out_trunk_id", "return_code", "call_duration"];

//Recherche par "a_number", "b_number", "start_time", "in_trunk_id", "out_trunk_id", "return_code", "call_duration"
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6']) ){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $params['a_number'] = $_GET['q1'] . '%';
	$params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';  
    $params['out_trunk_id'] = $_GET['q5'] . '%'; 
    $params['return_code'] = $_GET['q6']; 
}

//Recherche par a_number
if(!empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number";
    $queryCount .= " WHERE a_number LIKE :a_number";
	$params['a_number'] = $_GET['q1'] . '%';
}

//Recherche par b_number
if(empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number";
    $queryCount .= " WHERE b_number LIKE :b_number";
	$params['b_number'] = $_GET['q2'] . '%';
}

//Recherche par start_time
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
    $query .= " WHERE start_time LIKE :start_time";
    $queryCount .= " WHERE start_time LIKE :start_time";
    $params['start_time'] = $_GET['q3'] . '%';
}

//Recherche par in_trunk_id
if(empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE in_trunk_id LIKE :in_trunk_id";
    $queryCount .= " WHERE in_trunk_id LIKE :in_trunk_id";
	$params['in_trunk_id'] = $_GET['q4'] . '%';
}

//Recherche par out_trunk_id
if(empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE out_trunk_id LIKE :out_trunk_id";
	$params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par return_code
if(empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE return_code LIKE :return_code";
    $queryCount .= " WHERE return_code LIKE :return_code";
	$params['return_code'] = $_GET['q6'];
}


//Recherche par a_number, b_number
if(!empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
}

//Recherche par a_number, start_time
if(!empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND start_time LIKE :start_time";
    $queryCount .= " WHERE a_number LIKE :a_number AND start_time LIKE :start_time";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
}

//Recherche par a_number, in_trunk_id
if(!empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND in_trunk_id LIKE :in_trunk_id";
    $queryCount .= " WHERE a_number LIKE :a_number AND in_trunk_id LIKE :in_trunk_id";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
}

//Recherche par a_number, out_trunk_id
if(!empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE a_number LIKE :a_number AND out_trunk_id LIKE :out_trunk_id";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par a_number, return_code
if(!empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND return_code LIKE :return_code";
    $queryCount .= " WHERE a_number LIKE :a_number AND return_code LIKE :return_code";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par b_number, start_time
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time";
    $queryCount .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
}

//Recherche par b_number, in_trunk_id
if(empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id";
    $queryCount .= " WHERE b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
}

//Recherche par b_number, out_trunk_id
if(empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE b_number LIKE :b_number AND out_trunk_id LIKE :out_trunk_id";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par b_number, return_code
if(empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND return_code = :return_code";
    $queryCount .= " WHERE b_number LIKE :b_number AND return_code = :return_code";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par start_time, in_trunk_id
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id";
    $queryCount .= " WHERE start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id";
	$params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
}

//Recherche par start_time, out_trunk_id
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE start_time LIKE :start_time AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE start_time LIKE :start_time AND out_trunk_id LIKE :out_trunk_id";
	$params['start_time'] = $_GET['q3'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par start_time, return_code
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE start_time LIKE :start_time AND return_code LIKE :return_code";
    $queryCount .= " WHERE start_time LIKE :start_time AND return_code LIKE :return_code";
	$params['start_time'] = $_GET['q3'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par in_trunk_id, out_trunk_id
if(empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
	$params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par in_trunk_id, return_code
if(empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
	$params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par out_trunk_id, return_code
if(empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
	$params['out_trunk_id'] = $_GET['q5'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par a_number, b_number, start_time
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
}

//Recherche par a_number, b_number, in_trunk_id
if(!empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
}

//Recherche par a_number, b_number, out_trunk_id
if(!empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND out_trunk_id LIKE :out_trunk_id";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par a_number, b_number, return_code
if(!empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND return_code LIKE :return_code";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND return_code LIKE :return_code";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par b_number, start_time, in_trunk_id
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id";
    $queryCount .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
}

//Recherche par b_number, start_time, out_trunk_id
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND out_trunk_id LIKE :out_trunk_id";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par b_number, start_time, return_code
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND return_code LIKE :return_code";
    $queryCount .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND return_code LIKE :return_code";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par b_number, in_trunk_id, out_trunk_id
if(empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par b_number, in_trunk_id, return_code
if(empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par start_time, in_trunk_id, out_trunk_id
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
	$params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par start_time, in_trunk_id, return_code
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
	$params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par in_trunk_id, out_trunk_id, return_code
if(empty($_GET['q1']) and empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
	$params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par a_number, b_number, start_time, in_trunk_id
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
}

//Recherche par a_number, b_number, start_time, out_trunk_id
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND out_trunk_id LIKE :out_trunk_id";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par a_number, b_number, start_time, return_code
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND return_code LIKE :return_code";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND return_code LIKE :return_code";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par b_number, start_time, in_trunk_id, out_trunk_id
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par b_number, start_time, in_trunk_id, return_code
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par start_time, in_trunk_id, out_trunk_id, return_code
if(empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
    $params['return_code'] = $_GET['q6'];
}


//Recherche par a_number, b_number, start_time, in_trunk_id, out_trunk_id
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
}

//Recherche par b_number, start_time, in_trunk_id, out_trunk_id, return_code
if(empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
	$params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par a_number, start_time, in_trunk_id, out_trunk_id, return_code
if(!empty($_GET['q1']) and empty($_GET['q2']) and !empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE a_number LIKE :a_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par a_number, b_number, in_trunk_id, out_trunk_id, return_code
if(!empty($_GET['q1']) and !empty($_GET['q2']) and empty($_GET['q3']) and !empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND in_trunk_id LIKE :in_trunk_id AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par a_number, b_number, start_time, out_trunk_id, return_code
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND out_trunk_id LIKE :out_trunk_id AND return_code LIKE :return_code";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['out_trunk_id'] = $_GET['q5'] . '%';
    $params['return_code'] = $_GET['q6'];
}

//Recherche par a_number, b_number, start_time, in_trunk_id, return_code
if(!empty($_GET['q1']) and !empty($_GET['q2']) and !empty($_GET['q3']) and empty($_GET['q4']) and !empty($_GET['q5']) and !empty($_GET['q6'])){
	$query .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
    $queryCount .= " WHERE a_number LIKE :a_number AND b_number LIKE :b_number AND start_time LIKE :start_time AND in_trunk_id LIKE :in_trunk_id AND return_code LIKE :return_code";
	$params['a_number'] = $_GET['q1'] . '%';
    $params['b_number'] = $_GET['q2'] . '%';
    $params['start_time'] = $_GET['q3'] . '%';
    $params['in_trunk_id'] = $_GET['q4'] . '%';
    $params['return_code'] = $_GET['q6'];
}

// organisation
if(!empty($_GET['sort']) && in_array($_GET['sort'], $sortable)){
    $sens = $_GET['sens'] ?? 'asc';
    if(!in_array($sens, ['asc', 'desc'])){
        $sens = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $sens";
}

//Pagination
$page = (int)($_GET['p'] ?? 1);
$offset = ($page-1) * LIGNES_PAR_PAGE;

$query .= " LIMIT " . LIGNES_PAR_PAGE . " OFFSET $offset";

$statement = $bdd->prepare($query);
$statement->execute($params);
$destinations = $statement->fetchAll();
/*value="<?= htmlentities($_GET['q'] ?? null)?>"*/

$statement = $bdd->prepare($queryCount);
$statement->execute($params);
$count = (int)$statement->fetch()['count'];
$pages = ceil($count / LIGNES_PAR_PAGE);


class URLHelper {
    public static function withParam(array $data, string $param, $value): string{
        return http_build_query(array_merge($data, [$param => $value]));
    }
    public static function withParams(array $data, array $params): string {
        return http_build_query(array_merge($data, $params));
    }
} 


class TableHelper{
    const SORT_KEY = 'sort';
    const SENS_KEY = 'sens';

    public static function sort(string $sortKey, string $label, array $data): string{
        $sort = $data[self::SORT_KEY] ?? null;
        $sens = $data[self::SENS_KEY] ?? null;
        $icon = "";
        if($sort === $sortKey){
            $icon = $sens === 'asc' ? "^" : 'v';
        }
        $url = URLHelper::withParams($data, [
            'sort' => $sortKey, 
            'sens' => $sens === 'asc' && $sort === $sortKey ? 'desc' : 'asc'
        ]);
        return <<<HTML
        <a href="?$url">$label $icon</a>
HTML;
    }
}

?>
<?php
    require("live_header.php");
?>

<h2>CDR Tracing </h2>
<br/>

<table> 
<tr>
                        <td> <div class="form-group">
                            <input type="text" class="form-control" name="q1" placeholder="a number" value="<?= htmlentities($_GET['q1'] ?? null)?>">		
                        </div></td>
                       <td> <div class="form-group">
                            <input type="text" class="form-control" name="q2" placeholder="b number" value="<?= htmlentities($_GET['q2'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="text" class="form-control" name="q3" placeholder="start time" value="<?= htmlentities($_GET['q3'] ?? null)?>">		
                        </div></td>
                        <td><div class="form-group">
                            <input type="text" class="form-control" name="q4" placeholder="in trunk id" value="<?= htmlentities($_GET['q4'] ?? null)?>">		
			</div></td>
            <td><div class="form-group">
                            <input type="text" class="form-control" name="q5" placeholder="out trunk id" value="<?= htmlentities($_GET['q5'] ?? null)?>">		
			</div></td>
            <td><div class="form-group">
                            <input type="text" class="form-control" name="q6" placeholder="return code" value="<?= htmlentities($_GET['q6'] ?? null)?>">		
			</div></td>
			<td><div class="generer">
				 <button class="btn btn-primary">Generate</button>
			</div></td>
</tr>
</table>
		    </form>
		<div class="tableau" >
		    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= TableHelper::sort('a_number', 'a_number', $_GET) ?></th>
                                <th><?= TableHelper::sort('b_number', 'b_number', $_GET) ?></th>
                                <th><?= TableHelper::sort('start_time', 'start_time', $_GET) ?></th>	
                                <th><?= TableHelper::sort('in_trunk_id', 'in_trunk_id', $_GET) ?></th>	
                                <th><?= TableHelper::sort('out_trunk_id', 'out_trunk_id', $_GET) ?></th>                       
                                <th><?= TableHelper::sort('return_code', 'return_code', $_GET) ?></th>	
                                <th><?= TableHelper::sort('call_duration', 'call_duration', $_GET) ?></th>	
                                
                            </tr>			
			</thead>

                        <tbody>  
                            <?php foreach($destinations as $dest): ?>
                                <tr>
                                    <td><?= $dest['a_number']?></td>
                                    <td><?= $dest['b_number']?></td>
                                    <td><?= $dest['start_time']?></td>
                                    <td><?= $dest['in_trunk_id']?></td>	
                                    <td><?= $dest['out_trunk_id']?></td>	
                                    <td><?= $dest['return_code']?></td>	
                                    <td><?= $dest['call_duration']?></td>	
                                </tr>
			    <?php endforeach ?>	
			</tbody>
		     </table>
		</div>

                    <?php if ($pages > 1 && $page > 1): ?>
                        <a href="?<?= URLHelper::withParam($_GET, "p", $page - 1) ?>" class="btn btn-primary">Preview page</a>
                    <?php endif ?>

                    <?php if ($pages > 1 && $page < $pages): ?>
                        <a href="?<?= URLHelper::withParam($_GET, "p", $page + 1) ?>"  class="btn btn-primary">Next page</a>
                    <?php endif ?>

                </main>
            </div>
        </div>

        <script src="/docs/5.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
    
    </body>

</html>
