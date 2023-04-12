<?php


require 'connection.php';
global $conn;
$message = "";

//insert
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $image = $_FILES['image'];

        if (empty($name) || empty($email) || empty($image)) {
            $message = "Every field should be filled!";
            exit();
        } else {

            $image_name = $image['name'];
            $temp_name_of_file = $image['tmp_name'];
            $format = explode('.', $image_name);
            $file_name = strtolower($format[0]);
            $file_extension = strtolower($format[1]);
            $allowed_format = ['jpg', 'jpeg', 'gif', 'png'];
            if (in_array($file_extension, $allowed_format)) {
                $location = 'image/' . $file_name . '.' . $file_extension;
                if (file_exists($location)) {
                    $message = "Image Fill Already Picked";
                } else {
                    move_uploaded_file($temp_name_of_file, $location);

                    $query = "INSERT INTO crud(name, email, image) VALUES ('$name','$email','$location')";

                    if (mysqli_query($conn, $query)) {
                        $message = "Record Inserted";
                        header('Location:index.php');
                        exit();
                    } else {
                        $message = "Failed Adding Person";
                        exit();
                    }


                }
            }


        }


    } else {
        $message = "please Press submit button!";
    }


}


//select all data

$query = 'SELECT * FROM crud ORDER BY id DESC ';
$results = mysqli_query($conn, $query);


//delete


if (isset($_GET['id'])) {
    $delete_id = $_GET['id'];


    $query1 = "SELECT image FROM crud WHERE id = '$delete_id' limit 1 ";
    $image_delete_result = mysqli_query($conn, $query1);


    if (mysqli_num_rows($image_delete_result) > 0) {
        while ($get_image_name = mysqli_fetch_assoc($image_delete_result)) {
            $file_name_for_delete = $get_image_name['image'];


            $query = "DELETE FROM crud WHERE id = '$delete_id' limit 1 ";
            $result = mysqli_query($conn, $query);

            if ($result == 1) {
                $file_to_delete = $file_name_for_delete;
                unlink($file_to_delete);

                header('Location:index.php');
                $message = "Deleted Successfully";
            }


        }
    }


}

//edit profile


if (isset($_GET['editid'])) {
    $edit_id = $_GET['editid'];
    $query_edit = "SELECT * FROM crud WHERE id = '$edit_id' limit 1 ";
    $get_edit_result = mysqli_query($conn, $query_edit);
    $get_edit_data = mysqli_fetch_assoc($get_edit_result);
}


?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud System</title>
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

    <h1 style="margin-left: 20%" class="itms">CRUD SYSTEM WITH IMAGE STORAGE</h1>
    <div class="itms">

        <?php
        if ($message != null) {
            echo " <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong> $message </strong> Be Careful 
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }
        ?>
        <!-- modal -->
        <!--add Button trigger modal -->
        <div class="mx-3 my-2">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Add User
            </button>
        </div>

        <!-- add Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="index.php" method="post" enctype="multipart/form-data">
                            <!-- name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input name="name" type="text" class="form-control">
                            </div>

                            <!-- email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input name="email" type="email" class="form-control">
                            </div>

                            <!-- name -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input name="image" type="file" class="form-control">
                            </div>


                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>


        <table class="table">
            <thead class="table-dark">
            <tr>
                <th scope="col">id</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Image</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>

            <?php


            if (mysqli_num_rows($results) > 0) {
                while ($row = mysqli_fetch_assoc($results)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td> <img class='rounded mx-auto d-block' src=" . $row['image'] . " height='180' width='180'></td>";
                    echo "<td><span> <a href='edit.php?editid=" . $row['id'] . "'  type='button' class='btn btn-primary'>Edit</a></span>";
                    echo "<span> <a href='view.php?id=" . $row['id'] . "'  type='button' class='btn btn-info'>Show</a></span>";
                    echo "<span> <a href='index.php?id=" . $row['id'] . "' type='button' class='btn btn-danger'>Delete</a></span></td>";
                    echo "</tr>";

                }
            }else{
            echo  "<div align='center'>
                       <h5>Data Not Found!! Or All Data Deleted!!</h5> 
    
</div>";
            }


            ?>


            </tbody>
        </table>


    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>

</html>

