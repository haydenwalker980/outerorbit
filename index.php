<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <style>
            .customtopLeft {
                float: left;
                width: calc( 60% - 20px );
                padding: 10px;
            }

            .customtopRight {
                float: right;
                width: calc( 40% - 20px );
                padding: 10px;
            }

            #login {
                text-align: center;
                border: 1px solid #039;
                margin: 0 0 10px 0;
                padding: 5px;
            }

            .grid-container {
                display: grid;
                grid-template-columns: auto auto auto;
                grid-gap: 3px;
                padding: 3px;
            }
            
            .grid-container > div {
                text-align: center;
            }

            .grid-container > div img {
                width: 49px;
                height: 49px;
            }

            ul {
                list-style-type: square;
                padding-left: 20px;
                margin: 0px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            <div class="customtopLeft">
                <div class="padding">
                    <div class="hero">
                        <h1 id="noMargin">spacemy.xyz</h1>
                        A opensourced passion project to replicate/mimmic the feel and customizability of 2008 MySpace. This is currently heavily under development, so expect some bugs or security bugs once in a while.<br><br><a href="register.php"><button>Join</button></a>
                    </div><br>
                    <div class="splashBlue">
                        Always make sure you're visiting the real spacemy.xyz!
                        <ul>
                            <li>Check the URL in your browser.</li>
                            <li>Make sure it begins with http://www.spacemy.xyz/</li>
                            <li>If ANY OTHER PAGE asks for your info, DON'T LOG IN!</li>
                        </ul>
                    </div>
                    <br>
                    <div class="login">
                        <div class="loginTopbar">
                            <b>SpaceMy Member Blogs</b><span style="float: right; color: white;"><small><a style="color: white;" href="/blogs/">[view more]</a></small></span>
                        </div>
                        <ul>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM blogs WHERE visiblity = 'Visible' ORDER BY id DESC LIMIT 10");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) { 
                        ?>
                            <li><span id="blogPost"><?php echo htmlspecialchars($row['subject']); ?> by <b><?php echo htmlspecialchars($row['author']); ?></b> [<a href="/blogs/view.php?id=<?php echo $row['id']; ?>">+</a>]</span></span></li>
                        <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="customtopRight">
                <div id="login">
					<b>Member Login</b>
					<form>
						<table>
							<tbody><tr class="email">
								<td class="label"><label for="email">E-Mail:</label></td>
								<td class="input"><input type="text" name="email" id="email"></td>
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
								<td colspan="2"><a href="login.php"><img src="/static/button_login_main.gif" alt="SIGN UP NOW!" name="singup" id="singup" border="0"></a>
								<a href="register.php"><img src="/static/button_signup_main.gif" alt="SIGN UP NOW!" name="singup" id="singup" border="0"></a></td>
							</tr>
							<tr class="forgot">
								<td colspan="2"><a href="">Forgot your password?</a></td>
							</tr>
						</tbody></table>
					</form>
				</div><br>
                <div class="login">
                    <div class="loginTopbar">
                        <b>Cool New People</b><span style="float: right; color: white;"><small><a style="color: white;" href="/users.php">[view more]</a></small></span>
                    </div>
                    <div class="grid-container">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC LIMIT 3");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) { 
                        ?>
                            <div class="item1"><a href="profile.php?id=<?php echo getIDFromUser($row['username'], $conn); ?>"><div><center><?php echo $row['username']; ?></center></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['username'], $conn); ?>"></a></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="padding10">
                <table class="cols" style="margin-top: 780px;">
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
                                Read through all of the profiles on SpaceMy! See pix, read blogs, and more!<br><br><br><br><br>
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
