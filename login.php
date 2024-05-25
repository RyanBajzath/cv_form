<?php
session_start(); // Start the session at the beginning of the script
require 'pdo.php'; // Ensure your database connection file is properly included
$salt = 'XyZzy12*_';



if (isset($_SESSION["error"])) {
    echo '<p>' . $_SESSION["error"] . '</p>';
    unset($_SESSION["error"]);
}

if (isset($_POST["pass"]) && isset($_POST["email"])) {
    $check = hash('md5', $salt . $_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        // Redirect the browser to index.php
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Incorrect password"; // Note the typo correction from 'errror' to 'error'
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ryan Bajzath login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <h1>Please Log In</h1>
        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="text" name="email" id="email"><br />
            <label for="id_1723">Password</label>
            <input type="password" name="pass" id="id_1723"><br />
            <input type="submit" onclick="return doValidate();" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>
        <p>
            For a password hint, view source and find an account and password hint in the HTML comments.
            <!-- Hint: 
                The account is umsi@umich.edu
                The password is the three character name of the programming language used in this class (all lower case) 
                followed by 123. -->
        </p>
        <script>
            function doValidate() {
                console.log('Validating...');
                try {
                    var addr = document.getElementById('email').value;
                    var pw = document.getElementById('id_1723').value;
                    console.log("Validating addr=" + addr + " pw=" + pw);
                    if (addr == null || addr == "" || pw == null || pw == "") {
                        alert("Both fields must be filled out");
                        return false;
                    }
                    if (addr.indexOf('@') == -1) {
                        alert("Invalid email address");
                        return false;
                    }
                    return true;
                } catch (e) {
                    return false;
                }
            }
        </script>
    </div>
</body>

</html>