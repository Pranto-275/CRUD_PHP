<?php


require 'connection.php';
global $conn;
$message = "";


if (isset($_GET['editid'])) {
    $edit_id = $_GET['editid'];


    $query_edit = "SELECT * FROM crud WHERE id = '$edit_id' limit 1 ";
    $get_edit_result = mysqli_query($conn, $query_edit);
    $get_edit_data = mysqli_fetch_assoc($get_edit_result);


}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {

        $id = $_POST['get_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $image = $_FILES['image'];

        if (empty($name) || empty($email) || $image['size'] == 0) {
            $message = "Every field should be filled!";
        } else {


            $image_name = $image['name'];
            $temp_name_of_file = $image['tmp_name'];
            $format = explode('.', $image_name);
            $file_name = strtolower($format[0]);
            $file_extension = strtolower($format[1]);
            $allowed_format = ['jpg', 'jpeg', 'gif', 'png'];
            if (in_array($file_extension, $allowed_format)) {
                $location = 'image/' . $file_name . '.' . $file_extension;


                    $file_to_delete = $get_edit_data['image'];
                    unlink($file_to_delete);
                    move_uploaded_file($temp_name_of_file, $location);

                $query = "UPDATE crud SET name = '$name',email = '$email',image ='$location' WHERE  id = '$id'";

                if (mysqli_query($conn, $query)) {
                    $message = "record inserted";
                    header('Location:index.php');
                } else {
                    $message = "Failed to Update";
                }


            }

        }

    }

}


?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Info</title>
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

        <?php
        if ($message != null) {
            echo " <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong> $message </strong> Be Careful 
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
        }

        ?>
        <div class="row">
            <div class="col-12 d-flex justify-content-center text-center">

                <div class="card" style="width: 18rem;">

                    <div class="card-body">
                        <h5 class="card-title">Edit Info of <?php echo $get_edit_data['id']; ?> </h5>


                        <form action="edit.php?editid=<?php echo $get_edit_data['id']; ?>" method="post"
                              enctype="multipart/form-data">
                            <!-- name -->

                            <input type="hidden" name="get_id" value="<?php echo $get_edit_data['id']; ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input name="name" type="text" class="form-control"
                                       value="<?php echo $get_edit_data['name']; ?>">
                            </div>

                            <!-- email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input name="email" type="email" class="form-control"
                                       value="<?php echo $get_edit_data['email']; ?>">
                            </div>

                            <!-- name -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input name="image" type="file" class="form-control"
                                       value="<?php echo $get_edit_data['image']; ?>">
                            </div>


                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                            <a href="index.php" class="btn btn-primary">Home</a>
                        </form>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</body>

</html>

