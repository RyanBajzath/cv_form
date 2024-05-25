<?php
require_once "pdo.php";
header('Content-type: application/json');
ob_start();
$stmt = $pdo->prepare('SELECT name  FROM Institution WHERE name LIKE :prefix');
//Not sure how but the reuqest in the session is what we need
$stmt->execute(array(':prefix' => $_REQUEST['term'] . "%"));
$retval = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $retval[] = $row['name'];
}

echo (json_encode($retval, JSON_PRETTY_PRINT));
exit;

//Check what is being sent, if no institution id is recieved, send it. 