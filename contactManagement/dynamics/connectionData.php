<?php

class Connection
{
    public PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("sqlsrv:Server=DESKTOP-MIE3511,1434;Database=ContactsDb", "sa", "_____");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getData()
    {
        $statement = $this->pdo->prepare("SELECT * FROM contacts");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editContact(array $newData, int $contact_id, int $user_id)
    {
        $conn = $this->pdo;
        try {
            $conn->beginTransaction();
            $statement = $conn->prepare("UPDATE contacts SET first_name = :first_name, last_name = :last_name, address = :address, 
            additional_info = :additional_info, contact_group = :contact_group, favorite = :favorite WHERE contact_id = $contact_id AND 
            user_id = $user_id");
            $statement->bindParam(":first_name", $newData["first_name"]);
            $statement->bindParam(":last_name", $newData["last_name"]);
            $statement->bindParam(":address", $newData["house_address"]);
            $statement->bindParam(":additional_info", $newData["additional_info"]);
            $statement->bindParam(":contact_group", $newData["contact_group"]);
            $statement->bindParam(":favorite", $newData["favorite"]);

            $statement->execute();

            $statement = $conn->prepare("DELETE FROM mobile WHERE contact_id = $contact_id AND user_id = $user_id");
            $statement->execute();

            $statement = $conn->prepare("DELETE FROM emails WHERE contact_id = $contact_id AND user_id = $user_id");
            $statement->execute();

            foreach ($newData["mobile"] as $mobile) {
                $statement = $conn->prepare("INSERT INTO mobile (mobile_number, contact_id, user_id) VALUES (:mobile_number, $contact_id, $user_id)");
                $statement->bindParam(":mobile_number", $mobile);
                $statement->execute();
            }

            foreach ($newData["email"] as $email) {
                $statement = $conn->prepare("INSERT INTO emails (email_address, contact_id, user_id) VALUES (:email_address, $contact_id, $user_id)");
                $statement->bindParam(":email_address", $email);
                $statement->execute();
            }

            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
        }
    }
    /*
    Used in add_contacts.php
    For fetching values from the values from a Data object and using them in a database transaction
    */
    public function addContacts(array $contactData, int $user_id)
    {
        $conn = $this->pdo;
        try {
            // $sql = "INSERT INTO contacts (first_name, last_name, contact_group, user_id) VALUES
            // ('Joy', 'Ogun', 1, 1)";

            // $conn->exec($sql);
            // return (int) $conn->lastInsertId();

            $contact_id = null;
            $conn->beginTransaction();
            $statement = $conn->prepare("INSERT INTO contacts (first_name, last_name, address, additional_info, contact_group, favorite, user_id)
            VALUES (:first_name, :last_name, :address, :additional_info, :contact_group, :favorite, $user_id)");

            $statement->bindParam(":first_name", $contactData["first_name"]);
            $statement->bindParam(":last_name", $contactData["last_name"]);
            $statement->bindParam(":address", $contactData["house_address"]);
            $statement->bindParam(":additional_info", $contactData["additional_info"]);
            $statement->bindParam(":contact_group", $contactData["contact_group"]);
            $statement->bindParam(":favorite", $contactData["favorite"]);

            $statement->execute();
            $contact_id = $conn->lastInsertId();

            foreach ($contactData["mobile"] as $mobile) {
                $statement = $conn->prepare("INSERT INTO mobile (mobile_number, contact_id, user_id) VALUES (:mobile_number, $contact_id, $user_id)");
                $statement->bindParam(":mobile_number", $mobile);
                $statement->execute();
            }

            foreach ($contactData["email"] as $email) {
                $statement = $conn->prepare("INSERT INTO emails (email_address, contact_id, user_id) VALUES (:email_address, $contact_id, $user_id)");
                $statement->bindParam(":email_address", $email);
                $statement->execute();
            }

            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollback();
        }
    }

    // Takes the user_id from the session or any other source and returns the contacts for that user  
    // REMINDER: Add a second parameter that would be used in filtering the different categories of users  
    public function getAllContacts(string $user_id)
    {
        $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE user_id = $user_id ORDER BY first_name");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchContacts(string $search_value, string $filter_id, $user_id)
    {
        switch ($filter_id) {
            case -1:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE user_id = $user_id AND 
                (first_name LIKE '$search_value%' OR last_name LIKE '$search_value%') ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case -2:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE favorite = 1 AND user_id = $user_id AND 
                (first_name LIKE '$search_value%' OR last_name LIKE '$search_value%') ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 1:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 1 AND user_id = $user_id AND 
                (first_name LIKE '$search_value%' OR last_name LIKE '$search_value%') ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 2:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 2 AND user_id = $user_id AND 
                (first_name LIKE '$search_value%' OR last_name LIKE '$search_value%') ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 3:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 3 AND user_id = $user_id AND 
                (first_name LIKE '$search_value%' OR last_name LIKE '$search_value%') ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 4:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 4 AND user_id = $user_id AND 
                (first_name LIKE '$search_value%' OR last_name LIKE '$search_value%') ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 5:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 5 AND user_id = $user_id AND 
                (first_name LIKE '$search_value%' OR last_name LIKE '$search_value%') ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 6:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 6 AND user_id = $user_id AND 
                (first_name LIKE '$search_value%' OR last_name LIKE '$search_value%') ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            default:
                return;
                break;
        }
    }

    public function getAllContactsByFilter(string $filter_id, string $user_id)
    {
        switch ($filter_id) {
            case -1:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE user_id = $user_id ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case -2:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE favorite = 1 AND user_id = $user_id ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 1:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 1 AND user_id = $user_id ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 2:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 2 AND user_id = $user_id ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 3:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 3 AND user_id = $user_id ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 4:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 4 AND user_id = $user_id ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 5:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 5 AND user_id = $user_id ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 6:
                $statement = $this->pdo->prepare("SELECT contact_id, first_name, last_name FROM contacts WHERE contact_group = 6 AND user_id = $user_id ORDER BY first_name");
                $statement->execute();
                return $statement->fetchAll(PDO::FETCH_ASSOC);
                break;

            default:
                return;
                break;
        }
    }

    public function getContactDetailsById(string $contact_id, string $user_id)
    {
        $contactDetails = null;
        $statement = $this->pdo->prepare("SELECT * FROM contacts WHERE contact_id = $contact_id AND user_id = $user_id");
        $statement->execute();
        $contactDetails = $statement->fetch(PDO::FETCH_ASSOC);
        $statement = $this->pdo->prepare("SELECT mobile_number FROM mobile WHERE contact_id = $contact_id AND user_id = $user_id");
        $statement->execute();

        // $contactDetails["mobile"] = $statement->fetchAll(PDO::FETCH_NUM);
        $mobile_array = [];
        foreach ($statement->fetchAll(PDO::FETCH_NUM) as $mobiles) {
            foreach ($mobiles as $mobile) {
                $mobile_array[] = $mobile;
            }
        }
        $contactDetails["mobile"] = $mobile_array;


        $statement = $this->pdo->prepare("SELECT email_address FROM emails WHERE contact_id = $contact_id AND user_id = $user_id");
        $statement->execute();

        $emails_array = [];
        foreach ($statement->fetchAll(PDO::FETCH_NUM) as $emails) {
            foreach ($emails as $email) {
                $emails_array[] = $email;
            }
        }
        $contactDetails["emails"] = $emails_array;

        return $contactDetails;
    }

    public function deleteContactById(string $contact_id, string $user_id)
    {
        // signup page, complete login page, search, delete, edit
        $this->pdo->exec("DELETE FROM contacts WHERE contact_id = $contact_id AND user_id = $user_id");
    }
}

class Data
{
    private string $first_name;
    private string $last_name;
    private array $mobile;
    private array $email;
    private int $contact_group;
    private string $house_address;
    private string $additional_info;
    private int $favorite;
    private int $user_id;

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->first_name;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getContactGroup()
    {
        return $this->contact_group;
    }

    public function getHouseAddress()
    {
        return $this->house_address;
    }

    public function getAdditionalInfo()
    {
        return $this->additional_info;
    }

    public function getContactData()
    {
        return [
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "mobile" => $this->mobile,
            "email" => $this->email,
            "contact_group" => $this->contact_group,
            "house_address" => $this->house_address,
            "additional_info" => $this->additional_info,
            "favorite" => $this->favorite
        ];
    }

    // change to method incase of when the client is receiving and not sending data
    public function setContactData(array $postData)
    {
        $this->first_name = htmlentities(stripslashes($postData['first_name']));
        $this->last_name = htmlentities(stripslashes($postData['last_name']));
        $this->contact_group = (int) htmlentities(stripslashes($postData['contact_group']));
        $this->house_address = htmlentities(stripslashes($postData['house_address']));
        $this->additional_info = htmlentities(stripslashes($postData['additional_info']));

        foreach ($postData['mobile'] as $mobile) {
            $this->mobile[] = htmlentities(stripslashes($mobile));
        }

        foreach ($postData['email'] as $email) {
            $this->email[] = htmlentities(stripslashes($email));
        }

        $this->favorite = isset($postData['favorite']) ? 1 : 0;
    }

    public function getData()
    {
        //     var_dump($this->first_name, $this->last_name, $this->mobile, $this->email, $this->contact_group, $this->house_address, $this->additional_info, $this->favorite);
    }
}

// $connection = new Connection();
// $contacts = $connection->getData();

// $data = new Data();
// $data->setContactData($_POST);
// echo "<pre>";
// var_dump($data->getContactData());
// echo "</pre>";
// $connection->addContacts($data->getContactData());




// try{
//     $conn = new PDO("sqlsrv:Server=DESKTOP-MIE3511,1434;Database=JavaTest", "sa", "akporhuarho11");
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }
// catch(Exception $e){
//     die(print_r($e->getMessage()));
// }

// $tsql = "SELECT * FROM authors";
// $getResults = $conn->prepare($tsql);

// $getResults->execute();
// $results = $getResults->fetchAll(PDO::FETCH_BOTH);

// foreach($results as $row){
//     echo $row['author_id'] . ' ' . $row['first_name'] . ' ' . $row['last_name'];
//     echo '<br>';
// }
