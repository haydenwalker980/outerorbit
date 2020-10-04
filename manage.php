<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/manage.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <?php 
            if(!isset($_SESSION['siteusername'])) { redirectToLogin(); }
            $user = getUserFromName($_SESSION['siteusername'], $conn); 
            //updateUserBio();
            
            if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['bioset']) {
                updateUserBio($_SESSION['siteusername'], $_POST['bio'], $conn);
                header("Location: manage.php");
            } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['cssset']) {
                updateUserCSS($_SESSION['siteusername'], $_POST['css'], $conn);
                header("Location: manage.php");
            } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['genderset']) {
                updateUserGender($_SESSION['siteusername'], $_POST['gender'], $conn);
                header("Location: manage.php");
            } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['ageset']) {
                updateUserAge($_SESSION['siteusername'], $_POST['age'], $conn);
                header("Location: manage.php");
            } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['locationset']) {
                updateUserLocation($_SESSION['siteusername'], $_POST['location'], $conn);
                header("Location: manage.php");
            } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['interestsmusicset']) {
                updateUserInterestMusic($_SESSION['siteusername'], $_POST['interestsmusic'], $conn);
                header("Location: manage.php");
            } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['interestsset']) {
                updateUserInterest($_SESSION['siteusername'], $_POST['interests'], $conn);
                header("Location: manage.php");
            } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['pfpset']) {
                //This is terribly awful and i will probably put this in a function soon
                $target_dir = "dynamic/pfp/";
                $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
                $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $imageFileType;

                $target_file = $target_dir . $target_name;
                
                $uploadOk = true;
                $movedFile = false;

                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $fileerror = 'unsupported file type. must be jpg, png, jpeg, or gif';
                    $uploadOk = false;
                }

                if (file_exists($target_file)) {
                    $movedFile = true;
                } else {
                    $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                }

                if ($uploadOk) {
                    if ($movedFile) {
                        $stmt = $conn->prepare("UPDATE users SET pfp = ? WHERE `users`.`username` = ?;");
                        $stmt->bind_param("ss", $target_name, $_SESSION['siteusername']);
                        $stmt->execute(); 
                        $stmt->close();
                        header("Location: manage.php");
                    } else {
                        $fileerror = 'fatal error';
                    }
                }
            } else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['songset']) {
                $uploadOk = true;
                $movedFile = false;

                $target_dir = "dynamic/music/";
                $songFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
                $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $songFileType;

                $target_file = $target_dir . $target_name;

                if($songFileType != "ogg" && $songFileType != "mp3") {
                    $fileerror = 'unsupported file type. must be mp3 or ogg<hr>';
                    $uploadOk = false;
                }

                if (file_exists($target_file)) {
                    $movedFile = true;
                } else {
                    $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                }

                if ($uploadOk) {
                    if ($movedFile) {
                        $stmt = $conn->prepare("UPDATE users SET music = ? WHERE `users`.`username` = ?;");
                        $stmt->bind_param("ss", $target_name, $_SESSION['siteusername']);
                        $stmt->execute(); 
                        $stmt->close();
                        header("Location: manage.php");
                    } else {
                        $fileerror = 'fatal error' . $_FILES["fileToUpload"]["error"] . '<hr>';
                    }
                }
            }
        ?>
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
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <div class="padding">
                <span style="padding-left: 20px;">
                    <h1 id="noMargin">&nbsp;Profile Edit</h1>
                </span>
                <div class="customtopLeft">
                    <div class="splashBlue">
                        You may enter CSS in the CSS text field. Javascript is not allowed and will be filtered out. Do not use CSS maliciously or your account will be disabled.
                    </div><br>
                    <center><img style="width: 10em;" src="dynamic/pfp/<?php echo $user['pfp']; ?>"></center><br>
                    <center> 
                        <audio style="width: 10em;" controls><source src="/dynamic/music/<?php echo $user['music']; ?>"></audio> <br><a href="/profile.php?id=<?php echo $user['id']; ?>">View your profile</a>
                    </center>
                </div>
                <div class="customtopRight">
                    <div class="splashBlue">
                        <?php if(isset($fileerror)) { echo "<small style='color:red'>" . $fileerror . "</small><br>"; } ?>
                        <center>
                        <?php
                            ini_set('display_errors', 1);
                            ini_set('display_startup_errors', 1);
                            error_reporting(E_ALL);

                            if(!isset($_SESSION['steamid'])) {
                                loginbutton("rectangle"); //login button
                            } else {
                                include($_SERVER['DOCUMENT_ROOT'] . '/vendor/smith197/steamauthentication/steamauth/userInfo.php'); //To access the $steamprofile array
                                logoutbutton(); //Logout Button
                                updateSteamURL($steamprofile['profileurl'], $_SESSION['siteusername'], $conn);
                            }     
                        ?>
                        </center>
                        <form method="post" enctype="multipart/form-data">
                            <b>Profile Picture</b><br>
                            <input type="file" name="fileToUpload" id="fileToUpload">
                            <input type="submit" value="Upload Image" name="pfpset">
                        </form><br>
                        <form method="post" enctype="multipart/form-data">
                            <b>Song</b><br>
                            <input type="file" name="fileToUpload" id="fileToUpload">
                            <input type="submit" value="Upload Song" name="songset">
                        </form><br>
                        <form method="post" enctype="multipart/form-data">
                            <b>Bio</b><br>
                            <textarea cols="56" placeholder="Bio" name="bio"><?php echo $user['bio'];?></textarea><br>
                            <input name="bioset" type="submit" value="Set">
                        </form><br>
                        <form method="post" enctype="multipart/form-data">
                            <b>Interests</b><br>
                            <textarea cols="56" placeholder="Interests" name="interests"><?php echo $user['interests'];?></textarea><br>
                            <input name="interestsset" type="submit" value="Set">
                        </form><br>
                        <form method="post" enctype="multipart/form-data">
                            <b>Music Interests</b><br>
                            <textarea cols="56" placeholder="Interests Music" name="interestsmusic"><?php echo $user['interestsmusic'];?></textarea><br>
                            <input name="interestsmusicset" type="submit" value="Set">
                        </form><br>
                        <form method="post" enctype="multipart/form-data">
                            <b>CSS</b><br>
                            <textarea cols="56" rows="16" placeholder="CSS" name="css"><?php echo $user['css'];?></textarea><br>
                            <input name="cssset" type="submit" value="Set">
                        </form><br>
                        <form method="post">
                            <b>Age</b> <br><input value="<?php echo $user['age']; ?>" type="text" name="age" required="required" row="4"></b><br>
                            <input type="submit" value="Set" name="ageset">
                        </form><br>
                        
                        <form method="post">
                            <b>Location</b> <br><input value="<?php echo $user['location']; ?>" type="text" name="location" required="required" row="4"></b><br>
                            <input type="submit" value="Set" name="locationset">
                        </form><br>
                        
                        <form method="post">
                            <b>Gender</b> <br><input value="<?php echo $user['gender']; ?>" type="text" name="gender" required="required" row="4"></b><br>
                            <input type="submit" value="Set" name="genderset">
                        </form><br>

                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>