<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
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
                            <span id="blogPost"><?php echo htmlspecialchars($row['subject']); ?> from <b><?php echo htmlspecialchars($row['author']); ?></b> [<a href="/blogs/view.php?id=<?php echo $row['id']; ?>">view more</a>]<span id="floatRight"><?php echo $row['date']; ?></span></span><br>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>