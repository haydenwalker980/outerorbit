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
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            <div class="padding">
                <div class="login">
                    <div class="loginTopbar">
                        <b>Incoming Friend Requests</b>
                    </div>
                    <div class="grid-container">
                        <?php 
                                $stmt = $conn->prepare("SELECT * FROM friends WHERE reciever = ? AND status = 'p'");
                                $stmt->bind_param("s", $_SESSION['siteusername']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows === 0) echo('You have no incoming friend requests.');
                            while($row = $result->fetch_assoc()) {
                        ?>
                            <div class="item1"><a href="/profile.php?id=<?php echo getIDFromUser($row['sender'], $conn); ?>"><div><b><center><?php echo $row['sender']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['sender'], $conn); ?>"></a><br><a href="accept.php?id=<?php echo $row['id']; ?>"><button>Accept</button></a> <a href="deny.php?id=<?php echo $row['id']; ?>"><button>Deny</button></a></div>
                        <?php } ?>
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