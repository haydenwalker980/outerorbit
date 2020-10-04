<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/friends.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
    </head>
    <body>
        <div class="container">
            <?php
                $stmt = $conn->prepare("SELECT * FROM `friends` WHERE `reciever` = ? AND `status` = 'u'");
                $stmt->bind_param("s", $_SESSION['siteusername']);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $substmt = $conn->prepare("UPDATE `friends` SET status = 'p' WHERE id = ?");
                    $substmt->bind_param("i", $row['id']);
                    $substmt->execute();
                    $substmt->close();
                }
                $stmt->close();

                require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php");
            ?>
            <br>
            <div class="padding">
                <div style="overflow:auto;">
                    <div class="login" style="width:calc(50% - 10px);float:left;">
                        <div class="loginTopbar">
                            <b>Incoming Friend Requests</b>
                        </div>
                        <ul>
                            <?php 
                                $stmt = $conn->prepare("SELECT * FROM friends WHERE reciever = ? AND (status != 'a' AND status != 'd')");
                                $stmt->bind_param("s", $_SESSION['siteusername']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result->num_rows === 0) echo('<li>You have no incoming friend requests.</li>');
                                while($row = $result->fetch_assoc()) {
                            ?>
                            <li>
                                <a href="/profile.php?id=<?php echo getIDFromUser($row['sender'], $conn); ?>">
                                    <b><?php echo $row['sender']?></b>
                                    <img style="height:1em;width:1em;" src="/dynamic/pfp/<?php echo getPFPFromUser($row['sender'], $conn)?>">
                                </a><br>
                                <a href="/friends/accept.php?id=<?php echo $row['id']?>"><button>Accept</button></a>
                                <a href="/friends/deny.php?id=<?php echo $row['id']?>"><button>Deny</button></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="login" style="width:calc(50% - 10px);float:right;">
                        <div class="loginTopbar">
                            <b>Outgoing Friend Requests</b>
                        </div>
                        <ul>
                            <?php 
                                $stmt = $conn->prepare("SELECT * FROM friends WHERE sender = ? AND (status != 'a' AND status != 'd')");
                                $stmt->bind_param("s", $_SESSION['siteusername']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if($result->num_rows === 0) echo('<li>You have no outgoing friend requests.</li>');
                                while($row = $result->fetch_assoc()) {
                            ?>
                            <li>
                                <a href="/profile.php?id=<?php echo getIDFromUser($row['sender'], $conn); ?>">
                                    <b><?php echo $row['reciever']?></b>
                                    <img style="height:1em;width:1em;" src="/dynamic/pfp/<?php echo getPFPFromUser($row['reciever'], $conn)?>">
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div><br>
                <div class="login">
                    <div class="loginTopbar">
                        <b>Current Friends</b>
                    </div>
                    <div class="grid-container">
                        <?php 
                                $stmt = $conn->prepare("SELECT * FROM friends WHERE reciever = ? AND status = 'a'");
                                $stmt->bind_param("s", $_SESSION['siteusername']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_assoc()) {
                        ?>
                            <div class="item1"><a href="/profile.php?id=<?php echo getIDFromUser($row['sender'], $conn); ?>"><div><b><center><?php echo $row['sender']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['sender'], $conn); ?>"></a><br><a href="revoke.php?id=<?php echo $row['id']; ?>"><button>Unfriend</button></a></div>
                        <?php } ?>
                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM friends WHERE sender = ? AND status = 'a'");
                            $stmt->bind_param("s", $_SESSION['siteusername']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_assoc()) {
                        ?>
                            <div class="item1"><a href="/profile.php?id=<?php echo getIDFromUser($row['reciever'], $conn); ?>"><div><b><center><?php echo $row['reciever']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['reciever'], $conn); ?>"></a><br><a href="revoke.php?id=<?php echo $row['id']; ?>"><button>Unfriend</button></a></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>