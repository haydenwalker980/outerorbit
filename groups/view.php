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
        
        <?php $group = getGroupFromId((int)$_GET['id'], $conn); 
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
            if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
            if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
            if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
            if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

            $stmt = $conn->prepare("INSERT INTO `groupcomments` (toid, comment, author) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $_GET['id'], $text, $_SESSION['siteusername']);
            $text = htmlspecialchars($_POST['comment']);
            $stmt->execute();
            $stmt->close();
            skipcomment:
        }
        ?>
        <meta property="og:title" content="<?php echo $group['name']; ?>" />
        <meta property="og:description" content="<?php echo preg_replace("/\"/", "&quot;", $group['description']); ?>" />
        <meta property="og:image" content="https://spacemy.xyz/dynamic/groups/<?php echo $group['pic']; ?>" />
        <style>
            .customtopLeft {
                float: left;
                width: calc( 40% - 20px );
                padding: 10px;
            }

            .customtopRight {
                float: right;
                width: calc( 60% - 20px );
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            <div class="padding">
                <span id="padding10">
                    <small>SpaceMy / Groups / <?php echo htmlspecialchars($group['name']); ?></small>
                </span><br>
                <div class="customtopLeft">  
                    <div class="splashBlue">
                        Remember to make sure that your reply is not innapropriate! Have fun.
                    </div><br>
                    <center><b><?php echo htmlspecialchars($group['name']); ?></b><br><a href="/profile.php?id=<?php echo getIDFromUser($group['owner'], $conn); ?>"><div><b><?php echo $group['owner']; ?></b></div><img style="height: 4em; width: 4em;" src="/dynamic/pfp/<?php echo getPFPFromUser($group['owner'], $conn); ?>"></a><br><br><img style="height: 4em; width: 4em;" src="/dynamic/groups/<?php echo $group['pic']; ?>"><br><br>
                    <a href="join.php?id=<?php echo $group['id']; ?>"><button>Join</button></a></center>
                </div>
                <div class="customtopRight">
                    <div class="splashBlue">
                        <?php echo htmlspecialchars($group['description']); ?>
                    </div><br>
                    <div class="splashBlue">
                        <b>Members</b><br>
                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM users WHERE currentgroup = ? ORDER BY id DESC");
                            $stmt->bind_param("i", $_GET['id']);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) { 
                        ?>
                            <a href="/profile.php?id=<?php echo getIDFromUser($row['username'], $conn); ?>"><?php echo $row['username']; ?></a><br>
                        <?php } ?>
                    </div><br>
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>Reply</b><br>
                            <textarea cols="39" placeholder="Comment" name="comment"></textarea><br>
                            <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                        </form>
                    </div><br>
                    <table id="userWall">
                        <tbody>
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM groupcomments WHERE toid = ? ORDER BY id DESC");
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