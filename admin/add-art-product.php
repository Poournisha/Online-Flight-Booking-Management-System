<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['agmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $aid = $_SESSION['agmsaid'];
        $title = $_POST['title'];
        $dimension = $_POST['dimension'];
        $orientation = $_POST['orientation'];
        $size = $_POST['size'];
        $artist = $_POST['artist'];
        $arttype = $_POST['arttype'];
        $artmed = $_POST['artmed'];
        $sprice = $_POST['sprice'];
        $description = $_POST['description'];
        $refno = mt_rand(100000000, 999999999);

        // Initialize an array for image paths
        $imagePaths = [];

        // Process uploaded files
        foreach ($_FILES['images']['name'] as $key => $imageName) {
            $extension = substr($imageName, strlen($imageName) - 4, strlen($imageName));
            $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");

            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('Image " . ($key + 1) . " has an invalid format. Only jpg / jpeg / png / gif formats are allowed');</script>";
            } else {
                // Rename the image
                $newImageName = md5($imageName) . time() . $extension;
                move_uploaded_file($_FILES['images']['tmp_name'][$key], "images/" . $newImageName);
                $imagePaths[] = $newImageName; // Store image path
            }
        }

        // Convert the image paths array to JSON for storage in the database
        $imagePathsJson = json_encode($imagePaths);

        // Insert product data into the database
        $query = mysqli_query($con, "INSERT INTO tblartproduct (Title, Dimension, Orientation, Size, Artist, ArtType, ArtMedium, SellingPricing, Description, Images, RefNum) VALUES ('$title', '$dimension', '$orientation', '$size', '$artist', '$arttype', '$artmed', '$sprice', '$description', '$imagePathsJson', '$refno')");

        if ($query) {
            echo "<script>alert('Art product details have been submitted.');</script>";
            echo "<script>window.location.href ='add-art-product.php'</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Art Product | Art Gallery Management System</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
</head>

<body>
    <section id="container" class="">
        <!--header start-->
        <?php include_once('includes/header.php'); ?>
        <!--header end-->

        <!--sidebar start-->
        <?php include_once('includes/sidebar.php'); ?>
        <!--sidebar end-->

        <!--main content start-->
        <section id="main-content" style="color:#000">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-file-text-o"></i> Add Art Product Detail</h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
                            <li><i class="icon_document_alt"></i>Art Product</li>
                            <li><i class="fa fa-file-text-o"></i>Art Product Detail</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <form class="form-horizontal " method="post" action="" enctype="multipart/form-data">
                        <div class="col-lg-6">
                            <section class="panel">
                                <header class="panel-heading">
                                    Add Art Product Detail
                                </header>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Title</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" id="title" name="title" type="text" required="true" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Art Product Images</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" name="images[]" multiple required="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Dimension</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" id="dimension" name="dimension" type="text" required="true">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="col-lg-6">
                            <section class="panel">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Orientation</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="orientation" name="orientation" required="true">
                                                <option value="">Choose orientation</option>
                                                <option value="Portrait">Portrait</option>
                                                <option value="Landscape">Landscape</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Size</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="size" name="size" required="true">
                                                <option value="">Choose Size</option>
                                                <option value="Small">Small</option>
                                                <option value="Medium">Medium</option>
                                                <option value="Large">Large</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Artist</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="artist" id="artist" required="true">
                                                <option value="">Choose Artist</option>
                                                <?php
                                                $query = mysqli_query($con, "SELECT * FROM tblartist");
                                                while ($row = mysqli_fetch_array($query)) {
                                                ?>
                                                    <option value="<?php echo $row['ID']; ?>"><?php echo $row['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Art Type</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="arttype" id="arttype" required="true">
                                                <option value="">Choose Art Type</option>
                                                <?php
                                                $query = mysqli_query($con, "SELECT * FROM tblarttype");
                                                while ($row = mysqli_fetch_array($query)) {
                                                ?>
                                                    <option value="<?php echo $row['ID']; ?>"><?php echo $row['ArtType']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Selling Price</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" id="sprice" type="text" name="sprice" required="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="description" name="description" rows="5" required="true"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <p style="text-align: center;">
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </p>
                    </form>
                </div>
            </section>
        </section>
        <?php include_once('includes/footer.php'); ?>
    </section>
</body>

</html>
