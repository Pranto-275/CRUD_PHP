<?php
require 'connection.php';
global $conn;



if (isset($_GET['id'])){
    $id = $_GET['id'];
    $query = "SELECT * FROM  crud WHERE id = '$id'";
    $result = mysqli_query($conn,$query);
}





?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Single Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
        .itms {
            margin: 100px auto;
        }
    </style>

</head>

<body>


<div class="container ">
    <h1 style="margin-left: 20%">CRUD SYSTEM WITH IMAGE STORAGE</h1>
    <div class="itms">
    <div class="row">
        <div class="col-12 d-flex justify-content-center text-center">
            <?php
            if(mysqli_num_rows($result)>0){
                while ($row = mysqli_fetch_assoc($result)){
                    echo "<div class='card' style='width: 18rem;'>
            <img src='{$row['image']}' class='card-img-top' alt='...'>
            <div class='card-body'>
                <h5 class='card-title'>{$row['name']}</h5>
                <p class='card-text'>{$row['email']}</p>
                <a href='index.php' class='btn btn-primary'>Go somewhere</a>
            </div>
        </div>";
                }
            }

            ?>

        </div>
    </div>


    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>

</html>


<?php

mysqli_close($conn);

?>

