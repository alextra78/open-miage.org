<html>
    <head>
        <title>Download</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../bootstrap.min.css" rel="stylesheet" media="screen">
    </head>    
    <body>   
        <?php
        $dh = @opendir(".");
        while (false !== ($obj = readdir($dh) )) {
            if ($obj == '.' || $obj == '..' || $obj == 'cgi-bin')
                continue;
            else if (is_dir($obj)) {
                $dh1 = @opendir($obj);
                while (false !== ($obj1 = readdir($dh1) )) {
                    if ($obj1 == '.' || $obj1 == '..' || $obj1 == 'cgi-bin')
                        continue;
                    else if (is_dir($obj . "/" . $obj1)) {
                        ?>
                    <legend>
                        <?= $obj . " / " . $obj1 ?>
                    </legend>
                    <?php
                    $contentByVersion = array();
                    $dh2 = @opendir($obj . "/" . $obj1);
                    while (false !== ($obj2 = readdir($dh2) )) {
                        if ($obj2 == '.' || $obj2 == '..' || $obj2 == 'cgi-bin')
                            continue;
                        else if (is_dir($obj . "/" . $obj1 . "/" . $obj2)) {
                            $content = "$obj / $obj1 / $obj2";
                            if (is_file("$obj.$obj1.$obj2"))
                                $content .= " <i>[download: " . file_get_contents("$obj.$obj1.$obj2") . "]</i>";
                            $content .= "<ul>";
                            $dh3 = @opendir($obj . "/" . $obj1 . "/" . $obj2);
                            $lineInContentByVersion = array();
                            while (false !== ($obj3 = readdir($dh3) )) {
                                if ($obj3 == '.' || $obj3 == '..' || $obj3 == 'cgi-bin' || !preg_match("/\.zip$/", $obj3))
                                    continue;
                                else if (is_file($obj . "/" . $obj1 . "/" . $obj2 . "/" . $obj3)) {
                                    $size = filesize($obj . "/" . $obj1 . "/" . $obj2 . "/" . $obj3);
                                    $size = ($size > 1000 * 1000) ? (ceil($size / (1000 * 10)) / 100) . " Mo" : (($size > 1000) ? (ceil($size / (10)) / 100) . " ko" : $size . " o");
                                    $lineInContentByVersion[$obj3] = '<li><a href="dl.php?dl=' . $obj . "/" . $obj1 . "/" . $obj2 . "/" . $obj3 . '"' . ">$obj3</a> <i>[size: $size]</i></li>";
                                }
                            }
                            $keys = array_keys($lineInContentByVersion);
                            sort($keys);
                            foreach ($keys as $key)
                                $content .= $lineInContentByVersion[$key];
                            $content .= "</ul>";
                            $v = explode("_", $obj2);
                            $a = explode(".", $v[0]);
                            $count = 0;
                            for ($i = 0; $i < sizeof($a); $i++)
                                $count += bcpow(1000, (sizeof($a) - $i)) * intval($a[$i]);
                            $contentByVersion[$count] = $content;
                            closedir($dh3);
                        }
                    }
                    $keys = array_keys($contentByVersion);
                    rsort($keys);
                    foreach ($keys as $value) {
                        echo $contentByVersion[$value];
                    }
                    closedir($dh2);
                }
            }
            closedir($dh1);
        }
    }
    closedir($dh);
    ?>
</body>
</html>