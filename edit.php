<?php
session_start();
require "pdo.php";
require "utilites.php";

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




//retrive the education data
$sql = "SELECT * from education WHERE profile_id = :profile_id ORDER BY rank";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':profile_id', $profile_id);
$stmt->execute();
$educations = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $educations[] = $row;
}

//retrieve instituion data 
$sql = "SELECT * FROM `institution` WHERE 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$institutions = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $institutions[] = $row;
}

//Joining tables based on profile_id


$sql = "SELECT i.name AS institution_name FROM education AS e JOIN institution AS i ON e.institution_id = i.institution_id WHERE e.profile_id = :profile_id;";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':profile_id', $profile_id);
$stmt->execute();
$institutions = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Name: " . $row['institution_name'] . "<br>";
    $joinedEducations[] = $row;
}







// Handle form submission
if (isset($_POST['edit'])) {
    //Delete old positions

    //create sql string
    $sql = "DELETE from position WHERE profile_id = :profile_id";
    //preprare pdo
    $stmt = $pdo->prepare($sql);
    // Bind the input parameter
    $stmt->bindParam(':profile_id', $_GET['profile_id']);
    // Execute the statement
    $stmt->execute();


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
        // Reconstruct educations from the database
        // Add click even to add new position inputs
    </script>
    <script>
        let countEdu = <?php echo count($educations); ?>; // Initialize countEdu with the number of education entries retrieved from the database
        const educations = <?php echo json_encode($educations); ?>; // Pass the education data to JavaScript
        const institutions = <?php echo json_encode($institutions); ?>; // Pass the education data to JavaScript
        console.log(institutions);

        const joinedEducations = <?php echo json_encode($joinedEducations); ?>; // Pass the education data to JavaScript
        console.log(joinedEducations)


        $(document).ready(function() {
            window.console && console.log("document ready called");

            // Populate existing education fields
            educations.forEach((education, index) => {
                console.log(education)





                $("#edu_fields").append(
                    '<div id="edu' + (index + 1) + '"> \
                    <p>Year: <input type="text" name="edu_year' + (index + 1) + '" value="' + education.year + '" /> \
                    <input type="button" value="-" \
                        onclick="$(\'#education' + (index + 1) + '\').remove();return false;"></p> \
                    <p>School: <input type="text" size="80" name="edu_school' + (index + 1) + '" class="school" value="' + joinedEducations[index].institution_name + '" />\
                </div>'
                );

                // Apply autocomplete to the new school field
                $('#edu_fields .school').autocomplete({
                    source: "school.php"
                });
            });

            // Add new education fields
            $('#addEdu').click(function(event) {
                event.preventDefault();
                if (countEdu >= 9) {
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console && console.log("Adding education " + countEdu);
                $('#edu_fields').append(
                    '<div id="education' + countEdu + '"> \
                    <p>Year: <input type="text" name="edu_year' + countEdu + '" value="" /> \
                    <input type="button" value="-" onclick="$(\'#education' + countEdu + '\').remove();return false;"><br>\
                    <p>School: <input type="text" size="80" name="edu_school' + countEdu + '" class="school" value="" />\
                </div>'
                );
                // Apply autocomplete to the new school field
                $('#edu_fields .school').autocomplete({
                    source: "school.php"
                });

                window.console && console.log("Autocomplete applied to new school field");
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
            Education: <input type="button" id="addEdu" value="+">
        </p>
        <div id="edu_fields">

        </div>
        <p>
            <input type="button" id="addPos" class="b1" value="+">
        <div id="position_fields"></div>
        </p>
        <p>
            <input type="submit" name="edit" value="Save" />
            <a href="index.php">Cancel</a>
        </p>
    </form>

</html>