<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script src="/onLogin.js"></script>
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
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); 

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);

                if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
                if(!$_POST['comment']){ $error = "your blog body cannot be blank"; goto skipcomment; }
                if(strlen($_POST['comment']) > 1024){ $error = "your comment must be shorter than 1024 characters"; goto skipcomment; }
                if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

                $target_dir = "../dynamic/groups/";
                $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
                $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $imageFileType;

                $target_file = $target_dir . $target_name;
                
                $uploadOk = true;
                $movedFile = false;

                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $fileerror = 'unsupported file type. must be jpg, png, jpeg, or gif';
                    $uploadOk = false;
                }

                if (file_exists($target_file)) {
                    $movedFile = true;
                } else {
                    $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                }

                if ($uploadOk) {
                    if ($movedFile) {
                        $stmt = $conn->prepare("INSERT INTO `groups` (name, description, owner, pic) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $subject, $text, $_SESSION['siteusername'], $target_name);
                        $text = htmlspecialchars($_POST['comment']);
                        $subject = htmlspecialchars($_POST['subject']);
                        $stmt->execute();
                        $stmt->close();
                        skipcomment:
                        header("Location: index.php");
                    } else {
                        $fileerror = 'fatal error';
                    }
                }
            }
            ?>
            <br>
            <div class="padding">
                <span id="padding10">
                    <small>SpaceMy / Groups / New</small>
                </span><br>
                <div class="customtopLeft">  
                    <div class="splashBlue">
                        Remember to make sure that your group is not innapropriate! Have fun.
                    </div><br>
                </div>
                <div class="customtopRight">
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($fileerror)) { echo "<small style='color:red'>" . $fileerror . "</small><br>"; } ?>
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>New Group</b><br>
                            <input type="file" name="fileToUpload" id="fileToUpload"><br>
                            <br><input placeholder="Group Title" type="text" name="subject" required="required" size="63"></b><br>
                            <textarea cols="48" placeholder="Description" name="comment"></textarea><br>
                            <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>