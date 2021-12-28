<?php
require_once '../config/db.php';
include_once '../Map.php';
$action = filter_input(INPUT_POST, 'action');
session_start();
switch ($action) {

    case 'create':
        $Map = unserialize($_SESSION['Map']);
        $Map->create($_POST['x'],$_POST['y'],$conn);
        $_SESSION['Map'] = serialize($Map);
        break;
    case 'solve':
        $Map = unserialize($_SESSION['Map']);
        $Map->solveBFS();
        break;
    case 'getTable':
        $Map = unserialize($_SESSION['Map']);
        $Map->getTable($conn);
        break;
    case 'getInfo':
        $Map = new Map($conn);
        $_SESSION['Map'] = serialize($Map);
        break;
    case 'getCell':
        $Map = unserialize($_SESSION['Map']);
        echo $Map->getCell($_POST['x'], $_POST['y'], $conn);
        break;
    }

?>