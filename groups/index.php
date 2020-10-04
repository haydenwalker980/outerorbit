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
                <div class="customtopLeft">  
                    <b><small>
                        <div class="groupssidelinks">
                            <ul>
                                <li><a href="">Groups Home</a></li>
                                <li><a href="/edit/">My Groups</a></li>
                                <li><a href="new.php">Create Group</a></li>
                                <li class="last"><a href="">Search Groups</a></li>
                            </ul>
                        </div>
                    </small></b>
                </div>

                <div class="customtopRight">  
                    <h1 id="noMargin">Groups Home</h1>Sort by:: <b>Newest</b><br>
                    <small><a href="/">SpaceMy</a> / <a href="/groups/">Groups</a></small><br><br>
                    <table>
                        <tr>
                            <th>Icon</th>
                            <th>Info</th>
                            <th>Creator</th>
                        </tr>
                        <?php 
                            $total_pages = $conn->query('SELECT COUNT(*) FROM groups WHERE visiblity = "Visible"')->fetch_row()[0]; 
                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                            $num_results_on_page = 16;

                            $stmt = $conn->prepare("SELECT * FROM groups WHERE visiblity = 'Visible' LIMIT ?,?");
                            $calc_page = ($page - 1) * $num_results_on_page;
                            $stmt->bind_param('ii', $calc_page, $num_results_on_page);
                            $stmt->execute();
                            $result = $stmt->get_result();?>
                        <div class="splashBlue">
                        <center>
                        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
                            <?php if ($page > 1): ?>
                            <a href="index.php?page=<?php echo $page-1 ?>">Prev</a>
                            <?php endif; ?>

                            <?php if ($page > 3): ?>
                            <a href="index.php?page=1">1</a>
                            ...
                            <?php endif; ?>

                            <?php if ($page-2 > 0): ?><a href="index.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a><?php endif; ?>
                            <?php if ($page-1 > 0): ?><a href="index.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a><?php endif; ?>

                            <a href="index.php?page=<?php echo $page ?>"><?php echo $page ?></a>

                            <?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><a href="index.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
                            <?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><a href="index.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

                            <?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
                            ...
                            <a href="index.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a>
                            <?php endif; ?>

                            <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
                            <a href="index.php?page=<?php echo $page+1 ?>">Next</a>
                            <?php endif; ?>
                        <?php endif; ?>
                                <br>
                                <form method="get" action="/users.php">
                                    <select name="searchmethod">
                                        <option value="new">Newest</option>
                                        <option value="old">Oldest</option>
                                        <option value="alph">Alphabetical</option>
                                    </select>
                                    <input type="submit" value="Go"> (Does not work yet)
                                </form> 
                            </center>
                        </div>
                        <?php
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
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>