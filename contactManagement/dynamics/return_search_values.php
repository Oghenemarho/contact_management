<?php
//public function searchContacts(string $search_value, string $filter_id, $user_id)
// "return_search_values.php?search_value=" + str +"&search_filter=" + searchFilter
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: ./login.html");
    exit;
} else {
    require_once "./connectionData.php";
    $filter_id = $_REQUEST["search_filter"];
    $search_value = $_REQUEST["search_value"];
    $connection = new Connection();
    $search_results = $connection->searchContacts($search_value, $filter_id, $_SESSION["id"]);
    echo json_encode($search_results);
}
