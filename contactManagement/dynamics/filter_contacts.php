<?php
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: ./login.html");
    exit;
} else {
    require_once "./connectionData.php";
    $filter_id = $_REQUEST["filter_id"];
    $connection = new Connection();
    $filteredContacts = $connection->getAllContactsByFilter($filter_id, $_SESSION["id"]);
    echo json_encode($filteredContacts);
}