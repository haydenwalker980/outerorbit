<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <link rel="stylesheet" href="/static/css/profile.css"> 
        <link rel="stylesheet" href="/lib/getCSS.php?id=<?php echo (int)$_GET['id']; ?>"> 
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script src="/onLogin.js"></script>
        <meta http-equiv="Content-Security-Policy" content="default-src 'self' youtube.com *.google.com *.gstatic.com; img-src 'self' youtube.com images.weserv.nl; style-src 'self' youtube.com 'unsafe-inline';">
        <?php $user = getUserFromId((int)$_GET['id'], $conn); ?>
        <meta property="og:title" content="<?php echo $user['username']; ?>" />
        <meta property="og:description" content="<?php echo $user['bio']; ?>" />
        <meta property="og:image" content="https://www.spacemy.xyz/dynamic/pfp/<?php echo $user['pfp']; ?>" />
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

                    $stmt = $conn->prepare("INSERT INTO `comments` (toid, author, comment) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $_GET['id'], $_SESSION['siteusername'], $text);
                    $text = htmlspecialchars($_POST['comment']);
                    $stmt->execute();
                    $stmt->close();
                    skipcomment:
                }

                if(isset($_SESSION['siteusername']) && $user['username'] == $_SESSION['siteusername']) {
                    $user['ownAccount'] = true;
                }

                $group = getGroupFromId($user['currentgroup'], $conn);
            ?>
            <br>
            <div class="padding">
                <span id="padding10">
                    <small>SpaceMy / Profile / <?php echo $user['username']; ?></small>
                </span><br>
                <div class="topLeft">  
                    <center>
                        <h1 id="noMargin"><?php echo $user['username']; ?></h1>
                        <img id="pfp" src="/dynamic/pfp/<?php echo $user['pfp']; ?>"><br>
                        <audio controls><source src="/dynamic/music/<?php echo $user['music']; ?>"></audio><br>
                    </center><br>
                    <div class="splashBlue">
                        <b>Gender</b> &bull; <?php echo htmlspecialchars($user['gender']); ?><br>
                        <b>Location</b> &bull; <?php echo htmlspecialchars($user['location']); ?><br>
                        <b>Age</b> &bull; <?php echo htmlspecialchars($user['age']); ?><br>
                        <b>Last Login</b> &bull; <?php echo htmlspecialchars($user['lastlogin']); ?><br>
                        <b>Steam URL</b> &bull; <?php if($user['steamurl'] != "") { echo "<a href='" . $user['steamurl'] . "'>" . stripURLTHingies($user['steamurl']) . "</a>"; } else { echo "Steam Not Linked"; } ?><br>
                    </div><br>
                    
                    <div class="contacting">
                        <div class="contactingTopbar">
                            Contacting <?php echo $user['username']; ?>
                        </div>
                        <div class="padding">
                            <ul>
                                <li><a href="pm.php?id=<?php echo $user['id']; ?>">Send Message</a></li>
                                <li><a href="/friends/add.php?id=<?php echo $user['id']; ?>">Add to Friends</a></li>
                                <li><a href="block.php?id=<?php echo $user['id']; ?>">Block User</a></li>
                                <li><a href="report.php?id=<?php echo $user['id']; ?>">Report User</a></li>
                            </ul>
                        </div>
                    </div><br>
                        <table id="Table1" class="interestsAndDetails" width="293" cellspacing="0" cellpadding="0" bordercolor="#426BBA" border="1" bgcolor="#6699cc">
                                <tbody>
                                    <tr>
                                        <td class="text tdborder" wrap="" width="293" valign="middle" bgcolor="#6699cc" align="left">&nbsp;
                                            <span class="whitetext12">
                                                <big><?php echo $user['username']; ?>'s Interests</big>
                                            </span>
                                        </td>
                                        </tr>
                                            <tr valign="top">
                                                <td class="tdborder">
                                                <table id="Table2" width="293" cellspacing="3" cellpadding="3" bordercolor="#000000" border="0" bgcolor="#ffffff" align="center">
                                                <tbody>
                                            <tr id="GeneralRow"><td width="100" valign="top" nowrap="" bgcolor="#97BEEC" align="left"><span class="lightbluetext8">Interests</span></td><td id="ProfileGeneral" width="175" bgcolor="#C9E0FA"><?php echo htmlspecialchars($user['interests']); ?></td></tr>
                                            <tr id="MusicRow"><td width="100" valign="top" nowrap="" bgcolor="#97BEEC" align="left"><span class="lightbluetext8">Music</span></td><td id="ProfileMusic" width="175" bgcolor="#C9E0FA"><?php echo htmlspecialchars($user['interestsmusic']); ?></td></tr>
                                            <tr id="Groups"><td width="100" valign="top" nowrap="" bgcolor="#97BEEC" align="left"><span class="lightbluetext8">Groups:</span></td><td id="ProfileHeroes" width="175" bgcolor="#C9E0FA"><?php echo "<a href='/groups/view.php?id=" . $group['id'] . "'>" . $group['name'] . "</a>"; ?>
                                            </td>
                                        </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    <div class="url">
                    <b>SpaceMy URL:</b><br>
                    &nbsp;&nbsp;https://spacemy.xyz/profile.php?id=<?php echo $user['id']; ?>
                    </div>
                </div>
                <div class="topRight">
                    <div class="extended">
                        <center><big><b><?php echo htmlspecialchars($user['username']); ?> is in your extended network</b></big></center>
                    </div><br>
                    <div class="blogSection">
                        <b><?php echo htmlspecialchars($user['username']); ?>'s Latest Blog Entry</b><br><br>
                        <?php 
                                $stmt = $conn->prepare("SELECT * FROM blogs WHERE author = ? ORDER BY id DESC");
                                $stmt->bind_param("s", $user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_assoc()) {
                                if($row['visiblity'] != "Link Only") {
                        ?>
                            <span id="blogPost"><?php echo htmlspecialchars($row['subject']); ?> [<a href="blogs/view.php?id=<?php echo $row['id']; ?>">view more</a>]</span><br>
                        <?php } } ?>
                    </div>
                    <div class="about">
                        <b>About <?php echo htmlspecialchars($user['username']); ?></b>
                    </div>
                    <div class="bio">
                        <?php echo parseText($user['bio']); ?>
                    </div><br>
                    <div class="about">
                        <b><?php echo htmlspecialchars($user['username']); ?>'s Friends</b>
                    </div><br>
                    <div class="grid-container">
                        <?php 
                                $stmt = $conn->prepare("SELECT * FROM friends WHERE reciever = ? AND status = 'a'");
                                $stmt->bind_param("s", $user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_assoc()) {
                        ?>
                            <div class="item1"><a href="/profile.php?id=<?php echo getIDFromUser($row['sender'], $conn); ?>"><div><b><center><?php echo $row['sender']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['sender'], $conn); ?>"></a><br></div>
                        <?php } ?>
                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM friends WHERE sender = ? AND status = 'a'");
                            $stmt->bind_param("s", $user['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_assoc()) {
                        ?>
                            <div class="item1"><a href="/profile.php?id=<?php echo getIDFromUser($row['reciever'], $conn); ?>"><div><b><center><?php echo $row['reciever']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['reciever'], $conn); ?>"></a><br></div>
                        <?php } ?>
                    </div>
                    <div class="about">
                        <b><?php echo htmlspecialchars($user['username']); ?>'s comments</b>
                    </div><br>
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>Comment</b><br>
                            <textarea cols="32" placeholder="Comment" name="comment"></textarea><br>
                            <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                        </form>
                    </div><br>
                    <table id="userWall">
                        <tbody>
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM comments WHERE toid = ? ORDER BY id DESC");
                                $stmt->bind_param("i", $_GET['id']);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while($row = $result->fetch_assoc()) { 
                            ?>
                            <tr>
                                <td class="tableLeft">
                                    <a href="profile.php?id=<?php echo getIDFromUser($row['author'], $conn); ?>"><div><b><center><?php echo $row['author']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['author'], $conn); ?>"></a>
                                </td>
                                <td class="tableRight">
                                    <div><b class="date"><?php echo $row['date']; ?></b> <span id="floatRight"><?php if(isset($user['ownAccount'])) { echo "<a href='/lib/deletecomment.php?id=" . $row['id'] . "'>[x]</a>"; } ?></span></div><div><p><?php echo parseText($row['comment']); ?></p></div>
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