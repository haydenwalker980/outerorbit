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
                <small>SpaceMy / Groups / Index</small><br>
                <h1 id="noMargin">Groups</h1>Sort by:: <b>Newest</b><br><a href="new.php">New Group</a><br><br>
                <table>
                    <tr>
                        <th>Icon</th>
                        <th>Info</th>
                        <th>Creator</th>
                    </tr>
                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM groups WHERE visiblity = 'Visible' ORDER BY id DESC");
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while($row = $result->fetch_assoc()) { 
                    ?>
                        <tr>
                            <td><img style="height: 4em; width: 4em;" src="/dynamic/groups/<?php echo $row['pic']; ?>"></td>
                            <td>
                                <b><?php echo $row['name']; ?></b>
                                <span id="floatRight" style="text-align:right;">
                                    <?php echo $row['date']; ?><br>
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `currentgroup` = ?");
                                        $stmt->bind_param('i', $row['id']);
                                        $stmt->execute();
                                        $stmt->store_result();
                                        $membercount = $stmt->num_rows();
                                        echo $membercount . " member" . ($membercount === 1 ? "" : "s");
                                        $stmt->close();
                                    ?>
                                </span><br>
                                <?php echo parseText($row['description']); ?><br>
                                <a href="view.php?id=<?php echo $row['id']; ?>"><button>More Info</button></a>
                            </td>
                            <td><center><a href="/profile.php?id=<?php echo getIDFromUser($row['owner'], $conn); ?>"><div><b><?php echo $row['owner']; ?></b></div><img style="height: 4em; width: 4em;" src="/dynamic/pfp/<?php echo getPFPFromUser($row['owner'], $conn); ?>"></a></center></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>