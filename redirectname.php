<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<?php
$name = str_replace("-", " ", $_GET['name']);

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
    header("Location: /profile.php?id=" . htmlspecialchars($row['id']));
}
$stmt->close();