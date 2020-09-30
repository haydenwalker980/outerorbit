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
            <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/header.php"); ?>
            <br>
            
            <div class="padding">
                <small><a href="/">SpaceMy</a> / <a href="/forums/">Forums</a></small><br>
                <div class="splashBlue">
                    <h1 id="noMargin">SpaceMy Forums</h1>These are heavily under construction. Don't expect everything to completely work at this point.
                </div><br>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Last Modified</th>
                    </tr>
                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY id DESC");
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while($row = $result->fetch_assoc()) { 
                    ?>
                        <tr>
                            <td><a href="category.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['lastmodified']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>