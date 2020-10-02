<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>

<?php
$user = getUserFromId((int)$_GET['id'], $conn);

$stmt = $conn->prepare("SELECT * FROM groups WHERE id = ? AND owner = ?");
$stmt->bind_param("is", $user['currentgroup'], $_SESSION['siteusername']);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0) die('u dont own this group');
$stmt->close();

$stmt = $conn->prepare("UPDATE users SET currentgroup = 0 WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$stmt->close();

header("Location: index.php");
?>