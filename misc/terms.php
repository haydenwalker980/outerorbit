<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            <div class="padding">
                <h1 id="noMargin">Terms & Conditions</h1>
                This isn't a really a "serious" terms of conditions where you have to read through 50 pages. We provide users with blogs, groups, friends, a way of private messaging, blocking, reporting, and much much more. We use your email if you forgot your password. By accepting the terms of service, you are agreeing on your behalf that you will comply to all the terms below.<br><br>
                If we change the terms of service, we will notify you by email.<br>
                To fully use your site, you need to create an account without violating or harrasing other people.<br>
                Don't post porn, gore, or any illegal stuff here. That's a huge nono. Just don't do anything stupid and you'll be fine.
                <h2>tl;dr - USE COMMON SENSE</h2>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>