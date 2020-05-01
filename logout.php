<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title> 
    <link rel="stylesheet" href="bs_style.css" type="text/css">
</head>

<body>
    <h2>Logging out</h2>
    <script>
        function redirected_login(){
            window.location.replace("main_page.php");
        }
        document.onload = setTimeout(redirected_login, 3000);
    </script>
</body>

</html>