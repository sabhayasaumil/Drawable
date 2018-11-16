<?php
$type = $_GET['type'];
if ($type !== 'Image') {
    $file = './' . $type . '/' . $_GET['key'] . '.zip';
    if ($type == 'Android') {
        $file_name = "Drawable.co.zip";
    } else if ($type == "iPhone") {
        $file_name = "Drawable.co.zip";
    } else {
        $file_name = "Icon.zip";
    }if (!is_file($file)) {
        echo "No download Available";
        exit;
    }
    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=" . $file_name);
    header("Content-Length: " . filesize($file));
    readfile($file);
} else {
    $key = "./Image/" . $_GET['key'];

    if (!is_dir($key)) {
        echo "No download Available";
        exit;
    }

    $dir = opendir($key);

    $file = readdir($dir);
    while ($file == "." || $file == "..") {
        $file = readdir($dir);
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=Drawable.co.' . pathinfo($file)['extension']);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($key . "/" . $file));
    readfile($key . "/" . $file);

    echo "<script>window.history.back();</script>";
    exit;
}
?>