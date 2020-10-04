<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <?php require($_SERVER["DOCUMENT_ROOT"] . "/lib/dark.php")?>
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
        <?php $user = getUserFromId((int)$_GET['id'], $conn); ?>
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); 

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
                if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
                if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

                $stmt = $conn->prepare("INSERT INTO `pms` (sto, sfrom, message, subject) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $user['username'], $_SESSION['siteusername'], $text, $subject);
                $text = htmlspecialchars($_POST['comment']);
                $subject = htmlspecialchars($_POST['subject']);
                $stmt->execute();
                $stmt->close();
                skipcomment:
            }
            ?>
            <br>
            <div class="padding">
                <span id="padding10">
                    <small><a href="/">SpaceMy</a> / <a href="/users.php">Profiles</a> / <a href="/profile.php?id=<?php echo $_GET['id']?>"><?php echo $user['username']; ?></a> / <a href="/pm.php?id=<?php echo $_GET['id']?>">Send PM</a></small>
                </span><br>
                <div class="customtopLeft">  
                    <div class="splashBlue">
                        Remember to not send harmful messages and make sure your private message is not against the terms of service!
                    </div><br>
                    <center><img style="width: 10em;" src="dynamic/pfp/<?php echo $user['pfp']; ?>"></center><br>
                </div>
                <div class="customtopRight">
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>Private Message</b><br>
                            <br><input placeholder="Subject" type="text" name="subject" required="required" size="63"></b><br>
                            <textarea cols="48" placeholder="Body" name="comment"></textarea><br>
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