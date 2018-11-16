<?php

if (!isset($_POST['sizes'])) {
    echo "no size selected";
    exit;
}

$sizes = $_POST['sizes'];
$size = explode("#", $sizes);
array_shift($size);

if (isset($_GET['key'])) {
    $key = "Icon/" . $_GET['key'];
    $src = $key . "/Icon.png";
    if (!is_file($src)) {
        echo "error";
        exit;
    }

    if (strpos($_POST['sizes'], 'Android') !== false)
        mkdir($key . "/Android");
    if (strpos($_POST['sizes'], 'iOS') !== false)
        mkdir($key . "/iOS");
    if (strpos($_POST['sizes'], 'iWatch') !== false)
        mkdir($key . "/iWatch");

    $zip = new ZipArchive;
    $zip->open('Icon/' . $_GET['key'] . '.zip', ZipArchive::CREATE);

    $progress = 2;
    $_SESSION[$_GET['key']] = 2;
    session_write_close();
    session_start();

    foreach ($size as $data) {
        $info = explode("-", $data);
        $name = "Icon " . $info[1] . "x" . $info[1] . ".png";
        resize_image($src, $key . "/" . $info[0] . "/" . $name, $info[1]);
        $zip->addFile($key . "/" . $info[0] . "/" . $name, $info[0] . "/" . $name);
    }
    $zip->close();
    deleteDirectory($key);
    echo '<br />done! Download will start within some moment';
} else
    echo "get variable key missing";

function resize_image($src_name, $des_name, $size) {
    list($width, $height) = getimagesize($src_name);

    $img = imagecreatefrompng($src_name);
    $tmp = imagecreatetruecolor($size, $size);
    $background = imagecolorallocate($tmp, 0, 0, 0);
    // removing the black from the placeholder
    imagecolortransparent($tmp, $background);

    // turning off alpha blending (to ensure alpha channel information 
    // is preserved, rather than removed (blending with the rest of the 
    // image in the form of black))
    imagealphablending($tmp, false);

    // turning on alpha channel information saving (to ensure the full range 
    // of transparency is preserved)
    imagesavealpha($tmp, true);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $size, $size, $width, $height);

    if (file_exists($des_name)) {
        unlink($des_name);
    }

    imagepng($tmp, $des_name);
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}
?>