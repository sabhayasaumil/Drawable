<?php

if (isset($_GET['key']) && isset($_GET['type'])) {
    $name = strtolower(basename($_FILES["file"]["name"]));
    $type = pathinfo($name, PATHINFO_EXTENSION);
    switch ($_GET['type']) {
        case "Android":
            $target_dir = "./Android/" . $_GET['key'];
            if ($type != "zip") {
                exit("error-File should be a zip containing all images directly");
            }
            //else if($name != "drawable-xxxhdpi.zip" && $name != "drawable-xxhdpi.zip" && $name != "drawable-xhdpi.zip" && $name != "drawable-hdpi.zip" && $name != "drawable-mdpi.zip" && $name != "drawable-ldpi.zip")
            //	$name = "drawable-xxxhdpi";
            break;
        case "iPhone":
            $target_dir = "./iPhone/" . $_GET['key'];
            if ($type != "zip") {
                exit("error-upload a zip file containg all the images directly");
            }
            break;
        case "Image":
            $target_dir = "./Image/" . $_GET['key'];
            if ($type != "jpg" && $type != "png" && $type != "jpeg")
                exit("error-Select an Image to upload");
            break;
        case "Icon":
            $target_dir = "./Icon/" . $_GET['key'];
            if ($type != "png")
                exit("error-Uploaded file in not an Icon, Should be png");

            $name = "Icon.png";
            break;
        default:
            exit("error-Invalid Input");
    }

    mkdir($target_dir);

    $target_file = $target_dir . "/" . $name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "success";

        if ($_GET['type'] == "Image") {
            list($width, $height) = getimagesize($target_file);
            echo "-" . $width . "x" . $height;
        }
    }
} else
    echo "incorrect path";
?>