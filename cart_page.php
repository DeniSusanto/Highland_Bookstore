<?php
session_start();
$conn = mysqli_connect('sophia.cs.hku.hk', 'dsusanto', 'qwerty1234', 'dsusanto') or die('Error! ' . mysqli_connect_error($conn));
$logged_in = false;
$cart_session = false;
if (isset($_SESSION['username'])) {
    $logged_in = true;
} else {
    if (isset($_SESSION['cart'])) {
        $cart_session = true;
    }
}

if (isset($_POST['delete'])) {
    $bookId = $_POST['bookId'];
    if ($logged_in == true) {
        $userId = $_SESSION['username'];
        $query = "DELETE FROM cart WHERE BookId='$bookId' and UserId='$userId'";
        $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
    } else {
        $cart_list = $_SESSION['cart'];
        unset($cart_list[$bookId]);
        $_SESSION['cart'] = $cart_list;
    }
}

if (isset($_POST['clear_all'])) {
    if ($logged_in == true) {
        $userId = $_SESSION['username'];
        $query = "DELETE FROM cart WHERE UserId='$userId'";
        $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
    } else {
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
    }
}

if (isset($_POST['bookId']) && isset($_POST["quantity"])) {
    $bookId = $_POST['bookId'];
    $quantity = $_POST['quantity'];
    if ($logged_in == true) {
        $username = $_SESSION['username'];
        $query = "SELECT * FROM cart where UserId='$username' and BookId='$bookId';";
        $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO cart VALUE (NULL, '$bookId', '$username', '$quantity')";
            $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
        } else {
            $cart = mysqli_fetch_array($result);
            $cart_id = $cart['CartId'];
            $prev_quant = $cart['Quantity'];
            $new_quant = $prev_quant + $quantity;
            $query = "UPDATE cart SET Quantity='$new_quant' WHERE CartId='$cart_id'";
            $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
        }
    } else {
        if ($cart_session == true) {
            $cart_arr = $_SESSION['cart'];
            if (isset($cart_arr[$bookId])) {
                $prev_quant = $cart_arr[$bookId];
                $new_quant = $prev_quant + $quantity;
                $cart_arr[$bookId] = $new_quant;
            } else {
                $cart_arr[$bookId] = $quantity;
            }
            $_SESSION['cart'] = $cart_arr;
        } else {
            $cart_arr = array();
            $cart_arr[$bookId] = $quantity;
            $_SESSION['cart'] = $cart_arr;
        }
    }
} else {
    $cart_filled = true;
    $cart_arr = NULL;
    if ($logged_in == true) {
        $username = $_SESSION['username'];
        $query = "SELECT * FROM cart where UserId='$username';";
        $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
        if (mysqli_num_rows($result) == 0) {
            $cart_filled = false;
        } else {
            $cart_arr = array();
            while ($row = mysqli_fetch_array($result)) {
                $cart_arr[$row['BookId']] = $row['Quantity'];
            }
        }
    } else {
        if ($cart_session == true) {
            $cart_arr = $_SESSION['cart'];
            if ($cart_arr == NULL) {
                unset($_SESSION['cart']);
                $cart_filled = false;
            }
        } else {
            $cart_filled = false;
        }
    }

    if (isset($_GET['items_in_cart'])) {
        if ($cart_filled == true) {
            $total_items = 0;
            foreach ($cart_arr as $key => $quant) {
                $total_items += $quant;
            }
            echo $total_items;
        } else {
            echo 0;
        }
    } elseif (isset($_GET['total_price'])) {
        if ($cart_filled == true) {
            $total_price = 0;
            foreach ($cart_arr as $key => $value) {
                $query = "SELECT * FROM book where BookId='$key';";
                $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
                $bookInfo = mysqli_fetch_array($result);
                $each_price = $bookInfo['Price'];
                $total_price += $value * $each_price;
            }
            echo $total_price;
        } else {
            echo 0;
        }
    } elseif (isset($_GET['item_list'])) {
        if ($cart_filled == true) {
            foreach ($cart_arr as $key => $value) {
                $query = "SELECT * FROM book where BookId='$key';";
                $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
                $bookInfo = mysqli_fetch_array($result);
                $each_price = $bookInfo['Price'];
?>
                <div class="checkout-item-list">
                    <div class="checkout-item-name">
                        <?php echo $value; ?> x <?php echo $bookInfo['BookName']; ?>
                    </div>
                    <div class="checkout-item-price">
                        HK$ <?php echo $each_price; ?>
                    </div>
                </div>
            <?php
            }
        } else {
            echo "You have no items in your cart";
        }
    } else {
        function get_header()
        {
            if (isset($_SESSION['username'])) {
            ?>
                <a class="user-action" href="logout.php">Logout</a>
                <div class="cart-container">
                    <button class="button" id="cart-button"></button>
                    <span id="inside-cart-quantity"></span>
                </div>
            <?php
            } else {
            ?>
                <a class="user-action" href="login_page.php?redirect=cart_page.php">Sign In</a>
                <a class="user-action" href="create_account.php">Register</a>
                <div class="cart-container">
                    <button class="button" id="cart-button"></button>
                    <span id="inside-cart-quantity"></span>
                </div>
            <?php
            }
        }

        function get_shopping_items($cart_arr, $bookInfo)
        {
            foreach ($cart_arr as $key => $value) {
            ?>
                <div class="book-item-container" id="book-item-<?php echo $key; ?>">
                    <p class="cart-book-name">Book Name: <?php echo $bookInfo[$key]['BookName']; ?></p>
                    <p class="cart-book-quantity">Quantity: <?php echo $value; ?></p>
                    <button id="cart-delete-button" class="red-button button" type="button" onclick="delete_from_cart(<?php echo $key; ?>);">Delete</button>
                </div>
            <?php
            }
        }

        function get_total_price($cart_arr, $bookInfo)
        {
            $total_price = 0;
            foreach ($cart_arr as $key => $value) {
                $each_price = $bookInfo[$key]['Price'];
                $total_price += $value * $each_price;
            }
            echo $total_price;
        }

        function get_cart_content($conn, $cart_filled, $cart_arr)
        {
            if ($cart_filled == true) {
                $bookInfo = array();
                foreach ($cart_arr as $key => $value) {
                    $query = "SELECT * FROM book where BookId='$key';";
                    $result = mysqli_query($conn, $query) or die('Failed to query ' . mysqli_error($conn));
                    $row = mysqli_fetch_array($result);
                    $bookInfo[$key] = $row;
                }
            ?>
                <div class="shoping-items-container">
                    <?php get_shopping_items($cart_arr, $bookInfo); ?>
                </div>
                <p id="total-price">
                    Total Price: $<span id="price-number"><?php get_total_price($cart_arr, $bookInfo); ?></span>
                </p>
            <?php
            } else {
            ?>
                <p id="empty-cart-message">Your shopping cart is empty</p>
        <?php
            }
        }

        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Shopping Cart</title>
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
            <div class="main-cart-container">
                <h2 id="shopping-cart-title">My Shopping Cart</h2>
                <?php get_cart_content($conn, $cart_filled, $cart_arr); ?>
                <div id="cart-buttons-container">
                    <button id="back-button" type="button" class="red-button button" onclick="location.href='main_page.php';">Back</button>
                    <button id="checkout-button" type="button" class="button" onclick="location.href='checkout_page.php';">Checkout</button>
                </div>
            </div>
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

                function delete_from_cart(id) {
                    var xmlhttp;
                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function() {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            document.open();
                            document.write(xmlhttp.responseText);
                            document.close();
                        }
                    }
                    xmlhttp.open("POST", "cart_page.php", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("delete=true&bookId=" + id);

                }
                document.getElementById("search-button").addEventListener("click", () => document.getElementById("search-form").submit());

                document.getElementById("cart-button").addEventListener("click", () => {
                    window.location.href = "cart_page.php";
                });
            </script>
        </body>

        </html>
<?php
    }
}
session_write_close();
?>