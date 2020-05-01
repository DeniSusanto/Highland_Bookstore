<?php
$conn=mysqli_connect('sophia.cs.hku.hk', 'dsusanto', 'qwerty1234', 'dsusanto') or die ('Error! '.mysqli_connect_error($conn));

if (isset($_GET['is_exist'])){
    $username = $_GET['newusername'];
}
elseif (isset($_POST['newusername'])){
    $username = $_POST['newusername'];
    $password = $_POST['newpassword'];
}
else if(isset($_GET['newusername'])){
    $username = $_GET['newusername'];
    $password = $_GET['newpassword'];
}


$query = "select * from login where UserId='$username'";
$result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
if (mysqli_num_rows($result) == 0){
    if (isset($_GET['is_exist'])){
        echo "false";
    }
    else{
        $insert_query = "INSERT INTO login VALUE ('$username', '$password')";
        $insert_result = mysqli_query($conn, $insert_query) or die ('Failed to query '.mysqli_error($conn));
        if (isset($_GET['newusername'])){
            echo "true";
        }
        else{
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Account created</title> 
                <link rel="stylesheet" href="bs_style.css" type="text/css">
            </head>
        
            <body>
                <h1>Account Created! Welcome</h1>
        
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
    }
    
}
else {
    if (isset($_GET['is_exist'])){
        echo "true";
    }
    else{
        if (isset($_GET['newusername'])){
            echo "false";
        }
        else{
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Create account error</title> 
                <link rel="stylesheet" href="bs_style.css" type="text/css">
            </head>
        
            <body>
                <h1>Account already existed</h1>
        
                <script>
                    function redirected_login(){
                        window.location.replace("create_account.php");
                    }
                document.onload = setTimeout(redirected_login, 3000);
            </script>
            </body>
            </html>
        
        <?php    
        }
    }
}
?>
