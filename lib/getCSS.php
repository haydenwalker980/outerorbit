<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php 
header("Content-type: text/css");

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
    $css = $row['css'];
}
$stmt->close();

//please improve thsi later

$DISALLOWED = array("<?php", "?>", "behavior: url", ".php", "@import", "@\import", "@/import", "url(", "u/r/l(", "u/rl(", "ur/l(", "/u/rl(", "@/i/m/p/o/r/t"); 
$validated = str_replace($DISALLOWED, "", $css);

echo $css;
?>