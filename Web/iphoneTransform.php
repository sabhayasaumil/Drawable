<?php

if (isset($_GET['key'])) {
    $key = "iPhone/" . $_GET['key'];
    if (is_dir($key)) {
        $dir = opendir($key);
        $file = readdir($dir);
        while ($file == "." || $file == "..")
            $file = readdir($dir);

        closedir($dir);

        $zip = new ZipArchive;
        $res = $zip->open($key . "/" . $file);

        if ($res === TRUE) {
            $zip->extractTo($key);
            $zip->close();
            unlink($key . '/' . $file);


            $dir = opendir($key);
            $file = readdir($dir);
            while (is_dir($file))
                $file = readdir($dir);

            closedir($dir);
            $file_name = $file;

            $key_fin = $key . '/screenshot';
            $iphone6plus = $key_fin . "/iPhone6plus";
            $iphone6 = $key_fin . "/iPhone6";
            $iphone5 = $key_fin . "/iPhone5";
            $iphone4 = $key_fin . "/iPhone4";

            mkdir($key_fin);
            mkdir($iphone6plus);
            mkdir($iphone6);
            mkdir($iphone5);
            mkdir($iphone4);


            $zip = new ZipArchive;
            $zip->open('iPhone/' . $_GET['key'] . '.zip', ZipArchive::CREATE);

            $dir_img = opendir($key . '/' . $file_name);

            while (($file = readdir($dir_img)) !== false) {
                if ($file !== "." && $file !== "..") {
                    $src = $key . '/' . $file_name . '/' . $file;
                    $info = pathinfo($src);
                    $ext = strtolower($info['extension']);
                    if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg') {
                        resize_image($src, $iphone6plus . "/" . $file, 1080, 1920, $ext);
                        $zip->addFile($iphone6plus . "/" . $file, "iPhone6plus./" . $file);


                        resize_image($src, $iphone6 . "/" . $file, 750, 1334, $ext);
                        $zip->addFile($iphone6 . "/" . $file, "iPhone6./" . $file);


                        resize_image($src, $iphone5 . "/" . $file, 640, 1136, $ext);
                        $zip->addFile($iphone5 . "/" . $file, "iPhone5./" . $file);


                        resize_image($src, $iphone4 . "/" . $file, 640, 960, $ext);
                        $zip->addFile($iphone4 . "/" . $file, "iPhone4./" . $file);
                    } else {
                        
                    }
                }
            }

            $zip->close();
            deleteDirectory($key);

            $_SESSION[$_GET['key']] = 100;
            session_write_close();
            session_start();
            echo '<br />done! Download will start within some moment';
        } else {
            echo 'There Was A Problem';
        }
    }
} else
    echo "get variable key missing";

function resize_image($src_name, $des_name, $newWidth, $newHeight, $type) {
    list($width, $height) = getimagesize($src_name);
    $tmp = imagecreatetruecolor($newWidth, $newHeight);

    if ($width > $height) {
        $temp = $newHeight;
        $newHeight = $newWidth;
        $newWidth = $temp;
    }

    switch ($type) {
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

    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($des_name)) {
        unlink($des_name);
    }
    switch ($type) {
        case 'jpg': imagejpeg($tmp, $des_name);
            break;
        case 'png': imagepng($tmp, $des_name);
            break;
    }
}

function Delete($path) {
    if (is_dir($path) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if (in_array($file->getBasename(), array('.', '..')) !== true) {
                if ($file->isDir() === true) {
                    rmdir($file->getPathName());
                } else if (($file->isFile() === true) || ($file->isLink() === true)) {
                    unlink($file->getPathname());
                }
            }
        }

        return rmdir($path);
    } else if ((is_file($path) === true) || (is_link($path) === true)) {
        return unlink($path);
    }

    return false;
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