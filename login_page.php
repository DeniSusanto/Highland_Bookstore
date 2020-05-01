<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="bs_style.css" type="text/css">
</head>

<body>
    <div class=header-container>
        <div class="search-bar-container">
            <form id="search-form" action="main_page.php" method="post">
                <input id="search-bar" type="text" name="search" placeholder="Keyword(s)">
                <button class="button" id="search-button" type="button">Search</button>
            </form>
        </div>
        <div class="user-action-container">
            <a class="user-action" href="login_page.php">Sign In</a>
            <a class="user-action" href="create_account.php">Register</a>
            <div class="cart-container">
                <button class="button" id="cart-button"></button>
                <span id="inside-cart-quantity"></span>
            </div>
        </div>
    </div>
    <div class="front-page-main-container">
        <h1 class="name-banner">Highland Bookstore</h1>
        <form id="login-form" method="POST" action="verifyLogin.php">
            <div class="front-col">
                <label class="front-input form-title" for="username">Login</label>
            </div>
            <div class="front-col">
                <input class="front-input" id="username" type="text" name="username" placeholder="Username" required>
            </div>
            <div class="front-col">
                <input class="front-input" id="password" type="password" name="password" placeholder="Password" required>
            </div>
            <div class="front-page-button-col">
                <input class="button" id="loginButton" type="submit" value="SUBMIT">
                <input class="button" id="createAccount" type="button" onclick="location.href='create_account.php';" value="CREATE">
            </div>
            <?php
            if (isset($_GET['redirect'])) {
                $redirect_to = $_GET['redirect'];
            ?>
                <input type="hidden" id="redirect" name="redirect" value="<?php echo $redirect_to; ?>">
            <?php
            }
            ?>
        </form>
    </div>
</body>

<script>
    function get_num_item_in_cart() {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var cart_num = document.getElementById('inside-cart-quantity');
                cart_num.innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "cart_page.php?items_in_cart=true", true);
        xmlhttp.send();
    }

    function initialize_page() {
        get_num_item_in_cart();
    }
    document.onload = initialize_page();

    function checkEmpty(e) {
        let username = document.getElementById("username");
        let password = document.getElementById("password");
        if (username.validity.valueMissing || password.validity.valueMissing) {
            alert("Please do not leave the fields empty");
            return;
        }
    }
    document.getElementById("search-button").addEventListener("click", () => document.getElementById("search-form").submit());
    document.getElementById("loginButton").addEventListener("click", checkEmpty);
    document.getElementById("cart-button").addEventListener("click", () => {
        window.location.href = "cart_page.php";
    });
</script>

</html>