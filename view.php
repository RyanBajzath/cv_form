<?php
session_start();
require "pdo.php";

// Check if logged in
if (!isset($_SESSION["user_id"])) {
    echo "<p>ACCESS DENIED</p>";
    return;
}

// Check for error messages
if (isset($_SESSION["error"])) {
    echo '<p>' . $_SESSION["error"] . '</p>';
    unset($_SESSION["error"]);
}

// Check if profile ID is present
if (!isset($_GET['profile_id']) || empty($_GET['profile_id'])) {
    echo "<p>No profile ID specified.</p>";
    exit;
}

$profile_id = $_GET['profile_id'];  // Store profile ID from GET for later use

// Retrieve profile data
$sql = "SELECT * from profile WHERE profile_id = :profile_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':profile_id', $profile_id);
$stmt->execute();
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    echo "<p>No profile found</p>";
    exit;  // If no profile, stop further execution
}

// Retrieve positions data
$sql = "SELECT * from position WHERE profile_id = :profile_id ORDER BY rank";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':profile_id', $profile_id);
$stmt->execute();
$positions = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $positions[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ryan Bajzath Update</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
</head>

<body>
    <script>
        let countPos = <?php echo count($positions); ?>; // Initialize countPos with the number of positions retrieved from the database
        const positions = <?php echo json_encode($positions); ?>; // Pass the positions data to JavaScript

        $(document).ready(function() {
            window.console && console.log("document ready called");

            // Reconstruct positions from the database
            positions.forEach((position, index) => {
                $('#position_fields').append(
                    '<div id="position' + (index + 1) + '"> \
                        <p>Year: <input type="text" name="year' + (index + 1) + '" value="' + position.year + '" /> \
                        <input type="button" value="-" \
                            onclick="$(\'#position' + (index + 1) + '\').remove();return false;"></p> \
                        <textarea name="desc' + (index + 1) + '" rows="8" cols="80">' + position.description + '</textarea>\
                    </div>'
                );
            });
            // Add click even to add new position inputs
            $('#addPos').click(function(event) {
                event.preventDefault();
                if (countPos >= 9) {
                    alert("Maximum of nine positions, entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("adding position" + countPos);
                $('#position_fields').append(
                    '<div id="position' + countPos + '"> \
                        <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
                        <input type="button" value="-" \
                            onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
                        <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
                    </div>'
                );
            });
        });
    </script>
    <form method="POST">
        <p>
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($profile['first_name']); ?>" />
        </p>
        <p>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($profile['last_name']); ?>" />
        </p>
        <p>
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($profile['email']); ?>" />
        </p>
        <p>
            <label for="headline">Headline</label>
            <input type="text" name="headline" id="headline" value="<?php echo htmlspecialchars($profile['headline']); ?>" />
        </p>
        <p>
            <label for="summary">Summary</label>
            <input type="text" name="summary" id="summary" value="<?php echo htmlspecialchars($profile['summary']); ?>" />
        </p>
        <p>
            <input type="button" id="addPos" class="b1" value="+">
        <div id="position_fields"></div>
        </p>
        <p>
            <input type="submit" name="edit" value="Save" />
            <a href="index.php">Cancel</a>
        </p>
    </form>
</body>

</html>