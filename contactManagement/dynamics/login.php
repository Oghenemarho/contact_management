<?php
require_once './connectionData.php';
define("REQUIRED_FIELD_ERROR", "This field is required");
$errors = [];
$email = "";
$password = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty(trim($_POST["email"]))) {
        $errors['email'] = REQUIRED_FIELD_ERROR;
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $errors['password'] = REQUIRED_FIELD_ERROR;
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($errors)) {
        $connection = new Connection();
        $sql = "SELECT email, first_name, last_name, user_id FROM users WHERE email = :email AND password = :password";
        if ($statement = $connection->pdo->prepare($sql)) {
            $statement->bindParam(":email", $email);
            $statement->bindParam(":password", $password);

            if ($statement->execute()) { // Reminder: check emails in sign-up if they already exist in database

                $resultSet = $statement->fetchAll();
                if (count($resultSet) == 1) {
                    foreach ($resultSet as $row) {
                        session_start();
                        $_SESSION["id"] = $row["user_id"];
                        $_SESSION["first_name"] = $row["first_name"];
                        $_SESSION["last_name"] = $row["last_name"];
                        $_SESSION["email"] = $row["email"];
                        $_SESSION["logged_in"] = true;

                        var_dump($_SESSION);

                        header("location: ./contacts.php");
                        exit;
                    }
                } else {
                    echo "You have entered an invalid email address or password";
                }
            } else {
                echo "Oops! There was a problem, please try again later";
            }
        } else {
            echo "Oops! There was a problem, please try again later";
        }
        unset($statement);
        unset($connection);
    }
}
