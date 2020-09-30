<?php
function getCategoryFromID($id, $connection) {
        $stmt = $connection->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    return $user;
}

function getPostFromID($id, $connection) {
    $stmt = $connection->prepare("SELECT * FROM threads WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

return $user;
}

?>