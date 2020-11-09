<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/friends.php"); ?>

<?php
$name = getRecNameFromFriendRequest((int)$_GET['id'], $conn);
$sender = getSendNameFromFriendRequest((int)$_GET['id'], $conn);

if($name != $_SESSION['siteusername']) {
    $doesnotown = true;
} else if($sender != $_SESSION['siteusername']) {
    $doesnotown2 = true;
}

if($doesnotown2 == true) {
    die("You do not own this friendship."); 
}

if(!isset($_GET['id'])) {
    die("ID is not set"); 
}

$stmt = $conn->prepare("UPDATE friends SET status = 'o' WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$stmt->close();

header("Location: index.php");
?>