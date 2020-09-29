<?php
function getUserFromId($id, $connection) {
        $stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
	if($result->num_rows === 0) return('That user does not exist.');
	$stmt->close();

	return $user;
}

function getGroupFromId($id, $connection) {
        $stmt = $connection->prepare("SELECT * FROM groups WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($result->num_rows === 0) { $group['name'] = "None"; $group['id'] = 0; };
    $stmt->close();

    return $user;
}

function getInfoFromBlog($id, $connection) {
        $stmt = $connection->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($result->num_rows === 0) return('That blog does not exist.');
    $stmt->close();

    return $user;
}

function archiveAllUserInfo($username, $connection) {
    $stmt = $connection->prepare("UPDATE comments SET comment = '[archived]' WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $connection->prepare("UPDATE blogs SET message = '[archived]' WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $connection->prepare("UPDATE blogcomments SET comment = '[archived]' WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $connection->prepare("UPDATE groupcomments SET comment = '[archived]' WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $connection->prepare("UPDATE groups SET description = '[archived]', name = '[archived]', pic = '51zLZbEVSTL._AC_SX679_.jpg' WHERE owner = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    return true;
}

function delPostsFromUser($username, $conn) {
    $stmt = $conn->prepare("DELETE FROM comments WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM blogs WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM blogcomments WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM groupcomments WHERE author = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM groups WHERE owner = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    return true;
}

function delAccount($username, $connection) {
        $stmt = $connection->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}

function isAdmin($username, $conn) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND status = 'admin'");
    $stmt->bind_param("s", $_SESSION['siteusername']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0) { return false; } else { return true; }
    $stmt->close();
}

function UpdateLoginTime($username, $connection) {
    $stmt = $connection->prepare("UPDATE users SET lastlogin = now() WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}

function deleteComment($id, $conn) {
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

function getUserFromName($name, $connection) {
        $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if($result->num_rows === 0) return('That user does not exist.');
    $stmt->close();

    return $user;
}

function getIDFromUser($name, $connection) {
    $stmt = $connection->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $id = $row['id'];
    }
    return $id;
    $stmt->close();
}

function getNameFromUser($id, $connection) {
    $stmt = $connection->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $id = $row['username'];
    }
    return $id;
    $stmt->close();
}

function getPFPFromUser($name, $connection) {
    $stmt = $connection->prepare("SELECT pfp FROM users WHERE username = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $pfp = $row['pfp'];
    }
    return $pfp;
    $stmt->close();
}

function updateSteamURL($url, $username, $connection) {
    $stmt = $connection->prepare("UPDATE users SET steamurl = ? WHERE username = ?");
    $stmt->bind_param("ss", $url, $username);
    $stmt->execute();
    $stmt->close();
}

function parseText($text) {
    $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
    $text = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $text);
    $text = str_replace(PHP_EOL, "<br>", $text);

    return $text;
}

function stripURLTHingies($url) {
    $replace = array("https://steamcommunity.com/id/", "/");
    return str_replace($replace, "", $url);
}

if(isset($_SESSION['siteusername'])) {
    UpdateLoginTime($_SESSION['siteusername'], $conn); 
}
?>