<?php
session_start();
require 'pdo.php';
require 'utilites.php';

// Check if logged in and errors
checkLoggedin();
errorMessage();

// Handle form submission
if (isset($_POST['add'])) {
    // Check if all required fields are present and not empty
    if (
        isset($_POST["first_name"], $_POST["last_name"], $_POST["email"], $_POST["headline"], $_POST["summary"]) &&
        !empty(trim($_POST["first_name"])) &&
        !empty(trim($_POST["last_name"])) &&
        !empty(trim($_POST["email"])) &&
        !empty(trim($_POST["headline"])) &&
        !empty(trim($_POST["summary"]))
    ) {

        $stm = "INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)";
        $stmt = $pdo->prepare($stm);
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],  // Use the session's user ID
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary']
        ));

        // After adding the profile, grab its id then use it as a foreign key to connect it with the positions
        $profile_id = $pdo->lastInsertId();

        // Insert positions into the Position table
        $rank = 1; // Start rank from 1
        for ($i = 1; $i <= 9; $i++) { // Loop to handle dynamic position fields
            if (!isset($_POST['year' . $i])) continue;
            if (!isset($_POST['desc' . $i])) continue;
            $year = $_POST['year' . $i];
            $desc = $_POST['desc' . $i];
            if (strlen($year) == 0 || strlen($desc) == 0) {
                $_SESSION["error"] = "All fields are required and must not be empty.";
                header("Location: add.php");
                return;
            }
            if (!is_numeric($year)) {
                $_SESSION["error"] = "Position year must be numeric.";
                header("Location: add.php");
                return;
            }
            $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES (:pid, :rank, :year, :desc)');
            $stmt->execute(array(
                ':pid' => $profile_id, // Use the retrieved profile_id
                ':rank' => $rank, // Use the current rank
                ':year' => $year,
                ':desc' => $desc
            ));
            $rank++; // Increment rank for the next position
        }

        // Insert education into education table
        $eduCounter = 1; // Start rank from 1
        for ($i = 1; $i <= 9; $i++) {
            if (!isset($_POST['edu_school' . $i])) continue;
            if (!isset($_POST['edu_year' . $i])) continue;
            $school = $_POST['edu_school' . $i];
            $year = $_POST['edu_year' . $i];
            if (strlen($year) == 0 || strlen($school) == 0) {
                $_SESSION["error"] = "All fields are required and must not be empty.";
                header("Location: add.php");
                return;
            }
            if (!is_numeric($year)) {
                $_SESSION["error"] = "Education year must be numeric.";
                header("Location: add.php");
                return;
            }
            $schoolId = findOrCreateSchoolId($school, $pdo);
            $stmt = $pdo->prepare('INSERT INTO Education (profile_id, rank, year, institution_id) VALUES (:pid, :rank, :year, :institution_id)');
            $stmt->execute(array(
                ':pid' => $profile_id, // Use the retrieved profile_id
                ':rank' => $eduCounter, // Use the current rank
                ':year' => $year,
                ':institution_id' => $schoolId
            ));
            $eduCounter++; // Increment rank for the next education
        }

        // Optionally, redirect or display a success message
        $_SESSION["success"] = "Profile added successfully.";
        header("Location: index.php");
        exit;
    } else {
        // If any field is empty, set an error message and redirect back to the form
        $_SESSION["error"] = "All fields are required and must not be empty.";
        header("Location: add.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ryan Bajzath add</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
</head>

<body>
    <script>
        countPos = 0;
        countEdu = 0;

        // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
        $(document).ready(function() {
            window.console && console.log('Document ready called');

            $('#addPos').click(function(event) {
                // http://api.jquery.com/event.preventdefault/
                event.preventDefault();
                if (countPos >= 9) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position " + countPos);
                $('#position_fields').append(
                    '<div id="position' + countPos + '"> \
                        <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
                        <input type="button" value="-" onclick="$(\'#position' + countPos + '\').remove();return false;"><br>\
                        <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
                    </div>');
            });

            $('#addEdu').click(function(event) {
                event.preventDefault();
                if (countEdu >= 9) {
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console && console.log("Adding education " + countEdu);

                $('#edu_fields').append(
                    '<div id="edu' + countEdu + '"> \
                        <p>Year: <input type="text" name="edu_year' + countEdu + '" value="" /> \
                        <input type="button" value="-" onclick="$(\'#edu' + countEdu + '\').remove();return false;"><br>\
                        <p>School: <input type="text" size="80" name="edu_school' + countEdu + '" class="school" value="" />\
                    </div>'
                );

                // Apply autocomplete to the new school field
                $('#edu' + countEdu + ' .school').autocomplete({
                    source: "school.php"
                });

                window.console && console.log("Autocomplete applied to new school field");
            });
        });
    </script>
    <form method="POST">
        <p>
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" />
        </p>
        <p>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" />
        </p>
        <p>
            <label for="email">Email</label>
            <input type="text" name="email" id="email" />
        </p>
        <p>
            <label for="headline">Headline</label>
            <input type="text" name="headline" id="headline" />
        </p>
        <p>
            <label for="summary">Summary</label>
            <input type="text" name="summary" id="summary" />
        </p>
        <p>
            Education: <input type="button" id="addEdu" value="+">
        </p>
        <div id="edu_fields"></div>
        <p>
            Position: <input type="button" id="addPos" value="+">
        </p>
        <div id="position_fields"></div>
        <p>
            <input type="submit" name="add" value="Add Record" />
            <input type="submit" name="logout" value="Logout" />
        </p>
    </form>
</body>

</html>