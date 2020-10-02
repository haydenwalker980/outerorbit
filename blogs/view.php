<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <?php $blog = getInfoFromBlog((int)$_GET['id'], $conn); ?>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <link rel="stylesheet" href="/lib/getCSS.php?id=<?php echo getIDFromUser($blog['author'], $conn); ?>"> 
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script src="/onLogin.js"></script>
        <meta property="og:title" content="<?php echo $blog['subject'] . " by " . $blog['author']; ?>" />
        <meta property="og:description" content="<?php echo preg_replace("/\"/", "&quot;", $blog['message']); ?>" />
        <meta http-equiv="Content-Security-Policy" content="default-src 'self' *.google.com *.gstatic.com; img-src 'self' images.weserv.nl; style-src 'self' 'unsafe-inline';">
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
                if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
                if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

                $stmt = $conn->prepare("INSERT INTO `blogcomments` (toid, comment, author) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $_GET['id'], $text, $_SESSION['siteusername']);
                $text = htmlspecialchars($_POST['comment']);
                $stmt->execute();
                $stmt->close();
                skipcomment:
            }
            ?>
            <br>
            <div class="padding">
                <span id="padding10">
                    <small><a href="/">SpaceMy</a> / <a href="/blogs/">Blogs</a> / <a href="/blogs/view.php?id=<?php echo $_GET['id']?>"><?php echo $blog['subject']; ?></a></small>
                </span><br>
                <div class="customtopLeft">  
                    <div class="splashBlue">
                        Remember to make sure that your reply does not break the terms of service!
                    </div><br>
                    <center>Written by <b><a href="/profile.php?id=<?php echo getIDFromUser($blog['author'], $conn); ?>"><?php echo htmlspecialchars($blog['author']); ?></a></b><br><img style="width: 10em;" src="/dynamic/pfp/<?php echo getPFPFromUser($blog['author'], $conn) ?>"></center><br>
                </div>
                <div class="customtopRight">
                    <h1 id="noMargin"><?php echo $blog['subject']; ?></h1>
                    <?php echo parseText($blog['message']); ?><br><br>
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>Comment</b><br>
                            <textarea cols="48" placeholder="Comment" name="comment"></textarea><br>
                            <input type="submit" value="Reply" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                        </form>
                    </div><br>
                    <table id="userWall">
                        <tbody>
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM blogcomments WHERE toid = ? ORDER BY id DESC");
                                $stmt->bind_param("i", $_GET['id']);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while($row = $result->fetch_assoc()) { 
                            ?>
                            <tr>
                                <td class="tableLeft">
                                    <a href="/profile.php?id=<?php echo getIDFromUser($row['author'], $conn); ?>"><div><b><center><?php echo $row['author']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['author'], $conn); ?>"></a>
                                </td>
                                <td class="tableRight">
                                    <div><b class="date"><?php echo $row['date']; ?></b></div><div><p><?php echo parseText($row['comment']); ?></p></div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>