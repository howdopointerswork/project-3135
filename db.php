<?php

function addUser($db, $username, $password) {
    $qry = "INSERT INTO user (username, password) VALUES (:username, :password)";
    $stmnt = $db->prepare($qry);
    $stmnt->bindValue(':username', $username);
    $stmnt->bindValue(':password', $password);
    $stmnt->execute();
}

function authenticate($db, $username, $password) {
    $qry = "SELECT * FROM user WHERE username = :username;";
    $stmnt = $db->prepare($qry);
    $stmnt->bindValue(':username', $username);
    $stmnt->execute();
    $result = $stmnt->fetch();

    if ($result && $result[2] == $password) {
        $result[] = true;
        return $result;
    } else {
        $result[] = false;
        return $result;
    }
}

function addActivity($db, $values) {
    $qry = "INSERT INTO logging (calories, sleep, water, exercise, meds, userid) 
            VALUES(:val0, :val1, :val2, :val3, :val4, :val5)";

    $stmnt = $db->prepare($qry);
    $stmnt->bindValue(":val0", (int)$values[0]);
    $stmnt->bindValue(":val1", $values[1]);
    $stmnt->bindValue(":val2", $values[2]);
    $stmnt->bindValue(":val3", $values[3]);
    $stmnt->bindValue(":val4", ($values[4] == "on") ? 1 : 0);
    $stmnt->bindValue(":val5", $values[5]);
    $stmnt->execute();
}

function getActivities($db, $id) {
    $qry = "SELECT * FROM logging WHERE userid = :id;";
    $stmnt = $db->prepare($qry);
    $stmnt->bindValue(":id", $id);
    $stmnt->execute();
    return $stmnt->fetchAll();
}

function deleteActivity($db, $id) {
    $qry = 'DELETE FROM logging WHERE id = :id';
    $stmnt = $db->prepare($qry);
    $stmnt->bindValue(':id', $id);
    $stmnt->execute();
}

function getActivity($db, $id) {
    $qry = 'SELECT * FROM logging WHERE id = :id';
    $stmnt = $db->prepare($qry);
    $stmnt->bindValue(':id', $id);
    $stmnt->execute();
    return $stmnt->fetch();
}

function updateActivity($db, $data, $id) {
    $qry = 'UPDATE logging SET 
            calories = :calories,
            sleep = :sleep,
            water = :water,
            exercise = :exercise,
            meds = :meds
            WHERE id = :id';

    $stmnt = $db->prepare($qry);
    $stmnt->bindValue(':calories', $data[0]);
    $stmnt->bindValue(':sleep', $data[1]);
    $stmnt->bindValue(':water', $data[2]);
    $stmnt->bindValue(':exercise', $data[3]);
    $stmnt->bindValue(':meds', $data[4]);
    $stmnt->bindValue(':id', $id);
    $stmnt->execute();
}

function updateProfile($db, $id, $vals) {
    $qry = 'UPDATE user SET
            username = :username,	
            age = :age,
            height = :height,
            weight = :weight,
            gender = :gender
            WHERE user_id = :id';

    $stmnt = $db->prepare($qry);
    $stmnt->bindValue(':username', $vals[0]);
    $stmnt->bindValue(':age', $vals[1]);
    $stmnt->bindValue(':height', (float)$vals[2]);
    $stmnt->bindValue(':weight', (float)$vals[3]);
    $stmnt->bindValue(':gender', $vals[4]);
    $stmnt->bindValue(':id', $id);
    $stmnt->execute();
}

function getBookings($db, $userId) {
    $qry = "SELECT * FROM booking WHERE userid = :userid ORDER BY booking_date ASC";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addBooking($db, $userId, $date, $description) {
    $qry = "INSERT INTO booking (userid, booking_date, description) 
            VALUES (:userid, :date, :desc)";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':desc', $description);
    $stmt->execute();
}

function deleteBooking($db, $bookingId) {
    $qry = "DELETE FROM booking WHERE id = :id";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
}

function getBooking($db, $bookingId) {
    $qry = "SELECT * FROM booking WHERE id = :id";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateBooking($db, $bookingId, $date, $description) {
    $qry = "UPDATE booking SET booking_date = :date, description = :desc WHERE id = :id";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':desc', $description);
    $stmt->bindValue(':id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
}

?>