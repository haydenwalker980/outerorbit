<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/forums.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <?php $thread = getPostFromID((int)$_GET['id'], $conn); ?>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <link rel="stylesheet" href="/static/css/table2.css"> 
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
        <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
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
                <small>
                    <a href="/">SpaceMy</a> /
                    <a href="/forums/">Forums</a> /
                    <a href="/forums/category.php?id=<?php echo $thread['toid']?>"><?php echo getCategoryFromID((int)$thread['toid'], $conn)['name']?></a> /
                    <a href="/forums/thread.php?id=<?php echo $_GET['id']?>"><?php echo $thread['title']?></a>
                </small><br><br>

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
                    <?php 
                        if($_SERVER['REQUEST_METHOD'] == 'POST') {
                            if(!isset($_SESSION['siteusername'])){ $error = "you are not logged in"; goto skipcomment; }
                            if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                            if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
                            if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                            if(!validateCaptcha($config['recaptcha_secret'], $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

                            $stmt = $conn->prepare("INSERT INTO `reply` (toid, author, text) VALUES (?, ?, ?)");
                            $stmt->bind_param("iss", $_GET['id'], $_SESSION['siteusername'], $text);
                            $text = htmlspecialchars($_POST['comment']);
                            $stmt->execute();
                            $stmt->close();

                            updateThreadTime((int)$_GET['id'], $conn);
                            skipcomment:
                        }
                    ?>
                    <div class="comment">
                        <form method="post" enctype="multipart/form-data" id="submitform">
                            <?php if(isset($error)) { echo $error . "<br>"; } ?>
                            <b>Reply</b><br>
                            <textarea style="width: 45em;" id="commentthing" placeholder="Body" name="comment"></textarea><br>
                            <script>
                            var simplemde = new SimpleMDE({ element: document.getElementById("commentthing") });
                            </script>
                            <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_sitekey']; ?>" data-callback="onLogin">
                        </form>
                    </div><br>
                    <hr>
                    <div id="originalpost">
                        <img style="float:right;height: 5em; width: 5em;" src="/dynamic/pfp/<?php echo getPFPFromUser($thread['author'], $conn); ?>">
                        <h2 id="noMargin" style="display: inline;"><?php echo $thread['title']?></h2> by
                        <small>
                            <a href="/profile.php?id=<?php echo getIDFromUser($thread['author'], $conn); ?>">
                                <b><?php echo $thread['author']; ?></b>
                            </a> - <?php echo $thread['date']; ?>
                        </small><br>
                        <?php echo parseText($thread['message']); ?><br>
                    </div>
                    <table id="replies">
                        <tr>
                            <th style="width: 20%;">Author</th>
                            <th style="width: 70%;">Text</th>
                            <th style="width: 10%;">Date</th>
                        </tr>
                        <?php 
                            $stmt = $conn->prepare("SELECT * FROM reply WHERE toid = ?");
                            $stmt->bind_param("i", $_GET['id']);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) { 
                        ?>
                            <tr>
                                <td>
                                    <center>
                                        <a href="/profile.php?id=<?php echo getIDFromUser($row['author'], $conn); ?>">
                                            <img style="height: 3em; width: 3em;" src="/dynamic/pfp/<?php echo getPFPFromUser($row['author'], $conn); ?>"><br>
                                            <b><?php echo $row['author']; ?></b>
                                        </a><br>
                                        <small><?php echo getPosts($row['author'], $conn); ?> Replies</small>
                                    </center>
                                </td>
                                <td><?php echo parseText($row['text']); ?></td>
                                <td><?php echo $row['date']; ?></td>
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