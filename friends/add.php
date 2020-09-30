<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>

<?php
$name = getNameFromUser((int)$_GET['id'], $conn);

if(!isset($_SESSION['siteusername']) || !isset($_GET['id'])) {
    die("You are not logged in or you did not put in an argument");
}

if($name == $_SESSION['siteusername']) {
    die("stop trying to friend yourself");
}

$stmt = $conn->prepare("SELECT * FROM friends WHERE sender = ? AND reciever = ?");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1) die('You already sent a friend request to this person');
$stmt->close();

$stmt = $conn->prepare("INSERT INTO friends (sender, reciever, status) VALUES (?, ?, 'u')");
$stmt->bind_param("ss", $_SESSION['siteusername'], $name);

$stmt->execute();
$stmt->close();

header("Location: index.php");
?>