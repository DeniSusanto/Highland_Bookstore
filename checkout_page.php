<?php
session_start();
$conn = mysqli_connect('sophia.cs.hku.hk', 'dsusanto', 'qwerty1234', 'dsusanto') or die('Error! ' . mysqli_connect_error($conn));
$logged_in = false;
if (isset($_SESSION['username'])) {
    $logged_in = true;
}

function insert_guest_sections()
{
?>
    <div class="checkout-main-container">
        <section id="new-existing-section">
            <div class="customer-opt">
                <p class="checkout-opt">I'm a new customer</p>
                <p id="new-account-text" class="checkout-opt-text">Please checkout below</p>
                <p></p>
            </div>
            <div id="little-or-container">
                <p id="little-or">or</p>
            </div>
            <div class="customer-opt-2">
                <p class="checkout-opt">I'm already a customer</p>
                <p class="checkout-opt-text"><a href="login_page.php">Sign In</a></p>
            </div>
        </section>
        <section class="checkout-form-section">
            <div>
                <p id="create-account-title">Create Account</p>
                <div class="checkout-col-container">
                    <div class="checkout-col">
                        <label for="newusername">Username</label>
                        <div class="absolute-wrapper">
                            <input class="checkout-input-relative" id="newusername" type="text" name="newusername" required>
                            <span id="username-exist"></span>
                        </div>
                    </div>
                    <div class="checkout-col">
                        <label for="newpassword">Password</label>
                        <input class="checkout-input" id="newpassword" type="password" name="newpassword" required>
                    </div>
                </div>
                <div>
        </section>
    <?php
}
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout Page</title>
        <link rel="stylesheet" href="bs_style.css" type="text/css">
    </head>

    <body>
        <?php
        if ($logged_in == false) {
            insert_guest_sections();
        } else { ?><div class="checkout-main-container checkout-main-container-unlog">
            <?php
        }
            ?>
            <section class="checkout-form-section">
                <form id="checkout-form" method="POST" action="invoice.php">
                    <div class="checkout-col">
                        <label for="full-name">Full Name</label>
                        <input class="checkout-input" id="full-name" type="text" name="full-name" placeholder="Required" required>
                    </div>
                    <div class="checkout-col">
                        <label for="company">Company Name</label>
                        <input class="checkout-input" id="company" type="text" name="company">
                    </div>
                    <div class="checkout-col">
                        <label for="address-1">Address Line 1</label>
                        <input class="checkout-input" id="address-1" type="text" name="address-1" placeholder="Required" required>
                    </div>
                    <div class="checkout-col">
                        <label for="address-2">Address Line 2</label>
                        <input class="checkout-input" id="address-2" type="text" name="address-2">
                    </div>
                    <div class="checkout-col">
                        <label for="city">City</label>
                        <input class="checkout-input" id="city" type="text" name="city" placeholder="Required" required>
                    </div>
                    <div class="checkout-col">
                        <label for="region">Region/State/District</label>
                        <input class="checkout-input" id="region" type="text" name="region">
                    </div>
                    <div class="checkout-col">
                        <label for="country">Country</label>
                        <input class="checkout-input" id="country" type="text" name="country" placeholder="Required" required>
                    </div>
                    <div class="checkout-col">
                        <label for="zip">Postcode/Zip Code</label>
                        <input class="checkout-input" id="zip" type="text" name="zip" placeholder="Required" required>
                    </div>

                </form>
            </section>
            <section class="checkout-form-section">
                <div id="checkout-summary">
                    <p id="change-order-link">Your order: <a href="cart_page.php">Change</a></p>
                    <p><b>Free Standard Shipping</b></p>
                    <div id="checkout-items-list"></div>
                    <p id="checkout-total-price-container"><b>Total Price: HK$ <span id="checkout-total-price"></span></b></p>
                </div>

                <div class="checkout-col">
                    <button class="button" id="checkout-button" type="button">Confirm</button>
                </div>
            </section>
            </div>
            <script>
                function initialize_page() {
                    insert_items_list();
                    insert_total_price();
                }
                document.onload = initialize_page();

                function insert_items_list() {
                    var xmlhttp;
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            var items_list = document.getElementById('checkout-items-list');
                            items_list.innerHTML = xmlhttp.responseText;
                        }
                    }
                    xmlhttp.open("GET", "cart_page.php?item_list=true", true);
                    xmlhttp.send();
                }

                function insert_total_price() {
                    var xmlhttp;
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            var total_price = document.getElementById('checkout-total-price');
                            total_price.innerHTML = xmlhttp.responseText;
                        }
                    }
                    xmlhttp.open("GET", "cart_page.php?total_price=true", true);
                    xmlhttp.send();
                }
                <?php
                if ($logged_in == false) {
                ?>

                    function create_new_account(e) {
                        var xmlhttp;
                        if (window.XMLHttpRequest) {
                            xmlhttp = new XMLHttpRequest();
                        } else {
                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp.onreadystatechange = function() {
                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {}
                        }
                        xmlhttp.open("GET", "create.php?newusername=" + document.getElementById('newusername').value + "&newpassword=" + document.getElementById('newpassword').value, false);
                        xmlhttp.send();
                    }

                    function checkEmpty(e) {
                        let username = document.getElementById("newusername");
                        let password = document.getElementById("newpassword");

                        let fullname = document.getElementById("full-name");
                        let address = document.getElementById("address-1");
                        let city = document.getElementById("city");
                        let country = document.getElementById("country");
                        let zip = document.getElementById("zip");
                        if (username.validity.valueMissing || password.validity.valueMissing || fullname.validity.valueMissing || address.validity.valueMissing ||
                            city.validity.valueMissing || country.validity.valueMissing || zip.validity.valueMissing) {
                            alert("Please do not leave the fields empty");
                            return;
                        } else {
                            create_new_account();
                            document.getElementById("checkout-form").submit();
                        }
                    }
                    document.getElementById("checkout-button").addEventListener("click", checkEmpty);

                    function checkExist() {
                        var input_username = document.getElementById('newusername').value;
                        if (input_username == "") {
                            document.getElementById('username-exist').innerHTML = ""
                        } else {
                            var xmlhttp;
                            if (window.XMLHttpRequest) {
                                xmlhttp = new XMLHttpRequest();
                            } else {
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange = function() {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    var $isexist = document.getElementById('username-exist');
                                    if (xmlhttp.responseText == "true") {
                                        document.getElementById('username-exist').innerHTML = "Username already exist!";
                                        document.getElementById('newusername').value = "";
                                    } else {
                                        document.getElementById('username-exist').innerHTML = "";
                                    }
                                }
                            }
                            xmlhttp.open("GET", "create.php?is_exist=true&newusername=" + document.getElementById('newusername').value, true);
                            xmlhttp.send();
                        }
                    }
                    document.getElementById("newusername").addEventListener("focusout", checkExist);
                <?php
                } else {
                ?>

                    function checkEmpty(e) {
                        let fullname = document.getElementById("full-name");
                        let address = document.getElementById("address-1");
                        let city = document.getElementById("city");
                        let country = document.getElementById("country");
                        let zip = document.getElementById("zip");
                        if (fullname.validity.valueMissing || address.validity.valueMissing ||
                            city.validity.valueMissing || country.validity.valueMissing || zip.validity.valueMissing) {
                            alert("Please do not leave the fields empty");
                            return;
                        } else {
                            document.getElementById("checkout-form").submit();
                        }
                    }
                    document.getElementById("checkout-button").addEventListener("click", checkEmpty);
                <?php
                }
                ?>
            </script>
    </body>

    </html>
    <?php
    session_write_close();
    ?>