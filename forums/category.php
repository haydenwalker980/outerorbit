<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/forums.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <?php $cat = getCategoryFromID((int)$_GET['id'], $conn); ?>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <link rel="stylesheet" href="/static/css/table2.css"> 
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
        <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script src="/onLogin.js"></script>
        <style>
            #replies tr:nth-child(even){background-color: #f2f2f2;}

            #replies tr:hover {background-color: #ddd;}

            #customers th {
                background-color: #6699cc;
                color: white;
            }

            .customtopLeft {
                float: left;
                width: calc( 22% - 20px );
                padding: 10px;
            }

            .customtopRight {
                float: right;
                width: calc( 78% - 20px );
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            
            <div class="padding">
                <small><a href="/">SpaceMy</a> / <a href="/forums/">Forums</a> / <a href="/forums/category.php?id=<?php echo $_GET['id']?>"><?php echo $cat['name']?></a></small><br><br>
                <div class="customtopLeft">  
                    <div class="sideblog">
                        <h3>My Controls</h3>
                        <ul>
                            <li><a href="" class="man">Blog Home</a></li>
                            <li><a href="" class="man">My Subscriptions</a></li>
                            <li><a href="" class="man">My Readers</a></li>
                            <li class="last"><a href="" class="man">My Preferred List</a></li>
                        </ul>
                    </div>
                </div>
                <div class="customtopRight">  
                    <div class="splashBlue">
                        <h1 id="noMargin"><?php echo $cat['name']; ?></h1><?php echo $cat['description']; ?>
                        <?php 
                            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                                if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
                                if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                                if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
                                if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                                if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

                                $stmt = $conn->prepare("INSERT INTO `threads` (title, toid, message, author) VALUES (?, ?, ?, ?)");
                                $stmt->bind_param("siss", $title, $_GET['id'], $text, $_SESSION['siteusername']);
                                $text = htmlspecialchars($_POST['comment']);
                                $title = htmlspecialchars($_POST['title']);
                                $stmt->execute();
                                $stmt->close();

                                updateCategoryTime((int)$_GET['id'], $conn);
                                skipcomment:
                            }
                        ?>
                    </div><br>
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>Make New Thread</b><br>
                            <input style="width: 45em;" type="text" placeholder="Title" name="title" size="50"><br>
                            <textarea style="width: 45em;" id="desc" placeholder="Body" name="comment"></textarea><br>
                            <script>
                            var simplemde = new SimpleMDE({ element: document.getElementById("desc") });
                            </script>
                            <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                        </form>
                    </div><br>
                    <hr>
                    <table id="replies">
                        <tr>
                            <th style="width: 65%;">Title</th>
                            <th style="width: 20%;">Author</th>
                            <th style="width: 10%;">Last Reply</th>
                        </tr>
                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM threads WHERE toid = ? ORDER BY lastmodified DESC");
                            $stmt->bind_param("i", $_GET['id']);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) { 
                        ?>
                            <tr>
                                <td>
                                    <b><a href="thread.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></b><br>
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM `reply` WHERE `toid` = ?");
                                        $thread_id = $row['id'];
                                        $stmt->bind_param("i", $thread_id);
                                        $stmt->execute();
                                        $stmt->store_result();
                                        $reply_count = $stmt->num_rows;
                                    ?>
                                    <small><?php echo $reply_count?> repl<?php echo ($reply_count === 1 ? "y" : "ies")?></small>
                                </td>
                                <td>
                                    <center>
                                        <a href="/profile.php?id=<?php echo getIDFromUser($row['author'], $conn); ?>">
                                            <img style="height: 3em; width: 3em;" src="/dynamic/pfp/<?php echo getPFPFromUser($row['author'], $conn); ?>"><br>
                                            <b><?php echo $row['author']; ?></b>
                                        </a>
                                    </center>
                                </td>
                                <td><?php echo $row['lastmodified']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>