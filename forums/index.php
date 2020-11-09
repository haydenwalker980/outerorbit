<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $config['pr_title']; ?></title>
        <link rel="stylesheet" href="/static/css/required.css"> 
        <link rel="stylesheet" href="/static/css/table2.css"> 
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
                <small><a href="/">SpaceMy</a> / <a href="/forums/">Forums</a></small><br>
                <div class="customtopLeft">  
                    <div class="sideblog">
                        <h3>My Controls</h3>
                        <ul>
                            <li><a href="" class="man">Forum Home</a></li>
                            <li><a href="" class="man">My Subscriptions</a></li>
                            <li><a href="" class="man">My Readers</a></li>
                            <li class="last"><a href="" class="man">My Preferred List</a></li>
                        </ul>
                    </div>
                </div>
                <div class="customtopRight">  
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
                            $stmt = $conn->prepare("SELECT * FROM categories");
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
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>