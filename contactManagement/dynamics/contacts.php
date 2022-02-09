<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: ./login.html");
    exit;
} else {
    require_once './connectionData.php';
    $connection = new Connection();
    $contacts = $connection->getAllContacts($_SESSION["id"]);
    // echo "<pre>";
    // print_r($connection->getAllContacts($_SESSION["id"]));
    // echo "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/all.min.css">

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
        }

        main {
            flex-basis: 75%;
            display: flex;
            justify-content: flex-start;
            height: 100vh;
            background-color: white;
            overflow: hidden;
        }

        main .left {
            flex-basis: 40%;
            background-color: white;
            padding: 2px;
        }

        main .right {
            flex-basis: 60%;
            transition: 0.3s all ease-in;
        }

        main .right.hide {
            opacity: 0;
        }

        .top-bar {
            background-color: white;
            display: flex;
            align-items: center;
            height: 7vh;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            margin-right: 5px;
            margin-left: 5px;
        }

        .back {
            width: 2.5rem;
        }

        .back .fas {
            line-height: 6vh;
            display: inline-block;
            width: 100%;
            text-align: center;
            font-size: 1.1rem;
            color: rgba(0, 0, 0, 0.7);
            transition: 0.3s;
        }

        .back:hover .fas {
            color: cornflowerblue;
        }

        .profile-pic {
            height: 2.5rem;
            width: 2.5rem;
            background-color: #2EA8D1;
            margin: 2px;
            border-radius: 50%;
            position: relative;
        }

        .profile-pic span {
            position: absolute;
            left: 50%;
            transform: translateX(-53%);
            text-align: center;
            line-height: 2.5rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            letter-spacing: 1px;
            font-size: 1rem;
            color: white;
            cursor: pointer;
        }

        .top-bar input {
            flex: 1;
            height: 100%;
            border: none;
            outline: none;
            font-size: 1.15rem;
            padding: 0.5rem;
        }


        .contacts {
            height: 92vh;
            margin-top: 1vh;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.452);
            position: relative;
        }

        #contact-list {
            height: 100%;
            background-color: white;
            overflow: auto;
            padding-bottom: 2rem;

        }

        .contact-group-label {
            height: 2.5vh;
            background-color: white;
            font-size: 0.7rem;
            letter-spacing: 2px;
            word-spacing: 4px;
            margin: 0;
            padding-top: 3px;
            font-weight: bold;
            padding-left: 5px;
            color: rgba(0, 0, 0, 0.452);
            margin-bottom: 1rem;
        }

        .contact {
            display: flex;
            align-items: center;
            padding: 3px 12px;
            background-color: white;
            height: 4.5rem;
            transition: 0.4s ease-in-out all;
        }

        .contact.selected {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .contact-pic,
        .logo-home-btn {
            height: 3rem;
            width: 3rem;
            margin-right: 1rem;
            border-radius: 50%;
            position: relative;
        }

        .logo-home-btn {
            background-color: #2EA8D1;
        }

        #contact-list .contact:nth-child(n+0) .contact-pic {
            background-image: linear-gradient(to right, #2EA8D1, #2ea8d178);
        }

        #contact-list .contact:nth-child(2n+0) .contact-pic {
            background-image: linear-gradient(to right, #572ED1, #572ed14b);
        }

        #contact-list .contact:nth-child(3n+0) .contact-pic {
            background-image: linear-gradient(to right, #2E57D1, #2e57d160);
        }

        .contact-pic span,
        .logo-home-btn span {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            line-height: 3rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            letter-spacing: 1px;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .contact-pic {
            cursor: default;
            pointer-events: none;
        }

        .contact-name {
            font-size: 1rem;
            color: rgba(0, 0, 0, 0.5);
            letter-spacing: 2px;
            word-spacing: 3.5px;
            cursor: default;
            pointer-events: none;
        }

        .add-contact-btn {
            height: 3.2rem;
            width: 3.2rem;
            background-color: orangered;
            border-radius: 50%;
            position: absolute;
            bottom: 5%;
            right: 10%;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
            transition: 0.3s;
        }

        .add-contact-btn .fas {
            position: absolute;
            left: 50%;
            transform: translateX(-55%);
            text-align: center;
            line-height: 3.2rem;
            color: white;
            font-size: 1.1rem;
        }

        .add-contact-btn:hover {
            transform: rotateZ(45deg);
            background-color: white;
            border: 2px solid orangered;
            color: orangered;
        }

        .add-contact-btn:hover .fas {
            color: orangered;
        }




        #options {
            height: 100vh;
            background-image: linear-gradient(to right, rgba(241, 235, 222, 0.534), white);
            flex-basis: 25%;
        }

        #options .logo-home-btn {
            width: 3.5rem;
            height: 3.5rem;
            margin: 1rem;
        }

        #options .logo-home-btn span {
            font-family: cursive;
            line-height: 3.5rem;
        }

        .options-list {
            margin-top: 2rem;
        }

        .options-list li {
            height: 3rem;
            line-height: 3rem;
            background-color: rgba(0, 0, 0, 0);
            margin-bottom: 0.2rem;
            font-size: .9rem;
            letter-spacing: 2px;
            word-spacing: 4px;
            transition: 0.3s all ease;
        }

        .options-list li button {
            background-color: rgba(0, 0, 0, 0);
            height: 100%;
            width: 100%;
            border: none;
            outline: none;
            text-align: left;
            padding-left: 1rem;
        }

        /*This is for when you click on one of the options filters*/
        .options-list li.selected {
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .contact-head {
            z-index: 2;
            position: sticky;
            height: 25vh;
            background-image: linear-gradient(to right, #2EA8D1, #2EA8D15e);
            padding: 0.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .initials-display {
            height: 5rem;
            width: 5rem;
            border-radius: 50%;
            border: 2px solid white;
            position: relative;
            margin: 0.5rem auto;
        }

        .initials-display span {
            position: absolute;
            top: 50%;
            left: 50%;
            text-align: center;
            transform: translate(-50%, -50%);
            font-family: serif;
            color: white;
            font-size: 1.8rem;

        }

        .contact-name-display {
            font-family: sans-serif;
            letter-spacing: 2px;
            word-spacing: 4px;
            font-size: 1.3rem;
            color: white;
            margin-top: 1rem;
            text-align: center;
        }

        .contact-info {
            overflow: auto;
            height: 65vh;
            /* background-color: green; */
        }

        .contact-info div {
            margin: 0;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            /* min-height: 12vh; */
        }

        .contact-info div ul {
            list-style: none;
        }

        .contact-info div ul li {
            margin: 0.2rem 0;
            letter-spacing: 2px;
        }

        .contact-info div p {
            letter-spacing: 1.5px;
            word-spacing: 3px;
        }

        .contact-info div:nth-child(even) {
            background-color: rgba(128, 128, 128, 0.144);
        }

        .contact-actions {
            height: 10vh;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        .contact-actions button {
            width: 3rem;
            background-color: whitesmoke;
            color: rgba(0, 0, 0, 0.829);
            height: 3rem;
            margin: 2px 1.5rem;
            position: relative;
            border-radius: 50%;
            transition: 0.1s;
            appearance: none;
            outline: none;
            border: none;
            font-size: 1.1rem;
        }

        .contact-actions button:hover {
            color: white;
            background-color: cornflowerblue;
        }

        .contact-actions .favorite-option:hover {
            background-color: whitesmoke;
        }

        .contact-actions button .fas {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: 0.3s ease all;
        }
    </style>
</head>

<body>
    <div id="options">
        <div class="logo-home-btn">
            <span>KYC</span>
        </div>

        <ul class="options-list">
            <li class="selected"><button type="submit">All Contacts <input class="filter-id" type="text" class="contact-id" readonly hidden value="-1"></button></button></li>
            <li><button type="button">Favorites <input class="filter-id" type="text" class="contact-id" readonly hidden value="-2"></button></li>
            <li><button type="button">Family and Friends <input class="filter-id" type="text" class="contact-id" readonly hidden value="1"></button></button></li>
            <li><button type="button">Business <input class="filter-id" type="text" class="contact-id" readonly hidden value="2"></button></button></li>
            <li><button type="button">Service Lines <input class="filter-id" type="text" class="contact-id" readonly hidden value="3"></button></button></li>
            <li><button type="button">Acquaintances <input class="filter-id" type="text" class="contact-id" readonly hidden value="4"></button></button></li>
            <li><button type="button">Blacklist <input class="filter-id" type="text" class="contact-id" readonly hidden value="5"></button></button></li>
            <li><button type="button">Others <input class="filter-id" type="text" class="contact-id" readonly hidden value="6"></button></button></li>
        </ul>
    </div>

    <main>
        <div class="left">
            <div class="top-bar">
                <div class="back" id="back"><i class="fas fa-arrow-left"></i></div>
                <input type="text" name="search-contacts" id="search-contacts">
                <div class="profile-pic">
                    <span>
                        OA
                    </span>
                </div>
            </div>

            <div class="contacts">
                <div class="contact-group-label">ALL CONTACTS</div>
                <ul id="contact-list">

                    <?php foreach ($contacts as $contact) : ?>
                        <li class="contact">
                            <div class="contact-pic">
                                <span><?php echo strtoupper(substr($contact["first_name"], 0, 1)) . strtoupper(substr($contact["last_name"], 0, 1)) ?></span>
                            </div>
                            <div class="contact-name">
                                <?php echo $contact["first_name"] . " " . $contact["last_name"] ?>
                            </div>
                            <input type="text" class="contact-id" readonly hidden value="<?php echo $contact["contact_id"] ?>">
                        </li>
                    <?php endforeach ?>

                </ul>

                <!-- Add Contact button goes here-->
                <div class="add-contact-btn">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
        </div>

        <div class="right hide">
            <div class="contact-head">
                <div class="initials-display">
                    <span>
                        JO
                    </span>
                </div>

                <div class="contact-name-display">
                    John Okafor
                </div>
            </div>

            <div class="contact-info">
                <div class="mobile-contacts">
                    <h4>mobile</h4>
                    <ul>
                        <li>09020144573</li>
                        <li>07068898203</li>
                    </ul>
                </div>

                <div class="email-addresses">
                    <h4>email</h4>
                    <ul>
                        <li>richardakporhuarho@gmail.com</li>
                        <li>tellahmarhii@gmail.com</li>
                    </ul>
                </div>

                <div class="house-address">
                    <h4>house address</h4>
                    <p>
                        76 Erorode Street, Enebiri Layout Off Jakpa Road, Effurun, Delta State.
                    </p>
                </div>

                <div class="additional-info">
                    <h4>additional info</h4>
                    <p>
                        profession: software developer,
                        work-place: 88 Garage road, kdhits Off Ogbomoso Road
                    </p>
                </div>
            </div>

            <div class="contact-actions">
                <!-- Edit, Delete and Share buttons go here-->
                <button class="delete-option">
                    <i class="fas fa-trash"></i>
                </button>

                <button class="edit-option">
                    <i class="fas fa-pen"></i>
                </button>

                <button class="share-option">
                    <i class="fas fa-share-alt"></i>
                </button>

                <button class="favorite-option">
                    <i class="fas fa-star"></i>
                </button>
            </div>
        </div>

    </main>

    <script>
        var searchFilter = -1;
        var contacts = document.querySelectorAll("#contact-list .contact"); // Set this anytime a new set of contacts is received
        const addContactBtn = document.querySelector(".add-contact-btn");
        const filterBtns = document.querySelectorAll("#options ul.options-list li");
        const editOption = document.querySelector(".contact-actions .edit-option");
        var lastClicked;

        editOption.onclick = function() {
            window.location = "edit_contact.php?contact_id=" + lastClicked;
        }

        addContactBtn.onclick = function() {
            window.location = "add_contacts.php";
        }
        refreshContacts();

        function refreshContacts() {
            // When a contact is clicked this fetches the value of the hidden input
            // This method must be called anytime the contact list is updated in order to allow the new contacts fetch their data
            contacts = document.querySelectorAll("#contact-list .contact");
            contacts.forEach(function(contact) {
                contact.addEventListener("click", function(e) {
                    contacts.forEach(function(contact) {
                        contact.classList.remove("selected");
                    });
                    e.target.classList.add("selected");
                    var innerElems = Array.from(contact.children);

                    innerElems.forEach(function(contactChild) {
                        if (contactChild.classList.contains("contact-id")) {
                            lastClicked = contactChild.value;
                            getContactDetails(contactChild.value, displayContactDetails);
                        }
                    });

                });
            });
        }

        filterBtns.forEach(function(filterBtn) {
            filterBtn.addEventListener("click", function(e) {
                var infoPanel = document.querySelector("main .right");
                filterBtns.forEach(function(filterBtn) {
                    filterBtn.classList.remove("selected");
                });
                filterBtn.classList.add("selected");
                var innerElems = Array.from(filterBtn.children[0].children);
                innerElems.forEach(function(filterBtnChild) {
                    if (filterBtnChild.classList.contains("filter-id")) {
                        getContactsByFilter(filterBtnChild.value, repopulateContactsList);
                        searchFilter = filterBtnChild.value;
                        searchField.value = "";
                    }
                });
                infoPanel.classList.add("hide");
            });
        });

        var filterJsonResponseText;

        function getContactsByFilter(filterId, callBack) {
            if (filterId === "") {
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        filterJsonResponseText = this.responseText;
                        console.log(filterJsonResponseText);
                        callBack(filterJsonResponseText);
                    }
                }
            }
            xmlhttp.open("GET", "filter_contacts.php?filter_id=" + filterId, true);
            xmlhttp.send();
        }

        function repopulateContactsList(contactsJson) {
            // Delete the former elements in the contacts list, and add new ones
            var contactsContainer = document.getElementById("contact-list");
            Array.from(contactsContainer.children).forEach(function(child) {
                child.remove();
            });
            contactsJson = JSON.parse(contactsJson);
            contactsJson.forEach(function(contact) {
                console.log(contact);
                // Adding the new Contacts to the Contact list

                // create the contact card
                var contactCard = document.createElement("li");
                contactCard.classList.add("contact");

                // create the span with the contact initials
                var initialsSpan = document.createElement("span");
                initialsSpan.innerText = contact.first_name.substr(0, 1) + contact.last_name.substr(0, 1);

                // create div to hold the span
                var contactPic = document.createElement("div");
                contactPic.classList.add("contact-pic");

                // create div to hold the full name
                var fullname = document.createElement("div");
                fullname.classList.add("contact-name");
                fullname.innerText = contact.first_name + " " + contact.last_name;

                // create hidden input to hold the contact_id
                var hold_contact_id = document.createElement("input");
                hold_contact_id.value = contact.contact_id;
                hold_contact_id.setAttribute("type", "text");
                hold_contact_id.classList.add("contact-id");
                hold_contact_id.readOnly = true;
                hold_contact_id.hidden = true;

                // Append elements
                contactPic.appendChild(initialsSpan);
                contactCard.appendChild(contactPic);
                contactCard.appendChild(fullname);
                contactCard.appendChild(hold_contact_id);

                contactsContainer.appendChild(contactCard);
                refreshContacts();
            });
        }

        // contacts.forEach(function(contact){
        //     contact.onclick = function(){
        //         contact.childNodes.forEach(function(contactChildren){
        //             if(contactChildren.classList.contains("contact-id")){
        //                 console.log("working");
        //             }
        //         });
        //     }
        // });

        var jsonResponseText;

        // When a contact is clicked this fetches the contact details as json text
        function getContactDetails(contact_id, callBack) {
            if (contact_id === "") {
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        jsonResponseText = this.responseText;
                        console.log(jsonResponseText);
                        callBack(JSON.parse(jsonResponseText));
                        // document.write("<pre>" + jsonResponseText + "</pre>");
                    }
                }
            }
            xmlhttp.open("GET", "feed_contacts.php?contact_id=" + contact_id, true);
            xmlhttp.send();
        }

        // When a contact is clicked this displays the contact information on the right panel
        function displayContactDetails(jsonString) {
            var contactNameInitials = document.querySelector(".right .contact-head .initials-display span");
            var contactName = document.querySelector(".right .contact-head .contact-name-display");
            var mobileDisplay = document.querySelector(".right .contact-info .mobile-contacts ul");
            var emailDisplay = document.querySelector(".right .contact-info .email-addresses ul");
            var houseAddressDisplay = document.querySelector(".right .contact-info .house-address p");
            var additionalInfoDisplay = document.querySelector(".right .contact-info .additional-info p");
            var infoPanel = document.querySelector("main .right");


            contactNameInitials.innerText = jsonString.first_name.substr(0, 1) + jsonString.last_name.substr(0, 1);
            contactName.innerText = jsonString.first_name + " " + jsonString.last_name;

            // Remove former values from the mobile contact list
            Array.from(mobileDisplay.children).forEach(function(mobile) {
                mobile.remove();
            });

            // Add the new values to the mobile contact list
            jsonString.mobile.forEach(function(mobile) {
                var holdMobile = document.createElement("li");
                holdMobile.innerText = mobile;
                mobileDisplay.appendChild(holdMobile);
            });

            // Remove former values from the email list
            Array.from(emailDisplay.children).forEach(function(email) {
                email.remove();
            });

            // Add the new values to the mobile contact list
            jsonString.emails.forEach(function(email) {
                var holdEmail = document.createElement("li");
                holdEmail.innerText = email;
                emailDisplay.appendChild(holdEmail);
            });

            // update house Address
            houseAddressDisplay.innerText = jsonString.address;
            if (jsonString.favorite == 1) {
                document.querySelector(".contact-actions .favorite-option").style.backgroundColor = "cornflowerblue";
                document.querySelector(".contact-actions .favorite-option").style.color = "white";
            } else {
                document.querySelector(".contact-actions .favorite-option").style.backgroundColor = "whitesmoke";
                document.querySelector(".contact-actions .favorite-option").style.color = "black";
            }

            additionalInfoDisplay.innerText = jsonString.additional_info;
            infoPanel.classList.remove("hide");
        }

        /*What's left? delete and create account */
        // Validate adding and editing of contacts
        // Change all mobile fieds from text to tel
        // Add confirm box to edit to ask if the user is sure they want to make such changes
        // to implement delete use the lastClicked variable
        // Add progress bar or a gif to show that the page is loading for ajax requests
    </script>

    <script>
        // search contacts
        var searchField = document.querySelector("#search-contacts");
        var searchReturn;
        var searchBackBtn = document.querySelector("#back");

        searchBackBtn.onclick = function() {
            searchField.value = "";
            searchContacts(repopulateContactsList);
        }

        //use repopulateContactList as the callback so as to repopulate the list anytime a value is typed in
        // Remember to clear the search bar after clicking on each filter
        searchField.addEventListener("keyup", function() {
            var infoPanel = document.querySelector("main .right");
            infoPanel.classList.add("hide");
            searchContacts(repopulateContactsList);
        });


        function searchContacts(callback) {
            var str = searchField.value;
            if (str.length === 0) {
                getContactsByFilter(searchFilter, repopulateContactsList);
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        searchReturn = this.responseText;
                        callback(searchReturn);
                    }
                }
            }
            xmlhttp.open("GET", "return_search_values.php?search_value=" + str + "&search_filter=" + searchFilter, true);
            xmlhttp.send();
        }


        // Delete contacts
        var deleteBtn = document.querySelector(".contact-actions .delete-option");
        deleteBtn.addEventListener("click", function(){
            
        });

        function deleteContact(){
            
        }
    </script>

</body>

</html>