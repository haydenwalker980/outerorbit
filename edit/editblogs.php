<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; $blog = getInfoFromBlog((int)$_GET['id'], $conn); ?></title>
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
            
                $stmt = $conn->prepare("SELECT * FROM blogs WHERE author = ? AND id = ?");
                $stmt->bind_param("si", $_SESSION['siteusername'], $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows === 0) die('you dont own this blog post');
                $stmt->close();

                $stmt = $conn->prepare("UPDATE blogs SET message = ?, visiblity = ?, comment = ? WHERE id = ?");
                $stmt->bind_param("sssi", $_POST['comment'], $_POST['visibility'], $_POST['commentst'], $_GET['id']);
                $stmt->execute();
                $stmt->close();
                
                header("Location: index.php");
                skipcomment:
            }
            ?>
            <br>
            <div class="padding">
                <span id="padding10">
                    <small>SpaceMy / Edit / Blog</small>
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
                            <b>Editing "<?php echo $blog['subject']; ?>"</b><br>
                            <textarea cols="48" placeholder="Blog Body" name="comment"><?php echo $blog['message']; ?></textarea><br>

                            <b>Visibility: </b>
                            <select id="options" name="visibility">
                                <option value="Visible">Visible</option>
                                <option value="Profile Only">Profile Only</option>
                                <option value="Link Only">Link Only</option>
                            </select><br>
                            <b>Comments: </b>
                            <select id="options" name="commentst">
                                <option value="a">Normal</option>
                                <option value="n">Disabled</option>
                            </select><br>

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