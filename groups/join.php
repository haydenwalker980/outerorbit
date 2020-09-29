<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<?php 
    if(!isset($_SESSION['siteusername'])) { header("Location: ../login.php"); }
    $stmt = $conn->prepare("UPDATE users SET currentgroup = ? WHERE username = ?");
    $stmt->bind_param("is", $_GET['id'], $_SESSION['siteusername']);
    $stmt->execute();
    $stmt->close();    

    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>