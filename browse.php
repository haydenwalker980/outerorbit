<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <link rel="stylesheet" href="/static/css/table2.css"> 
    </head>
    <body>
        <div class="container">
            <?php 
                require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); 

                switch($_GET['searchmethod']) {
                    case "users":
                        $type = "users";
                        $sql = "SELECT * FROM users WHERE username LIKE ?";
                        break;
                    case "blog":
                        $type = "blogs";
                        $sql = "SELECT * FROM blogs WHERE subject LIKE ?";
                        break;
                    case "groups":
                        $type = "groups";
                        $sql = "SELECT * FROM groups WHERE name LIKE ?";
                        break;
                    default:
                        die("Invalid type of search");
                        break;
                }
            ?>
            <br>
                <div class="padding">
                    <center>
                        <form method="get" action="/browse.php">
                            <select name="searchmethod">
                                <option value="users">User</option>
                                <option value="blog">Blog</option>
                                <option value="groups">Group</option>
                            </select>
                            <input type="text" size="30" name="search"> <input type="submit" value="Search">
                        </form> 
                    </center><br>
                <div class="login">
                    <div class="loginTopbar">
                        <b>Searching for type "<?php echo htmlspecialchars($_GET['searchmethod']); ?>"</b>
                    </div>
                    <?php if($type == "users") { ?>
                        <div class="grid-container">
                    <?php } else if($type == "groups") { ?>
                        <table>
                            <tr>
                                <th>Icon</th>
                                <th>Info</th>
                                <th>Creator</th>
                            </tr>
                    <?php } ?>
                        <?php
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('s', $search);
                            $search = "%" . htmlspecialchars($_GET['search']) . "%";
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) { ?>
                        <?php if($type == "users") { ?>
                            <div class="item1"><a href="profile.php?id=<?php echo getIDFromUser($row['username'], $conn); ?>"><div><b><center><?php echo $row['username']; ?></center></b></div><img src="/dynamic/pfp/<?php echo getPFPFromUser($row['username'], $conn); ?>"></a></div>
                        <?php } else if($type == "blogs") { ?>
                            <span id="blogPost"><?php echo htmlspecialchars($row['subject']); ?> from <b><?php echo htmlspecialchars($row['author']); ?></b> [<a href="/blogs/view.php?id=<?php echo $row['id']; ?>">view more</a>]<span id="floatRight"><?php echo $row['date']; ?></span></span><br>
                        <?php } else if($type == "groups") { ?>
                            <tr>
                                <td><img style="height: 4em; width: 4em;" src="/dynamic/groups/<?php echo $row['pic']; ?>"></td>
                                <td><b><?php echo $row['name']; ?></b> <span id="floatRight"><?php echo $row['date']; ?></span><br><?php echo parseText($row['description']); ?><br><a href="view.php?id=<?php echo $row['id']; ?>"><button>More Info</button></a></td>
                                <td><center><a href="/profile.php?id=<?php echo getIDFromUser($row['owner'], $conn); ?>"><div><b><?php echo $row['owner']; ?></b></div><img style="height: 4em; width: 4em;" src="/dynamic/pfp/<?php echo getPFPFromUser($row['owner'], $conn); ?>"></a></center></td>
                            </tr>
                            <?php } 
                        }?>
                    <?php if($type == "users") { ?>
                        </div>
                    <?php } else if($type == "groups") { ?>
                        </table>
                    <?php } ?>
                </div>

                <br>
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