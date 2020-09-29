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
        <?php if(!isset($_SESSION['siteusername'])) { header("Location: login.php"); } ?>
        <?php $user = getUserFromName($_SESSION['siteusername'], $conn); ?>
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <div class="padding">
                <span id="padding10">
                    <small>SpaceMy / Incoming PMs</small>
                </span><br>
                <div class="customtopLeft">  
                    <div class="splashBlue">
                        Remember to not send harmful messages and make sure your private message is not against the terms of service!
                    </div><br>
                    <center><img style="width: 10em;" src="dynamic/pfp/<?php echo $user['pfp']; ?>"></center><br>
                </div>
                <div class="customtopRight">
                    <table id="userWall">
                        <tbody>
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM pms WHERE sto = ? ORDER BY id DESC");
                                $stmt->bind_param("s", $_SESSION['siteusername']);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if($result->num_rows === 0) echo('You have no PMs.');
                                while($row = $result->fetch_assoc()) { 
                            ?>
                            <tr>
                                <td class="tableLeft">
                                    <center><a href="profile.php?id=<?php echo getIDFromUser($row['sfrom'], $conn); ?>"><div><b><center><?php echo $row['sfrom']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['sfrom'], $conn); ?>"></a></center>
                                </td>
                                <td class="tableRight">
                                    <div><b class="date"><?php echo $row['date']; ?> | "<?php echo htmlspecialchars($row['subject']); ?>"</b></div><div><p><?php echo parseText($row['message']); ?></p></div>
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