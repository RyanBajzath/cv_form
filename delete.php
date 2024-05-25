<?php
//Check if logged in, then check for GET to fill inputs with their values then post the data with an update :) Not soo bd. You got this!
session_start();
require "pdo.php";

if (!isset($_SESSION["user_id"])) {
    echo "<p>ACCESS DENIED</p>";
    return;
}

if (isset($_POST["delete"])) {
    //create sql string
    $sql = "DELETE from profile WHERE profile_id = :profile_id";
    //preprare pdo
    $stmt = $pdo->prepare($sql);
    // Bind the input parameter
    $stmt->bindParam(':profile_id', $_GET['profile_id']);
    // Execute the statement
    $stmt->execute();
    $_SESSION["success"] = "Record Deleted" .  $_GET['profile_id'];
    header("Location:index.php");
}

//Refrence GET param to check for delete info with pdo
$sql = "SELECT * from profile WHERE :profile_id = :profile_id";
$stmt = $pdo->prepare($sql);
// Bind the input parameter
$stmt->bindParam(':profile_id', $_GET['profile_id']);
// Execute the statement
$stmt->execute();
// Fetch the data
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ryan Bajzath Delete</title>
</head>

<body>
    <form method="post">
        <?php if (isset($profile) && $profile) : ?>
            <p><?php echo htmlspecialchars($profile['first_name'] ?? 'N/A'); ?></p>
            <p><?php echo htmlspecialchars($profile['last_name'] ?? 'N/A'); ?></p>
            <input type="submit" name="delete" value="Delete">
        <?php else : ?>
            <p>No profile found.</p>
        <?php endif; ?>
    </form>
</body>

</html>