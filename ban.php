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
                width: calc( 60% - 20px );
                padding: 10px;
            }

            .customtopRight {
                float: right;
                width: calc( 40% - 20px );
                padding: 10px;
            }

            #login {
                text-align: center;
                border: 1px solid #039;
                margin: 0 0 10px 0;
                padding: 5px;
            }

            .grid-container {
                display: grid;
                grid-template-columns: auto auto auto;
                grid-gap: 3px;
                padding: 3px;
            }
            
            .grid-container > div {
                text-align: center;
            }

            .grid-container2 > div img {
                width: 49px;
                height: 49px;
            }

            .grid-container2 {
                display: grid;
                grid-template-columns: auto auto auto auto;
                grid-gap: 3px;
                padding: 3px;
            }
            
            .grid-container2 > div {
                text-align: center;
            }

            .grid-container > div img {
                width: 49px;
                height: 49px;
            }

            ul {
                list-style-type: square;
                padding-left: 20px;
                margin: 0px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="headerTop">
                <a href="/index.php"><img src="/static/spacemyfall3.png"></a>
                <small id="floatRight">
                            <a href="/logout.php">Logout</a>
                    &nbsp;&nbsp;
                </small><br>
            </div>
            <br>
            <div class="padding">
                <h2 id="noMargin">You have been banned</h2>      
                You have been banned from OuterOrbit until further notice due to the following reason:
<pre>
<?php 
if(isset($_SESSION['siteusername'])) {
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `username` = ?");
    $stmt->bind_param("s", $_SESSION['siteusername']);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $split = explode("|", $row['banstatus']);
        echo $split[1];
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>
</pre>
            </div>
        </div>
        <br>
        <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/footer.php"); ?>
    </body>
</html>
