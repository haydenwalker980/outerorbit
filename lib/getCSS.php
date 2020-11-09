<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php 
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use MatthiasMullie\Minify;

header("Content-type: text/css");

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
    $css = $row['css'];
}
$stmt->close();

$DISALLOWED = array("<?php", "?>", "behavior: url", ".php");
$validated = str_replace($DISALLOWED, "", $css);

$minifier = new Minify\CSS($validated);
echo $minifier->minify();

?>