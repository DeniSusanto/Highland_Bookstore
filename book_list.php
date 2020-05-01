<?php
define("ROOT_IMAGES_DIR", "upload_image/");

function concat_image_path($image_name){
    return ROOT_IMAGES_DIR.$image_name;
}

function display_book_list($result){
    while($row = mysqli_fetch_array($result)) {
        ?>
        <div class="book-display-list">
            <a href="book_details.php?bookId=<?php echo $row['BookId'];?>" class="book-title"><?php echo $row['BookName'];?></a>
            <div class="book-image">
                <img src="<?php echo concat_image_path($row['BookImage']);?>" alt="<?php echo $row['BookName']." book cover";?>" title ="<?php echo $row['BookName']." book cover";?>" />
            </div>
            <p class="book-info">Author: <?php echo $row['Author'];?></p>
            <p class="book-info">Publisher: <?php echo $row['Publisher'];?></p>
            <p class="book-info">Price: $<?php echo $row['Price'];?></p>
        </div>
        <?php
    }
}

$conn=mysqli_connect('sophia.cs.hku.hk', 'dsusanto', 'qwerty1234', 'dsusanto') or die ('Error! '.mysqli_connect_error($conn));

if (isset($_GET['purpose'])){
    $purpose = $_GET['purpose'];
    if ($purpose == "get_all"){
        $query = "select BookId, BookName, Author, Publisher, Price, BookImage from book;";
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
        display_book_list($result);       
    }
    elseif ($purpose == "sort_all_lowest_first") {
        $query = "select BookId, BookName, Author, Publisher, Price, BookImage from book ORDER BY Price ASC;";
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
        display_book_list($result);   
    }
    elseif ($purpose == "get_category_all"){
        $category = $_GET['category'];
        $query = "select BookId, BookName, Author, Publisher, Price, BookImage from book where Category='$category';";
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
        display_book_list($result);      
    }
    elseif ($purpose == "sort_by_category_lowest_first"){
        $category = $_GET['category'];
        $query = "select BookId, BookName, Author, Publisher, Price, BookImage from book where Category='$category' ORDER BY Price ASC;";
        $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
        display_book_list($result);
    }
    elseif ($purpose == "search"){
        $keywords = $_GET['keywords'];
        if ($keywords != ''){
            $keys = explode(" ", $keywords);
            $query = "select BookId, BookName, Author, Publisher, Price, BookImage from book";
            $first_ind = true;
            for ($i = 0; $i < count($keys); $i++){
                $key = $keys[$i];
                if ($key != ""){
                    if ($first_ind == true){
                        $query = $query." where BookName like binary '%$key%'";
                        $first_ind = false;
                    }
                    else{
                        $query = $query." or BookName like binary '%$key%'";
                    }
                }
            }
            $query = $query.";";
            $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
            display_book_list($result); 
        }
        else {
            $query = "select BookId, BookName, Author, Publisher, Price, BookImage from book;";
            $result = mysqli_query($conn, $query) or die ('Failed to query '.mysqli_error($conn));
            display_book_list($result);       
        }
    }
}
?>