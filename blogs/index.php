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
                        This doesn't really look that good... I'll try to make it look better later. <br><br><a href="new.php"><button>New Blog Post</button></a>
                    </div><br>
                    
                    <div class="login">
                        <div class="loginTopbar">
                            <b>All Blogs</b>
                        </div>
                        <div class="padding">
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM blogs WHERE visiblity = 'Visible' ORDER BY id DESC");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while($row = $result->fetch_assoc()) { 
                            ?>
                                <div class="blog">
                                    <img style="float: left;height: 4em; width: 4em;" src="/dynamic/pfp/<?php echo getPFPFromUser($row['author'], $conn); ?>">
                                    <span id="blogPost">
                                        <?php echo htmlspecialchars($row['subject']); ?> from <b><?php echo htmlspecialchars($row['author']); ?></b></a><br>
                                        <small>
                                            <?php echo $row['date']; ?><br>
                                            &nbsp;Posted by <a href="/profile.php?id=<?php echo getIDFromUser($row['author'], $conn); ?>"><?php echo $row['author']; ?></a>
                                        </small>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>
