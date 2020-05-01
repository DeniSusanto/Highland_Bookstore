<?php
session_start();
$conn=mysqli_connect('sophia.cs.hku.hk', 'dsusanto', 'qwerty1234', 'dsusanto') or die ('Error! '.mysqli_connect_error($conn));

$username = $_POST['username'];
$password = $_POST['password'];

$query = "select * from login where UserId='$username' and PW='$password'";
$result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));

if (mysqli_num_rows($result) == 1){
    $_SESSION['username'] = $username;
    if (isset($_SESSION['cart'])){
        $cart_arr = $_SESSION['cart'];
        if ($cart_arr != NULL){
            foreach ($cart_arr as $bookId => $quantity){
                $query = "SELECT * FROM cart where UserId='$username' and BookId='$bookId';";
                $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
                if (mysqli_num_rows($result) == 0){
                    $query = "INSERT INTO cart VALUE (NULL, '$bookId', '$username', '$quantity')";
                    $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
                }
                else{
                    $cart = mysqli_fetch_array($result);
                    $cart_id = $cart['CartId'];
                    $prev_quant = $cart['Quantity'];
                    $new_quant = $prev_quant + $quantity;
                    $query = "UPDATE cart SET Quantity='$new_quant' WHERE CartId='$cart_id'";
                    $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
                }
            }
        }
        unset($_SESSION['cart']);
    }
    if(isset($_POST['redirect'])){
        header('Location: '.$_POST['redirect']);
    }
    else{
        header('Location: main_page.php');
    }
}
else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login error</title> 
        <link rel="stylesheet" href="bs_style.css" type="text/css">
    </head>

    <body>
        <h1>Invalid login, please login again</h1>

        <script>
            function redirected_login(){
                window.location.replace("login_page.php");
            }
            document.onload = setTimeout(redirected_login, 3000);
        </script>
    </body>
    </html>

<?php    
}
session_write_close();
?>
