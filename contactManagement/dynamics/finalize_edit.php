<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once './connectionData.php';
    $connection = new Connection();
    $data = new Data();
    $data->setContactData($_POST);
    $connection->addContacts($data->getContactData(), $contact_id, $_SESSION["id"]);
}
