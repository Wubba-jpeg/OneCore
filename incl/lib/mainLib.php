<?php
class mainLib {
public function userID($udid, $username) {
    include "connection.php";
    
    $query = $db->prepare("SELECT * FROM users WHERE udid = '$udid'");
    $query->execute();
    $result = $query->fetch();
    
    if ($result === false) {
        // new user if they don't exist
        $query = $db->prepare("INSERT INTO users (udid, userName) VALUES ('$udid', '$username')");
        $query->execute();
        return $db->lastInsertId();
    } else {
        // update username if it is changed
        if ($result['userName'] != $username) {
            $query = $db->prepare("UPDATE users SET userName = '$username' WHERE udid = '$udid'");
            $query->execute();
        }
        return $result["userId"];
    }
}
}
