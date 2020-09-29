<?php
function getAllPendingFriendRequests($useranme, $connection) {
        $stmt = $connection->prepare("SELECT * FROM friends WHERE reciever = ? AND status = 'p'");
        $stmt->bind_param("s", $useranme);
        $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($result->num_rows === 0) return('You have no incoming friend requests.');
    $stmt->close();

    return $user;
}

function getSendNameFromFriendRequest($id, $connection) {
    $stmt = $connection->prepare("SELECT sender FROM friends WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $sender = $row['sender'];
    }
    return $sender;
    $stmt->close();
}

function getRecNameFromFriendRequest($id, $connection) {
    $stmt = $connection->prepare("SELECT reciever FROM friends WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $rec = $row['reciever'];
    }
    return $rec;
    $stmt->close();
}
?>