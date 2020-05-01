<?php
session_start();
if (isset($_GET['bookId'])){
    define("ROOT_IMAGES_DIR", "upload_image/");
    $conn=mysqli_connect('sophia.cs.hku.hk', 'dsusanto', 'qwerty1234', 'dsusanto') or die ('Error! '.mysqli_connect_error($conn));
    $bookId = $_GET['bookId'];
    $query = "SELECT * FROM book where BookId='$bookId';";
    $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
    $bookDetails = mysqli_fetch_array($result);
    function get_header(){
        if (isset($_SESSION['username'])){
            ?>
            <a class="user-action" href="logout.php">Logout</a>
            <div class="cart-container">
                <button class="button" id="cart-button"></button>
                <span id="inside-cart-quantity"></span>
            </div>
            <?php
        }
        else{
            ?>
            <a class="user-action" href="login_page.php">Sign In</a>
            <a class="user-action" href="create_account.php">Register</a>
            <div class="cart-container">
                <button class="button" id="cart-button"></button>
                <span id="inside-cart-quantity"></span>
            </div>
            <?php
        }
    }

    function concat_image_path($image_name){
        return ROOT_IMAGES_DIR.$image_name;
    }
    ?>
    
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $bookDetails['BookName'];?></title> 
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
                    <?php get_header(); ?>
                </div>
            </div>
            <div class="main-container">
                <div class="solo-panel-display">
                    <a href="main_page.php">Home</a><span> > </span> <a href="#"><?php echo $bookDetails['BookName'];?></a>
                    <h2 id="solo-book-heading"><?php echo $bookDetails['BookName'];?></h2>
                    <div id="solo-book-display-container">
                        <div class="solo-book-left-panel">
                            <div class="solo-book-image-container">
                                <img src="<?php echo concat_image_path($bookDetails['BookImage']);?>" alt="<?php echo $bookDetails['BookName']." book cover";?>" title ="<?php echo $bookDetails['BookName']." book cover";?>" />
                            </div>
                        </div>
                        <div class="solo-book-right-panel">
                            <div class="solo-book-details-container">
                                <p id="book-author" class="solo-book-details">Author: <?php echo $bookDetails['Author'];?></p>
                                <p id="book-published" class="solo-book-details">Published: <?php echo $bookDetails['Published'];?></p>
                                <p id="book-publisher" class="solo-book-details">Publisher: <?php echo $bookDetails['Publisher'];?></p>
                                <p id="book-category" class="solo-book-details">Category: <?php echo $bookDetails['Category'];?></p>
                                <p id="book-language" class="solo-book-details">Language: <?php echo $bookDetails['Lang'];?></p>
                                <div class="solo-book-description-container">
                                    Description:
                                    <p id="book-description" class="solo-book-description"><?php echo $bookDetails['Description'];?></p>
                                </div>
                                <p id="book-price" class="solo-book-price">Price: $<?php echo $bookDetails['Price'];?></p>
                            </div>
                            <div class="solo-book-title-add-cart-container">
                                <label for="order-quantity">Order: </label>
                                <input id="order-quantity" type="text" name="order" value=1>
                                <button class="button" id="add-to-cart-button" type="button">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function initialize_page(){
                    get_num_item_in_cart();
                }
                document.onload = initialize_page();

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
                
                function add_to_cart(){
                    var xmlhttp;
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            get_num_item_in_cart();
                        }
                    }
                    xmlhttp.open("POST", "cart_page.php", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("bookId=<?php echo $bookDetails['BookId'];?>&quantity="+document.getElementById("order-quantity").value);
                }
                document.getElementById("add-to-cart-button").addEventListener("click", add_to_cart);
                document.getElementById("search-button").addEventListener("click", () => document.getElementById("search-form").submit());
                document.getElementById("cart-button").addEventListener("click", () => {window.location.href = "cart_page.php";});
            </script>
        </body>
    </html>
<?php 
}
else{
    header('Location: main_page.php');
}
session_write_close();
?>