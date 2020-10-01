<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/manage.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <?php 
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            $user = getUserFromName($_SESSION['siteusername'], $conn); 
            $total = getAllFileSize($_SESSION['siteusername'], $conn);
            if(!isset($total)) { $total = 0; }
            $mb10 = 10000000;

            function FileSizeConvert($bytes){
                $bytes = floatval($bytes);
                    $arBytes = array(
                        0 => array(
                            "UNIT" => "TB",
                            "VALUE" => pow(1024, 4)
                        ),
                        1 => array(
                            "UNIT" => "GB",
                            "VALUE" => pow(1024, 3)
                        ),
                        2 => array(
                            "UNIT" => "MB",
                            "VALUE" => pow(1024, 2)
                        ),
                        3 => array(
                            "UNIT" => "KB",
                            "VALUE" => 1024
                        ),
                        4 => array(
                            "UNIT" => "B",
                            "VALUE" => 1
                        ),
                    );

                foreach($arBytes as $arItem)
                {
                    if($bytes >= $arItem["VALUE"])
                    {
                        $result = $bytes / $arItem["VALUE"];
                        $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                        break;
                    }
                }
                return $result;
            }

            if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['fileup']) {
                $uploadOk = true;
                $movedFile = false;

                $target_dir = "../dynamic/files/";
                $songFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
                $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $songFileType;

                $target_file = $target_dir . $target_name;
                
                if($total > 10000000) {
                    die("You have used up all of your space");
                }

                if($songFileType != "tff" && $songFileType != "woff" && $songFileType != "png" && $songFileType != "gif" && $songFileType != "jpg" && $songFileType != "jpeg" && $songFileType != "otf" && $songFileType != "eot" && $songFileType != "svg") {
                    $fileerror = 'unsupported file type. must be tiff, woff, png, gif, jpg, jpeg, otf, eot, or svg<hr>';
                    $uploadOk = false;
                }

                if (file_exists($target_file)) {
                    $movedFile = true;
                } else {
                    $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                }

                if ($uploadOk) {
                    if ($movedFile) {
                        $stmt = $conn->prepare("INSERT INTO files (filename, owner) VALUES (?, ?)");
                        $stmt->bind_param("ss", $target_name, $_SESSION['siteusername']);
                        $stmt->execute();
                        $stmt->close();                        
                        header("Location: index.php");
                    } else {
                        $fileerror = 'fatal error' . $_FILES["fileToUpload"]["error"] . '<hr>';
                    }
                }
            }
        ?>
        <style>
            .customtopLeft {
                float: left;
                width: calc( 30% - 20px );
                padding: 10px;
            }

            .customtopRight {
                float: right;
                width: calc( 70% - 20px );
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <div class="padding">
                <span style="padding-left: 20px;">
                    <h1 id="noMargin">&nbsp;File Igloo</h1>
                </span>
                <div class="customtopLeft">
                    <div class="splashBlue">
                        You may upload some files so you can use custom fonts for your profile using CSS. Please do not use this for NSFW/gore/illegal purposes.
                    </div><br>
                </div>
                <div class="customtopRight">
                    <div class="splashBlue">
                        <?php if(isset($fileerror)) { echo "<small style='color:red'>" . $fileerror . "</small><br>"; } ?>
                        <form method="post" enctype="multipart/form-data">
                            <b>File</b><br>
                            <input type="file" name="fileToUpload" id="fileToUpload">
                            <input type="submit" value="Upload File" name="fileup">
                        </form><br>
                    </div><br>
                    <b>Uploaded Files</b> <small>[<?php if(!empty($total)) { echo FileSizeConvert($total); } else { echo "0B";} ?>/10MB]</small><br>
                    <ul>
                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM files WHERE owner = ?");
                        $stmt->bind_param("s", $_SESSION['siteusername']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()) {?>
                        <li>
                            <a href="/dynamic/files/<?php echo $row['filename']?>"><?php echo $row['filename']?></a>
                            <small>[<?php echo FileSizeConvert(filesize("../dynamic/files/" . $row['filename']))?>]</small><br>
                        </li>
                        <?php }
                        $stmt->close();
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>
