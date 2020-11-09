<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
        <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
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
                if(strlen($_POST['comment']) > 10000){ $error = "your comment must be shorter than 10000 characters"; goto skipcomment; }
                if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

                if($_POST['allowcomments'] == "true") {
                    $stmt = $conn->prepare("INSERT INTO `blogs` (author, message, subject) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $_SESSION['siteusername'], $text, $subject);
                    $text = htmlspecialchars($_POST['comment']);
                    $subject = htmlspecialchars($_POST['subject']);
                    $stmt->execute();
                    $stmt->close();
                    logDB($_SESSION['siteusername'] . " has made a blog named " . $text, $conn);
                } else {
                    $stmt = $conn->prepare("INSERT INTO `blogs` (author, message, subject, comment) VALUES (?, ?, ?, 'n')");
                    $stmt->bind_param("sss", $_SESSION['siteusername'], $text, $subject);
                    $text = htmlspecialchars($_POST['comment']);
                    $subject = htmlspecialchars($_POST['subject']);
                    $stmt->execute();
                    $stmt->close();
                    logDB($_SESSION['siteusername'] . " has made a blog named " . $text, $conn);
                }
                skipcomment:
            }
            ?>
            <br>
            <div class="padding">
                <span id="padding10">
                    <small>SpaceMy / Blogs / New</small>
                </span><br>
                <div class="customtopLeft">  
                    <div class="splashBlue">
                        Remember to make sure that your blog post is not innapropriate or harrases a person or a group of people! Have fun.
                    </div><br>
                </div>
                <div class="customtopRight">
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>Blog Post</b> <br>
                            <br><input placeholder="Subject" type="text" name="subject" required="required" size="63"></b><br>
                            <textarea cols="48" id="desc" placeholder="Body" name="comment"><?php if(isset($_GET['text'])) { echo $_GET['text']; } ?></textarea><br><small><a href="https://www.markdownguide.org/basic-syntax">Markdown</a> & Emoticons are allowed.</small><br>
                            <input type="checkbox" id="allow" name="allowcomments" value="true"> <small>Allow Comments</small><br>
                            <script>
                            var simplemde = new SimpleMDE({ element: document.getElementById("desc") });
                            </script>
                            <script src="/js/bloglimit.js"></script>
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