<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/conn.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/lib/profile.php"); ?>
<?php
    $stmt = $conn->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        if($row['owner'] != $_SESSION['siteusername']) {
            die("You dont own this file");
        } else {
            $filename = $_SERVER['DOCUMENT_ROOT'] . "/dynamic/files/" . $row['filename'];
        }
    }
    
    if (!unlink($filename)) {  
        echo ("$filename cannot be deleted due to an error");  
    }  
    else {  
        echo ("$filename has been deleted");  
    }

    $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $stmt->close();

?>