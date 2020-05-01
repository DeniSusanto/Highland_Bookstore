<?php
session_start();

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

function get_catalogue_list(){
        $conn=mysqli_connect('sophia.cs.hku.hk', 'dsusanto', 'qwerty1234', 'dsusanto') or die ('Error! '.mysqli_connect_error($conn));
        $query = "SELECT DISTINCT Category FROM book;";
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
        while($row = mysqli_fetch_array($result)) {
            $category = $row['Category'];
            ?>
                <div class="category-list">
                    <a href="category_page.php?category=<?php echo $category; ?>"><?php echo $category; ?></a>
                </div>
            <?php
        }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title> 
        <link rel="stylesheet" href="bs_style.css" type="text/css">
    </head>
    <body>
        <div class=header-container>
            <div class="search-bar-container">
                <input id="search-bar" type="text" name="search" placeholder="Keyword(s)">
                <button class="button" id="search-button">Search</button>
            </div>
            <div class="user-action-container">
                <?php get_header(); ?>
            </div>
        </div>
        <div class="main-container">
            <div class="left-panel-category">
                <h3 id="category-heading">Category</h3>
                <div id="category-container">
                    <?php get_catalogue_list(); ?>
                </div>
            </div>
            <div class="right-panel-display">
                <a href="main_page.php">Home</a>
                <h2 id="books-display-heading">All Books</h2>
                <div id="sort-by-price-container">
                    <button class="button" id="sort-by-price-button">Sort by Price (Lowest)</button>
                </div>
                <div id="books-display-container">
                    
                </div>
            </div>
        </div>
        <script>
            function initialize_page(){
                <?php
                if (isset($_POST["search"])){
                    $keywords = $_POST["search"];
                    ?>
                    document.getElementById("search-bar").value = "<?php echo $keywords;?>";
                    get_book_lists("search");
                    <?php
                }
                else{
                    ?>
                    get_book_lists("all");
                    <?php
                }
                ?>
                get_num_item_in_cart();
            }
            document.onload = initialize_page();

            function get_book_lists(type){
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                if (type=="all"){
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            var book_display = document.getElementById('books-display-container');
                            book_display.innerHTML = xmlhttp.responseText;
                        }
                    }
                    xmlhttp.open("GET", "book_list.php?purpose=get_all",true);
                    xmlhttp.send();
                }
                else if (type=="sort_all_lowest_first"){
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            var book_display = document.getElementById('books-display-container');
                            book_display.innerHTML = xmlhttp.responseText;
                            document.getElementById("books-display-heading").innerHTML = "All Books (Sort by Price Lowest)";
                        }
                    }
                    xmlhttp.open("GET", "book_list.php?purpose=sort_all_lowest_first", true);
                    xmlhttp.send();
                }
                else if (type=="search"){
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            var book_display = document.getElementById('books-display-container');
                            book_display.innerHTML = xmlhttp.responseText;
                            document.getElementById("books-display-heading").innerHTML = "Searching Results";
                        }
                    }
                    xmlhttp.open("GET", "book_list.php?purpose=search&keywords="+document.getElementById("search-bar").value, true);
                    xmlhttp.send();
                }
            }
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
            document.getElementById("sort-by-price-button").addEventListener("click", () => {get_book_lists("sort_all_lowest_first")});
            document.getElementById("search-button").addEventListener("click", () => {get_book_lists("search")});
            document.getElementById("cart-button").addEventListener("click", () => {window.location.href = "cart_page.php";});
        </script>
    </body>
</html>