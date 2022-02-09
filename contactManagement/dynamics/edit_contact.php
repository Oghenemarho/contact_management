<?php
// REMINDER: Add the html to the page and do some client and server side validation
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("location: ./login.html");
    exit;
} else {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if ($_SESSION["edit_id"] = (int) stripslashes(htmlentities($_GET["contact_id"]))) {
            // do nothing
        } else {
            // error
            header("location: ./contacts.php");
        }
    } else if ($_SERVER["REQUEST_METHOD"] === "POST") {

        require_once './connectionData.php';
        $connection = new Connection();
        $data = new Data();
        $data->setContactData($_POST);
        $connection->editContact($data->getContactData(), $_SESSION["edit_id"], $_SESSION["id"]);
        header("location: ./contacts.php"); // Instead display a modular box indicating that the contact has been editted successfully 
        // And then use the Javascript Window.location() function to redirect to the contacts page
    }
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
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-image: url(../images/image4.jpg);
            background-size: cover;

        }

        body div.container {
            display: flex;
            justify-content: center;
            padding-top: 2.5vh;
            height: 100vh;
            width: 100%;
            background-image: linear-gradient(to top right, rgba(50, 117, 243, 0.541), rgba(215, 240, 248, 0.746))
        }

        form {
            width: 60%;
            height: 95vh;
            background-color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 4px;
            overflow: auto;
            padding-bottom: 1rem;
        }

        form h2 {
            background-color: cornflowerblue;
            color: white;
            padding: 0.5rem;
            width: 100%;
            margin-bottom: 1rem;
            text-align: center;
            border-radius: 4px 4px 0 0;
        }

        #pad {
            padding-right: 0.8rem;
            padding-left: 0.8rem;
            width: 90%;
            position: relative;
        }

        .field-container input {
            display: block;
        }

        .field-container {
            height: 4.5rem;
        }

        .field-container {
            display: flex;
            opacity: 1;
            transition: 0.4s ease all;
            position: relative;
        }

        form label {
            min-width: 6rem;
            height: 2.5rem;
            line-height: 2.5rem;
            font-size: 0.9rem;
            flex-basis: 15%;

        }

        .input-container {
            height: 100%;
        }

        form button {
            height: 2.5rem;
            outline: none;
            appearance: none;
            border: none;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        form button:hover {
            background-color: royalblue;
            color: white;
        }

        .input {
            height: 2.5rem;
            margin: 0 1rem;
            background-color: rgba(158, 149, 149, 0.253);
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            transition: 0.2s ease all;
        }

        .input input {
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0);
            outline: none;
            appearance: none;
            border: none;
        }

        /* name */

        .name-field {
            display: flex;
            justify-content: space-between;
        }

        .name-field .right .input-container:last-child .input {
            margin-right: 0;
        }

        .name-field div.right {
            display: flex;
            flex-basis: 85%;
        }

        .input-container {
            width: 50%
        }

        /* mobile */
        .mobile-field {
            display: flex;
        }

        .mobile-field div.right {
            display: flex;
            flex-basis: 85%;
        }

        .input-container.mobile {
            width: 100%
        }

        .mobile-field div.right button {
            width: 2.5rem;
            margin: 0;
        }

        /* email */

        .email-field {
            display: flex;
        }

        .email-field div.right {
            display: flex;
            flex-basis: 85%;
        }

        .input-container.email {
            width: 100%
        }

        .email-field div.right button {
            width: 2.5rem;
            margin: 0;
        }

        /* Contact groups and favorites*/
        .flex.group-fav {
            display: flex;
            justify-content: space-between;
        }

        .flex.group-fav .field-container {
            flex: 1;
        }

        .check {
            height: 2.5rem;
            margin: 0 0.2rem;
            position: relative;
        }

        .check input {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        label.favorite {
            min-width: 4rem;
        }

        .field-container.favorite {
            justify-content: center;
        }

        /*Select styling*/
        .select {
            height: 2.5rem;
            width: 15rem;
            background-color: royalblue;
            color: white;
            position: relative;
            border-radius: 4px;
            margin: 0 1rem;
        }

        .select select {
            border-radius: 4px;
            width: 100%;
            height: 100%;
            appearance: none;
            outline: none;
            background-color: royalblue;
            color: white;
            border: none;
            text-indent: 10px;
            font-size: 1rem;
        }

        .select::after {
            content: "\25bc";
            display: block;
            height: 2.4rem;
            width: 2.4rem;
            background-color: royalblue;
            position: absolute;
            top: 0;
            right: 0;
            line-height: 2.4rem;
            text-align: center;
            border-radius: 0 4px 4px 0;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .select.focused::after {
            content: "\25b2";
        }



        .textarea {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .textarea .textarea-con {
            flex: 1;
            height: 6rem;

            margin-left: 1rem;
        }

        .textarea textarea {
            width: 100%;
            height: 4rem;
            padding: 0.2rem;
            resize: none;
            outline: none;
            border-radius: 5px;
            background-color: rgba(158, 149, 149, 0.253);
            border: none;
        }

        .textarea label {
            min-width: 6rem;
            line-height: initial
        }

        .textarea-con span {
            display: block;
            text-align: right;
            padding: 0.1rem 0;
        }

        #submitbtn {
            width: 100%;
            background-color: royalblue;
            color: white;
            font-size: 1rem;
            font-weight: 500;
        }

        #submitbtn:hover {
            background-color: white;
            box-shadow: 0 0 5px royalblue;
            color: rgb(13, 63, 211);
        }

        .fas {
            pointer-events: none;
        }

        @keyframes deleteAnimation {
            from {
                opacity: 1;
                position: relative;

            }

            to {
                transform: rotateZ(10deg);
                padding-top: 3rem;
                opacity: 0;

            }
        }

        .removed {
            animation-name: deleteAnimation;
            animation-duration: 0.3s;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <h2>EDIT CONTACT</h2>
            <div id="pad">
                <div class="field-container name-field">
                    <label for="first_name">Name</label>
                    <div class="right">
                        <div class="input-container">
                            <div class="input">
                                <input type="text" name="first_name" id="first_name" placeholder="First Name">
                            </div>
                        </div>
                        <div class="input-container">
                            <div class="input">
                                <input type="text" name="last_name" id="last_name" placeholder="Last Name">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="mobiles">
                    <div class="field-container mobile-field">
                        <label for="mobile">mobile</label>
                        <div class="right">
                            <div class="input-container mobile">
                                <div class="input">
                                    <input type="text" name="mobile[]" id="mobile">
                                </div>
                            </div>
                            <button id="new-mobile"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>

                <div id="emails">
                    <div class="field-container email-field">
                        <label for="email">email</label>
                        <div class="right">
                            <div class="input-container email">
                                <div class="input">
                                    <input type="email" name="email[]" id="email" placeholder="example@example.com">
                                </div>
                            </div>
                            <button id="new-email"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>

                <div class="flex group-fav">
                    <label for="contact_group">contact group</label>
                    <div class="field-container">

                        <div class="input-container">
                            <div class="select">
                                <select name="contact_group" id="contact_group">
                                    <option value="0">none</option>
                                    <!--None-->
                                    <option value="1">family & friends</option> <!-- Family and Friends -->
                                    <option value="2">business</option>
                                    <option value="3">service lines</option>
                                    <option value="4">acquaintances</option>
                                    <option value="5">blacklisted</option>
                                    <option value="6">others</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field-container favorite">
                        <label for="favorite" class="favorite">Favorite</label>
                        <div class="check">
                            <input type="checkbox" name="favorite" id="favorite" value="1">
                        </div>
                    </div>
                </div>

                <div class="textarea">
                    <label for="house_address">house address</label>
                    <div class="textarea-con">
                        <textarea name="house_address" id="house_address" placeholder="maximum of 200 characters"></textarea>
                        <span>0</span>
                    </div>
                </div>
                <div class="textarea">
                    <label for="additional_info">additional information</label>
                    <div class="textarea-con">
                        <textarea name="additional_info" id="additional_info" placeholder="maximum of 200 characters"></textarea>
                        <span>0</span>
                    </div>
                </div>

                <button type="submit" id="submitbtn">Add Contact</button>

            </div>
        </form>
    </div>
    <script>
        // input fields
        var firstNameField = document.querySelector("#first_name");
        var lastNameField = document.querySelector("#last_name");
        var firstMobileField = document.querySelector("#mobile");
        var firstEmailField = document.querySelector("#email");
        var contactGroupSelect = document.querySelector("#contact_group");
        var contactGroupOptions = document.querySelectorAll("#contact_group option");
        var favorite = document.querySelector("#favorite");
        var houseAddress = document.querySelector("#house_address");
        var additionalInfo = document.querySelector("#additional_info");

        var contactId = <?php echo $_SESSION["edit_id"] ?>;
        var request = new XMLHttpRequest();
        request.open("GET", "fetch_edit_data.php?contact_id=" + contactId);
        request.responseType = "json";
        request.send();

        request.onload = function() {
            var contactDataJson = request.response;
            console.log(contactDataJson);
            populateFields(contactDataJson);
        }

        function populateFields(contactData) {
            firstNameField.value = contactData.first_name;
            lastNameField.value = contactData.last_name;
            populateMobile(contactData.mobile);
            populateEmail(contactData.emails);
            getContactGroup(contactData.contact_group, contactGroupOptions);
            additionalInfo.value = contactData.additional_info;
            houseAddress.value = contactData.address;

            if (contactData.favorite == 1)
                favorite.checked = true;
            else
                favorite.checked = false;
        }

        function getContactGroup(groupId, options) {
            options.forEach(function(option) {
                if (option.value == groupId) {
                    option.selected = true;
                }
            })
        }

        function populateMobile(mobileArray) {
            firstMobileField.value = mobileArray[0];
            for (i = 0; i < mobileArray.length; i++) {
                if (i == 0) {
                    continue;
                } else {
                    var input = document.createElement("input");
                    input.setAttribute("type", "text");
                    input.setAttribute("name", "mobile[]");
                    input.classList.add("mobile");
                    input.value = mobileArray[i];

                    var inputWrap = document.createElement("div");
                    inputWrap.classList.add("input");
                    inputWrap.appendChild(input);

                    var inputContainer = document.createElement("div");
                    inputContainer.classList.add("input-container", "mobile");
                    inputContainer.appendChild(inputWrap);

                    var button = document.createElement("button");
                    button.classList.add("remove-mobile");
                    button.innerHTML = "<i class=\"fas fa-minus\"></i>";

                    var right = document.createElement("div");
                    right.classList.add("right");
                    right.appendChild(inputContainer);
                    right.appendChild(button);

                    var label = document.createElement("label");
                    label.innerHTML = "";

                    var fieldContainer = document.createElement("div");
                    fieldContainer.classList.add("field-container", "mobile-field");
                    fieldContainer.appendChild(label);
                    fieldContainer.appendChild(right);

                    mobiles.appendChild(fieldContainer);
                    input.focus;
                    focusControl();

                    removeMobileBtn = document.querySelectorAll('.remove-mobile');

                    removeMobileBtn.forEach(function(btn) {
                        btn.addEventListener("click", removeMobile);
                    });
                }
            }
        }

        function populateEmail(emailsArray) {
            firstEmailField.value = emailsArray[0];
            for (i = 0; i < emailsArray.length; i++) {
                if (i == 0) {
                    continue;
                } else {
                    var input = document.createElement("input");
                    input.setAttribute("type", "email");
                    input.setAttribute("name", "email[]");
                    input.classList.add("email");
                    input.value = emailsArray[i];

                    var inputWrap = document.createElement("div");
                    inputWrap.classList.add("input");
                    inputWrap.appendChild(input);

                    var inputContainer = document.createElement("div");
                    inputContainer.classList.add("input-container", "email");
                    inputContainer.appendChild(inputWrap);

                    var button = document.createElement("button");
                    button.classList.add("remove-email");
                    button.innerHTML = "<i class=\"fas fa-minus\"></i>";

                    var right = document.createElement("div");
                    right.classList.add("right");
                    right.appendChild(inputContainer);
                    right.appendChild(button);

                    var label = document.createElement("label");
                    label.innerHTML = "";

                    var fieldContainer = document.createElement("div");
                    fieldContainer.classList.add("field-container", "email-field");
                    fieldContainer.appendChild(label);
                    fieldContainer.appendChild(right);

                    emails.appendChild(fieldContainer);
                    input.focus;
                    focusControl();

                    removeEmailBtn = document.querySelectorAll('.remove-email');


                    removeEmailBtn.forEach(function(btn) {
                        btn.addEventListener("click", removeEmail);
                    });
                }
            }
        }
    </script>
    <script>
        function focusControl() {
            var inputes = document.querySelectorAll('form #pad input[type=text], form #pad input[type=email], form #pad textarea');
            inputes.forEach(function(input) {
                input.addEventListener('focusin', showFocus);
            });

            inputes.forEach(function(input) {
                input.addEventListener('focusout', hideFocus);
            });
        }

        focusControl();

        function showFocus(e) {
            if (e.target.parentNode.classList.contains('input')) {
                e.target.parentElement.style.border = '2px solid cornflowerblue';
            } else {

            }
        }

        function hideFocus(e) {
            if (e.target.parentNode.classList.contains('input')) {
                e.target.parentElement.style.border = 'none';
            } else {

            }
        }
    </script>

    <script>
        var innerSelect = document.querySelector("#contact_group");
        var outerSelect = document.querySelector("div.select");
        window.onclick = function(e) {
            if (e.target == innerSelect && outerSelect.classList.item(1) != "focused") {
                outerSelect.classList.add("focused");
            } else if (e.target == innerSelect && outerSelect.classList.item(1) == "focused") {
                outerSelect.classList.remove("focused");
            } else if (e.target != innerSelect && outerSelect.classList.item(1) == "focused") {
                outerSelect.classList.remove("focused");
            } else {

            }
        }
    </script>

    <script>
        // For adding more mobile numbers
        var mobiles = document.querySelector('#mobiles');
        var newMobile = document.querySelector('#new-mobile');
        var removeMobileBtn;

        var emails = document.querySelector('#emails');
        var newEmail = document.querySelector('#new-email');
        var removeEmailBtn;

        newMobile.addEventListener("click", moremobile);
        newEmail.addEventListener("click", moreemail);

        function removeMobile(e) {
            e.preventDefault();
            e.target.parentNode.parentNode.classList.add("removed");
            e.target.parentNode.parentNode.onanimationend = function() {
                this.remove();
            }
        }

        function removeEmail(e) {
            e.preventDefault();
            e.target.parentNode.parentNode.classList.add("removed");
            e.target.parentNode.parentNode.onanimationend = function() {
                this.remove();
            }
        }

        function moremobile(e) {
            e.preventDefault();
            var input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("name", "mobile[]");
            input.classList.add("mobile");

            var inputWrap = document.createElement("div");
            inputWrap.classList.add("input");
            inputWrap.appendChild(input);

            var inputContainer = document.createElement("div");
            inputContainer.classList.add("input-container", "mobile");
            inputContainer.appendChild(inputWrap);

            var button = document.createElement("button");
            button.classList.add("remove-mobile");
            button.innerHTML = "<i class=\"fas fa-minus\"></i>";

            var right = document.createElement("div");
            right.classList.add("right");
            right.appendChild(inputContainer);
            right.appendChild(button);

            var label = document.createElement("label");
            label.innerHTML = "";

            var fieldContainer = document.createElement("div");
            fieldContainer.classList.add("field-container", "mobile-field");
            fieldContainer.appendChild(label);
            fieldContainer.appendChild(right);

            mobiles.appendChild(fieldContainer);
            input.focus;
            focusControl();

            removeMobileBtn = document.querySelectorAll('.remove-mobile');

            removeMobileBtn.forEach(function(btn) {
                btn.addEventListener("click", removeMobile);
            });
        }

        function moreemail(e) {
            e.preventDefault();
            var input = document.createElement("input");
            input.setAttribute("type", "email");
            input.setAttribute("name", "email[]");
            input.focus;

            var inputWrap = document.createElement("div");
            inputWrap.classList.add("input");
            inputWrap.appendChild(input);

            var inputContainer = document.createElement("div");
            inputContainer.classList.add("input-container", "email");
            inputContainer.appendChild(inputWrap);

            var button = document.createElement("button");
            button.classList.add("remove-email");
            button.innerHTML = "<i class=\"fas fa-minus\"></i>";

            var right = document.createElement("div");
            right.classList.add("right");
            right.appendChild(inputContainer);
            right.appendChild(button);

            var label = document.createElement("label");
            label.innerHTML = "";

            var fieldContainer = document.createElement("div");
            fieldContainer.classList.add("field-container", "email-field");
            fieldContainer.appendChild(label);
            fieldContainer.appendChild(right);

            emails.appendChild(fieldContainer);
            input.focus;
            focusControl();

            removeEmailBtn = document.querySelectorAll('.remove-email');


            removeEmailBtn.forEach(function(btn) {
                btn.addEventListener("click", removeEmail);
            });
        }
    </script>
</body>

</html>