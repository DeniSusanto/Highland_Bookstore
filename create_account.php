<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create account</title> 
        <link rel="stylesheet" href="bs_style.css" type="text/css">
    </head>
    <body>
        <div class=header-container>
            <div class="search-bar-container">
                <div class="search-bar-container">
                    <form id="search-form" action="main_page.php" method="post">
                        <input id="search-bar" type="text" name="search" placeholder="Keyword(s)">
                        <button class="button" id="search-button" type="button">Search</button>
                    </form>
                </div>
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
            <form id="create-form" method="POST" action="create.php">
                <div class="front-col">
                    <label class="front-input form-title" for="username">Create Account</label>
                </div>
                <div class="front-col">
                    <input class="front-input" id="newusername" type="text" name="newusername" placeholder="Desired Username" required>
                </div>
                <div class="front-col">
                    <input class="front-input" id="newpassword" type="password" name="newpassword" placeholder="Desired Password" required>
                </div>
                <div class="front-page-button-col">
                    <input class="button" id="confirmButton" type="submit" value="CONFIRM">
                    <input class="button red-button" id="backAccount" type="button" onclick="location.href='login_page.php';" value="BACK" >
                </div>
            </form>
        </div>
    </body>
    <script>
        function get_num_item_in_cart(){
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var cart_num = document.getElementById('inside-cart-quantity');
                    cart_num.innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "cart_page.php?items_in_cart=true",true);
            xmlhttp.send();
        }
        function initialize_page(){
            get_num_item_in_cart();
        }
        document.onload = initialize_page();
        
        function checkEmpty(e){
            let username = document.getElementById("newusername");
            let password = document.getElementById("newpassword");
            if (username.validity.valueMissing || password.validity.valueMissing){
                alert("Please do not leave the fields empty");
                return;
            }
        }
        document.getElementById("loginButton").addEventListener("click", checkEmpty);
        document.getElementById("search-button").addEventListener("click", () => document.getElementById("search-form").submit());
        document.getElementById("cart-button").addEventListener("click", () => {window.location.href = "cart_page.php";});
        </script>
</html>