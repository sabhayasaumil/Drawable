<?php
if (isset($_GET['key'])) {
    $key = "Android/" . $_GET['key'];
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

            if (strpos($file_name, 'xxxhdpi') !== false)
                $type = 1;
            else if (strpos($file_name, 'xxhdpi') !== false)
                $type = 2;
            else if (strpos($file_name, 'xhdpi') !== false)
                $type = 3;
            else if (strpos($file_name, 'hdpi') !== false)
                $type = 4;
            else if (strpos($file_name, 'mdpi') !== false)
                $type = 5;
            else if (strpos($file_name, 'ldpi') !== false)
                $type = 6;
            else
                $type = 1;

            $key_fin = $key . '/drawable';
            $drawable_xxxhdpi = $key_fin . "/drawable-xxxhdpi";
            $drawable_xxhdpi = $key_fin . "/drawable-xxhdpi";
            $drawable_xhdpi = $key_fin . "/drawable-xhdpi";
            $drawable_hdpi = $key_fin . "/drawable-hdpi";
            $drawable_mdpi = $key_fin . "/drawable-mdpi";
            $drawable_ldpi = $key_fin . "/drawable-ldpi";

            mkdir($key_fin);
            mkdir($drawable_xxxhdpi);
            mkdir($drawable_xxhdpi);
            mkdir($drawable_xhdpi);
            mkdir($drawable_hdpi);
            mkdir($drawable_mdpi);
            mkdir($drawable_ldpi);

            $zip = new ZipArchive;
            $zip->open('Android/' . $_GET['key'] . '.zip', ZipArchive::CREATE);

            $R_XXXHDPI = 1;
            $R_XXHDPI = 3;
            $R_XHDPI = 1;
            $R_HDPI = 3;
            $R_MDPI = 1;
            $R_LDPI = 3;

            $R_XXXHDPI_D = 1;
            $R_XXHDPI_D = 4;
            $R_XHDPI_D = 2;
            $R_HDPI_D = 8;
            $R_MDPI_D = 4;
            $R_LDPI_D = 16;

            if ($type == 1) {
                $fin_N = 1;
                $fin_D = 1;
            } else if ($type == 2) {
                $fin_N = $R_XXHDPI_D;
                $fin_D = $R_XXHDPI;
            } else if ($type == 3) {
                $fin_N = $R_XHDPI_D;
                $fin_D = $R_XHDPI;
            } else if ($type == 4) {
                $fin_N = $R_HDPI_D;
                $fin_D = $R_HDPI;
            } else if ($type == 5) {
                $fin_N = $R_MDPI_D;
                $fin_D = $R_MDPI;
            } else if ($type == 6) {
                $fin_N = $R_LDPI_D;
                $fin_D = $R_LDPI;
            }

            $xxxhdpi = $fin_N / $fin_D;
            $xxhdpi = $fin_N * $R_XXHDPI / ( $fin_D * $R_XXHDPI_D);
            $xhdpi = $fin_N * $R_XHDPI / ( $fin_D * $R_XHDPI_D);
            $hdpi = $fin_N * $R_HDPI / ( $fin_D * $R_HDPI_D);
            $mdpi = $fin_N * $R_MDPI / ( $fin_D * $R_MDPI_D);
            $ldpi = $fin_N * $R_LDPI / ( $fin_D * $R_LDPI_D);

            $dir_img = opendir($key . '/' . $file_name);

            while (($file = readdir($dir_img)) !== false) {
                if ($file !== "." && $file !== "..") {
                    $src = $key . '/' . $file_name . '/' . $file;
                    $info = pathinfo($src);
                    $ext = strtolower($info['extension']);
                    if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg') {
                        resize_image($src, $drawable_xxxhdpi . "/" . $file, $xxxhdpi, $ext);
                        $zip->addFile($drawable_xxxhdpi . "/" . $file, "drawable_xxxhdpi/" . $file);

                        resize_image($src, $drawable_xxhdpi . "/" . $file, $xxhdpi, $ext);
                        $zip->addFile($drawable_xxhdpi . "/" . $file, "drawable_xxhdpi/" . $file);

                        resize_image($src, $drawable_xhdpi . "/" . $file, $xhdpi, $ext);
                        $zip->addFile($drawable_xhdpi . "/" . $file, "drawable_xhdpi/" . $file);

                        resize_image($src, $drawable_hdpi . "/" . $file, $hdpi, $ext);
                        $zip->addFile($drawable_hdpi . "/" . $file, "drawable_hdpi/" . $file);

                        resize_image($src, $drawable_mdpi . "/" . $file, $mdpi, $ext);
                        $zip->addFile($drawable_mdpi . "/" . $file, "drawable_mdpi/" . $file);


                        resize_image($src, $drawable_ldpi . "/" . $file, $ldpi, $ext);
                        $zip->addFile($drawable_ldpi . "/" . $file, "drawable_ldpi/" . $file);
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

function resize_image($src_name, $des_name, $ratio, $type) {
    list($width, $height) = getimagesize($src_name);

    $newWidth = $ratio * $width;
    $newHeight = $ratio * $height;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);


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