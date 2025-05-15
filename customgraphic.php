<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}


// Formats and uploads the image, replaces old one aswell
if (isset($_POST["type"])) {
    if (isset($_FILES["graphic"]) && $_FILES["graphic"]["error"] === UPLOAD_ERR_OK) {
        $fileInfo = pathinfo($_FILES['graphic']['name']);
        $fileExtension = strtolower($fileInfo['extension'] ?? '');

        $filename = basename($_POST['type']) . '.jpg';
        $path = __DIR__ . '/img/uploads/graphics/' . $filename;

        if (move_uploaded_file($_FILES['graphic']['tmp_name'], $path)) {
            $imagick = new Imagick($path);
            $imagick->resizeImage(1920, 1080, Imagick::FILTER_LANCZOS, 1);
            $imagick->setImageFormat('jpg');
            $imagick->writeImage($path);
            $imagick->clear();
            $imagick->destroy();
        } 

        header("Refresh:0");
    } else {
        echo '<script>alert("Error uploading graphic.")</script>';
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Custom Graphics</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php include "php/navbar.php" ?>

    <div class="modal fade" id="modify_1" tabindex="-1" aria-labelledby="modify_1Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modify_1Label">Modify Graphic 1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="modify_1">
                        <div class="mb-3">
                            <label for="graphic" class="form-label">New Graphic</label>
                            <input class="form-control" type="file" id="graphic" name="graphic" accept=".png, .jpg"
                                aria-describedby="graphictip" required>
                            <div id="graphictip" class="form-text">
                                Graphic will be stretched to a 16:9 aspect ratio at 1920x1080 resolution.
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" value="Submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modify_2" tabindex="-1" aria-labelledby="modify_2Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modify_2Label">Modify Graphic 2</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="modify_2">
                        <div class="mb-3">
                            <label for="graphic" class="form-label">New Graphic</label>
                            <input class="form-control" type="file" id="graphic" name="graphic" accept=".png, .jpg"
                                aria-describedby="graphictip" required>
                            <div id="graphictip" class="form-text">
                                Graphic will be stretched to a 16:9 aspect ratio at 1920x1080 resolution.
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" value="Submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modify_3" tabindex="-1" aria-labelledby="modify_3Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modify_3Label">Modify Graphic 3</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="modify_3">
                        <div class="mb-3">
                            <label for="graphic" class="form-label">New Graphic</label>
                            <input class="form-control" type="file" id="graphic" name="graphic" accept=".png, .jpg"
                                aria-describedby="graphictip" required>
                            <div id="graphictip" class="form-text">
                                Graphic will be stretched to a 16:9 aspect ratio at 1920x1080 resolution.
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" value="Submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modify_4" tabindex="-1" aria-labelledby="modify_4Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modify_4Label">Modify Graphic 4</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="type" value="modify_4">
                        <div class="mb-3">
                            <label for="graphic" class="form-label">New Graphic</label>
                            <input class="form-control" type="file" id="graphic" name="graphic" accept=".png, .jpg"
                                aria-describedby="graphictip" required>
                            <div id="graphictip" class="form-text">
                                Graphic will be stretched to a 16:9 aspect ratio at 1920x1080 resolution.
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" value="Submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-10">
        <div class="container py-5">

            <h1 class="text-center">Custom Graphics</h1>

            <hr class="bg-light border-2 border-top border-dark">

            <div class="row row-cols-md-2 g-2">
                <div class="col d-flex justify-content-center">
                    <div class="card border text-bg-dark border-primary ">
                    <img class="card-img-top" src="img/uploads/graphics/modify_1.jpg?t=<?= time(); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title">Custom Graphic 1</h5>
                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modify_1">Modify</a>
                        </div>
                    </div>
                </div>
                <div class="col d-flex justify-content-center">
                    <div class="card border text-bg-dark border-primary ">
                    <img class="card-img-top" src="img/uploads/graphics/modify_2.jpg?t=<?= time(); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title">Custom Graphic 2</h5>
                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modify_2">Modify</a>
                        </div>
                    </div>
                </div>
                <div class="col d-flex justify-content-center">
                    <div class="card border text-bg-dark border-primary ">
                    <img class="card-img-top" src="img/uploads/graphics/modify_3.jpg?t=<?= time(); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title">Custom Graphic 3</h5>
                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modify_3">Modify</a>
                        </div>
                    </div>
                </div>
                <div class="col d-flex justify-content-center">
                    <div class="card border text-bg-dark border-primary ">
                    <img class="card-img-top" src="img/uploads/graphics/modify_4.jpg?t=<?= time(); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title">Custom Graphic 4</h5>
                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modify_4">Modify</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/manager.js"></script>
</body>

</html>