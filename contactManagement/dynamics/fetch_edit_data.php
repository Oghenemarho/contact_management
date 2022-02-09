<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: ./login.html");
    exit;
} 
else {
    require_once './connectionData.php';
    $connection = new Connection();
    $contact_id = $_REQUEST["contact_id"];     
    $contact_details = $connection->getContactDetailsById($contact_id, $_SESSION["id"]);
    echo json_encode($contact_details);
}