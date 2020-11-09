<?php
    function getVideoFromID($id, $connection) {
        $stmt = $connection->prepare("SELECT * FROM videos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if($result->num_rows === 0) return('That video does not exist.');
        $stmt->close();

        return $user;
    }
?>