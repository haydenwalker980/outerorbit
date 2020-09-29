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
                width: calc( 40% - 20px );
                padding: 10px;
            }

            .customtopRight {
                float: right;
                width: calc( 60% - 20px );
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            <div class="customtopLeft">
                <div class="splashBlue">
                    You can edit your blog's visibility or your group's description here!
                </div><br>
            </div>
            <div class="customtopRight">
                <div class="blogSection">
                    <b>Your Blogs</b><br>
                    <?php 
                            $stmt = $conn->prepare("SELECT * FROM blogs WHERE author = ? ORDER BY id DESC");
                            $stmt->bind_param("s", $_SESSION['siteusername']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()) {
                    ?>
                        <span id="blogPost"><?php echo htmlspecialchars($row['subject']); ?> [<a href="editblogs.php?id=<?php echo $row['id']; ?>">edit this</a>]</span><br>
                    <?php } ?>
                </div><br>
                <div class="groupSection">
                    <b>Your Groups</b><br>
                    <?php 
                            $stmt = $conn->prepare("SELECT * FROM groups WHERE owner = ? ORDER BY id DESC");
                            $stmt->bind_param("s", $_SESSION['siteusername']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()) {
                    ?>
                        <span id="group"><?php echo htmlspecialchars($row['name']); ?> [<a href="editgroups.php?id=<?php echo $row['id']; ?>">edit this</a>]</span><br>
                    <?php } ?>
                </div>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>