<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/register.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            <div class="padding">
                <div class="padding">
                    <?php 
                    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] && $_POST['username']) {
                        $email = htmlspecialchars(@$_POST['email']);
                        $username = htmlspecialchars(@$_POST['username']);
                        $password = @$_POST['password'];
                        $passwordhash = password_hash(@$password, PASSWORD_DEFAULT);
                        
                        if($_POST['password'] !== $_POST['confirm']){ $error = "password and confirmation password do not match"; goto skip; }
    
                        if(strlen($username) > 21) { $error = "your username must be shorter than 21 characters"; goto skip; }
                        if(strlen($password) < 8) { $error = "your password must be at least 8 characters long"; goto skip; }
                        if(!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password)) { $error = "please include both letters and numbers in your password"; goto skip; }
                        if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skip; }
                        if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skip; }
    
                        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if($result->num_rows) { $error = "there's already a user with that same name!"; goto skip; }
                        
                        if(register($username, $email, $passwordhash, $conn)) {
                            $_SESSION['siteusername'] = htmlspecialchars($username);
                            header("Location: manage.php");
                        } else {
                            $error = "There was an unknown error making your account.";
                        }	
                    }
                    skip:
                    ?>
                    <?php if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } ?>
                    <center>
                    <div id="login" style="width: 236px;">
                        <b>Member Register</b>
                        <form action="" method="post" id="submitform">
                            <table>
                                <tbody><tr class="email">
                                    <td class="label"><label for="email">E-Mail:</label></td>
                                    <td class="input"><input type="email" name="email" id="email"></td>
                                </tr>
                                <tr class="password">
                                    <td class="label"><label for="password">Password:</label></td>
                                    <td class="input"><input name="password" type="password" id="password"></td>
                                </tr>
                                <tr class="password">
                                    <td class="label"><label for="confirm">Confirm Password:</label></td>
                                    <td class="input"><input name="confirm" type="password" id="confirm"></td>
                                </tr>
                                <tr class="username">
                                    <td class="label"><label for="username">Username:</label></td>
                                    <td class="input"><input name="username" type="text" id="username"></td>
                                </tr>
                                <tr class="remember">
                                    <td colspan="2"><input type="checkbox" name="Remember" value="Remember" id="checkbox">
                                    <label for="checkbox">Remember my E-mail</label></td>
                                </tr>
                                <tr class="buttons">
                                    <td colspan="2"><input type="submit" value="Register" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin"></td>
                                </tr>
                                <tr class="forgot">
                                    <td colspan="2"><a href="">Forgot your password?</a></td>
                                </tr>
                            </tbody></table>
                        </form>
                        </center>
                    </div>
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