<?php
session_start();
$keys = [
    'full-name', 'company', 'address-1', 'address-2',
    'city', 'region', 'country', 'zip',
];
$user_information = array();
foreach ($keys as $key => $value) {
    if (isset($_POST[$value])) {
        if ($_POST[$value] != "") {
            $user_information[$value] = $_POST[$value];
        } else {
            $user_information[$value] = "NA";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="bs_style.css" type="text/css">
</head>

<body>
    <div id="invoice-container">
        <h1>Invoice Page</h1>
        <div id="invoice-infromation">
            <p>
                <span class="invoice-meta">Full Name: </span><?php echo $user_information['full-name']; ?><span class="invoice-info"></span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="invoice-meta"></span>Company: <span class="invoice-info"><?php echo $user_information['company']; ?></span>
            </p>

            <p>
                <span class="invoice-meta"></span>Address Line 1: <span class="invoice-info"><?php echo $user_information['address-1']; ?></span>
            </p>

            <p>
                <span class="invoice-meta"></span>Address Line 2: <span class="invoice-info"><?php echo $user_information['address-2']; ?></span>
            </p>

            <p>
                <span class="invoice-meta"></span>City: <span class="invoice-info"><?php echo $user_information['city']; ?></span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="invoice-meta"></span>Region: <span class="invoice-info"><?php echo $user_information['region']; ?></span>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="invoice-meta"></span>Country: <span class="invoice-info"><?php echo $user_information['country']; ?></span>
            </p>

            <p>
                <span class="invoice-meta">Postcode: </span><span class="invoice-info"><?php echo $user_information['zip']; ?></span>
            </p>
        </div>
        <div>
            <div id="invoice-items-list"></div>
            <p id="invoice-total-price-container"><b>Total Price: HK$ <span id="invoice-total-price"></span></b></p>
        </div>
    </div>
    <div id="after-invoice">
        <p><b>Thanks for ordering. Your books will be delivered within 7 working days.</b></p>
        <button class="button" id="ok-button" type="button">OK</button>
    </div>

    <script>
        function initialize_page() {
            insert_items_list();
            insert_total_price();
            purge_cart();
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
                    var items_list = document.getElementById('invoice-items-list');
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
                    var total_price = document.getElementById('invoice-total-price');
                    total_price.innerHTML = xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "cart_page.php?total_price=true", false);
            xmlhttp.send();
        }

        function purge_cart() {
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {}
            }
            xmlhttp.open("POST", "cart_page.php", false);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("clear_all=true");
        }
        document.getElementById("ok-button").addEventListener("click", () => {
            window.location.replace("main_page.php");
        });
    </script>
</body>

</html>
<?php
session_write_close();
?>