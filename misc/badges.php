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
                <h1 id="noMargin">Badges</h1>
                <img src="/static/badges/contestwinner.png"> <big><b>Halloween Contest Winner</b></big><br>
                Means they are the 2020 halloween CSS contest winner.<br>
                <img src="/static/badges/contrib.png"> <big><b>Contributor</b></big><br>
                Means they are a contributor.<br>
                <img src="/static/badges/cool.png"> <big><b>Trusted</b></big><br>
                Means they are trusted/well known in the community. Pretty self explanitory.<br>
                <img src="/static/badges/admin.png"> <big><b>Admin</b></big><br>
                Means they are an admin. Pretty self explanitory.<br>
                <img src="/static/badges/firstday.png"> <big><b>First Day</b></big><br>
                First day user on the site.<br>
                <img src="/static/badges/nitro.png"> <big><b>Nitro Booster</b></big><br>
                Boosted the discord server.<br>
                <img src="/static/badges/owner.png"> <big><b>Site Owner</b></big><br>
                Means they are the site owner. Pretty self explanitory.<br>
                <img src="/static/badges/youtried.png"> <big><b>You Tried</b></big><br>
                Means they submitted a entry in a contest, but did not win.<br>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>