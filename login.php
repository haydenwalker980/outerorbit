<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
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
                <div class="padding">
                    <center>
                    <?php 
                    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] && $_POST['username']) {
                            $email = htmlspecialchars(@$_POST['email']);
                            $username = htmlspecialchars(@$_POST['username']);
                            $password = @$_POST['password'];
                            $passwordhash = password_hash(@$password, PASSWORD_DEFAULT);

                        $stmt = $conn->prepare("SELECT password FROM `users` WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if(!mysqli_num_rows($result)){ { $error = "incorrect username or password"; goto skip; } }                 
                            $row = $result->fetch_assoc();
                            $hash = $row['password'];
                        
                        if(!password_verify($password, $hash)){ $error = "incorrect username or password"; goto skip; }
                        $_SESSION['siteusername'] = $username;
                        
                        header("Location: manage.php");
                    }
                    skip:

                    if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } ?>
                    <div id="login" style="width: 236px;">
                        <b>Member Login</b>
                        <form action="" method="post" id="submitform">
                            <table>
                                <tbody><tr class="email">
                                    <td class="label"><label for="email">User Name:</label></td>
                                    <td class="input"><input type="text" name="username" id="email"></td>
                                </tr>
                                <tr class="password">
                                    <td class="label"><label for="password">Password:</label></td>
                                    <td class="input"><input name="password" type="password" id="password"></td>
                                </tr>
                                <tr class="remember">
                                    <td colspan="2"><input type="checkbox" name="Remember" value="Remember" id="checkbox">
                                    <label for="checkbox">Remember my E-mail</label></td>
                                </tr>
                                <tr class="buttons">
                                    <td colspan="2"><input type="submit" value="Login" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin"></td>
                                </tr>
                                <tr class="forgot">
                                    <td colspan="2"><a href="">Forgot your password?</a></td>
                                </tr>
                            </tbody></table>
                        </form>
                    </div>
                    </center>
                </div><br>
                <table class="cols">
                    <tbody>
                        <tr>
                            <td>
                                <b>Get Started!</b><br>
                                Join for free, and view profiles, connect with others, blog, customize your profile, and much more!<br><br><br>
                                <span id="splash">» <a href="register.php">Learn More</a></span>	
                            </td>
                            <td>
                                <b>Create Your Profile!</b><br>
                                Tell us about yourself, upload your pictures, and start adding friends to your network.<br><br><br><br>
                                <span id="splash">» <a href="register.php">Start Now</a></span>		
                            </td>
                            <td>
                                <b>Browse Profiles!</b><br>
                                Read through all of the profiles on SpaceMy! See pix, read blogs, and more!<br><br><br><br>
                                <span id="splash">» <a href="users.php">Browse Now</a></span>
                            </td>
                            <td>
                                <b>Invite Your Friends!</b><br>
                                Invite your friends, and as they invite their friends your network will grow even larger!<br><br><br><br>
                                <span id="splash">» <a href="register.php">Invite Friends Now</a></span>	
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>