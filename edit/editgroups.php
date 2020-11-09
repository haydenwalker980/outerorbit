<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; $group = getGroupFromId((int)$_GET['id'], $conn); ?></title>
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
                if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
                if(!$_POST['comment']){ $error = "your blog body cannot be blank"; goto skipcomment; }
                if(strlen($_POST['comment']) > 1024){ $error = "your comment must be shorter than 1024 characters"; goto skipcomment; }
                if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }
            
                $stmt = $conn->prepare("SELECT * FROM groups WHERE owner = ? AND id = ?");
                $stmt->bind_param("si", $_SESSION['siteusername'], $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows === 0) die('you dont own this group');
                $stmt->close();

                $stmt = $conn->prepare("UPDATE groups SET description = ?, visiblity = ?, private = ? WHERE id = ?");
                $stmt->bind_param("sssi", $_POST['comment'], $_POST['visibility'], $_POST['inv'], $_GET['id']);
                $stmt->execute();
                $stmt->close();

                //This is terribly awful and i will probably put this in a function soon
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
                        $stmt = $conn->prepare("UPDATE groups SET pic = ? WHERE `name` = ?;");
                        $stmt->bind_param("ss", $target_name, $group['name']);
                        $stmt->execute(); 
                        $stmt->close();
                        //header("Location: index.php");
                    } else {
                        $fileerror = 'fatal error';
                    }
                }
                
                //header("Location: index.php");
                skipcomment:
            }
            ?>
            <br>
            <div class="padding">
                <span id="padding10">
                    <small>SpaceMy / Edit / Group</small>
                </span><br>
                <div class="customtopLeft">  
                    <div class="splashBlue">
                        Remember to make sure that your edit is not inappropriate! Have fun.
                    </div><br>
                </div>
                <div class="customtopRight">
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>Editing "<?php echo $group['name']; ?>"</b><br>
                            <textarea cols="48" placeholder="Blog Body" name="comment"><?php echo $group['description']; ?></textarea><br>

                            <select id="options" name="visibility">
                                <option value="Visible">Visible</option>
                                <option value="Link Only">Link Only</option>
                            </select><br><br>

                            <select id="options" name="inv">
                                <option value="e">Public</option>
                                <option value="p">Private</option>
                            </select><br><br>

                            <b>Update Pic</b><br>
                            <input type="file" name="fileToUpload" id="fileToUpload"><br><br>

                            <input type="submit" value="Update" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>