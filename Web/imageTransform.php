<?php
if (!isset($_POST['width'])) {
    echo "no size selected";
    exit;
}

$width = $_POST['width'];
$height = $_POST['height'];
$type = $_POST['type'];

if (isset($_GET['key'])) {
    $key = "Image/" . $_GET['key'];
    $dir = opendir($key);

    $file = readdir($dir);
    while ($file == "." || $file == "..")
        $file = readdir($dir);

    resize_image($key . "/" . $file, $width, $height, $type);
    echo '<br />done! Download will start within some moment';
} else
    echo "get variable key missing";

function resize_image($src_name, $width_n, $height_n, $type) {

    $info = pathinfo($src_name);
    $ext = strtolower($info['extension']);

    list($width, $height) = getimagesize($src_name);

    $tmp = imagecreatetruecolor($width_n, $height_n);
    switch ($ext) {
        case 'jpg': $img = imagecreatefromjpeg($src_name);
            break;
        case 'png': $img = imagecreatefrompng($src_name);


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

            break;
    }
    
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $width_n, $height_n, $width, $height);
    if (file_exists($src_name)) {
        unlink($src_name);
    }
    switch ($type) {
        case 'jpg': imagejpeg($tmp, $info['dirname'] . "/" . $info['filename'] . "." . $type);
            break;
        case 'png': imagepng($tmp, $info['dirname'] . "/" . $info['filename'] . "." . $type);
            break;
    }
}
?>