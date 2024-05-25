<?php
session_start();
require 'pdo.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ryan Bajzath index</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
</head>

<body>
    <h1>registration page</h1>
    <?php

    //Login option to log out if user is not logged in.
    if (!isset($_SESSION["user_id"])) {
        echo '<a href="login.php"> Please log in</a>';
        exit;
    }

    //Check for error messages
    if (isset($_SESSION["error"])) {
        echo '<p>' . $_SESSION["error"] . '</p>';
        unset($_SESSION["error"]);
    }

    //Check for success messages
    if (isset($_SESSION["success"])) {
        echo '<p>' . $_SESSION["success"] . '</p>';
        unset($_SESSION["sucess"]);
    }

    //Accessing and rendering profile rows with the ability to add, edit and delete
    $stmt = $pdo->query("SELECT first_name, last_name, email, headline, profile_id, summary FROM profile");

    echo '<table border="1">';
    echo "<tr><td>";
    echo "Name";
    echo "</td><td>";
    echo "Header";
    echo "</td>
    <td>";
    echo "Action";
    echo "</td></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>";
        echo '<a href="view.php?profile_id=' . urlencode(htmlspecialchars($row['profile_id'])) . '">' . htmlspecialchars($row['first_name']) . htmlspecialchars($row['last_name']) . '</a>';


        echo "</td><td>";
        echo htmlspecialchars($row['headline']);
        echo "</td><td>";
        echo '<a href="edit.php?profile_id=' . urlencode(htmlspecialchars($row['profile_id'])) . '"> edit  </a>';
        echo '<a href="delete.php?profile_id=' . urlencode(htmlspecialchars($row['profile_id'])) . '"> delete  </a>';

        echo "</td></tr>";
    }
    echo "</table>";
    if (!isset($_SESSION["user_id"])) {
        echo '<a href="login.php">Login</a>';
    } else {
        echo '<a href="add.php">Add New Entry</a>';
        echo '<a href="logout.php">Logout</a>';
    }
    ?>
</body>

</html>