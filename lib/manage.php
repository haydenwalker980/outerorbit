<?php
function updateUserBio($username, $bio, $connection) {
        $stmt = $connection->prepare("UPDATE users SET bio = ? WHERE username = ?");
        $stmt->bind_param("ss", $bio, $username);
        $stmt->execute();
	$stmt->close();

	return true;
}

function updateUserCSS($username, $css, $connection) {
        $stmt = $connection->prepare("UPDATE users SET css = ? WHERE username = ?");
        $stmt->bind_param("ss", $css, $username);
        $stmt->execute();
    $stmt->close();

    return true;
}

function updateUserGender($username, $gender, $connection) {
        $stmt = $connection->prepare("UPDATE users SET gender = ? WHERE username = ?");
        $stmt->bind_param("ss", $gender, $username);
        $stmt->execute();
    $stmt->close();

    return true;
}

function updateUserSong($username, $gender, $connection) {
    $stmt = $connection->prepare("UPDATE users SET song = ? WHERE username = ?");
    $stmt->bind_param("ss", $gender, $username);
    $stmt->execute();
$stmt->close();

return true;
}

function updateUserAge($username, $age, $connection) {
        $stmt = $connection->prepare("UPDATE users SET age = ? WHERE username = ?");
        $stmt->bind_param("ss", $age, $username);
        $stmt->execute();
    $stmt->close();

    return true;
}

function updateUserLocation($username, $location, $connection) {
        $stmt = $connection->prepare("UPDATE users SET location = ? WHERE username = ?");
        $stmt->bind_param("ss", $location, $username);
        $stmt->execute();
    $stmt->close();

    return true;
}

function updateUserInterest($username, $interests, $connection) {
        $stmt = $connection->prepare("UPDATE users SET interests = ? WHERE username = ?");
        $stmt->bind_param("ss", $interests, $username);
        $stmt->execute();
    $stmt->close();

    return true;
}

function updateUserInterestMusic($username, $interests, $connection) {
        $stmt = $connection->prepare("UPDATE users SET interestsmusic = ? WHERE username = ?");
        $stmt->bind_param("ss", $interests, $username);
        $stmt->execute();
    $stmt->close();

    return true;
}
?>