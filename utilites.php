<?php
//Handling the Input From Multiple Positions
function validatePos()
{
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        if (strlen($year) == 0 || strlen($desc) == 0) {
            return "All fields are required";
        }

        if (!is_numeric($year)) {
            return "Position year must be numeric";
        }
    }
    return true;
}

// Handle form submission
function addProfile($pdo)
{

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
}

//Checking if logged in
function checkLoggedin()
{
    if (!isset($_SESSION["user_id"])) {
        echo "<p>ACCESS DENIED</p>";
        return;
    }
}

//Flash message
function errorMessage()
{
    if (isset($_SESSION["error"])) {
        echo '<p>' . $_SESSION["error"] . '</p>';
        unset($_SESSION["error"]);
    }
}

// find school id based on string entered 
function findSchoolId($school, $pdo)
{
    $stmt = $pdo->prepare('SELECT institution_id FROM Institution WHERE name LIKE :school');
    $stmt->execute(array(':school' => $school));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return $row['institution_id'];
    } else {
        return null;
    }
}

function findSchoolName($institution_id, $pdo)
{
    header('Content-type: application/json');
    $stmt = $pdo->prepare('SELECT name FROM Institution WHERE institution_id LIKE :institution_id');
    $stmt->execute(array(':institution_id' => $institution_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $retval[] = $row['name'];
        echo (json_encode($retval, JSON_PRETTY_PRINT));
    } else {
        return null;
    }

    exit;
}

// Find or create school ID based on the institution name
function findOrCreateSchoolId($school, $pdo)
{
    // Try to find the institution in the database
    $stmt = $pdo->prepare('SELECT institution_id FROM institution WHERE name = :name');
    $stmt->execute(array(':name' => $school));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row['institution_id'];
    } else {
        // Institution not found, insert it
        $stmt = $pdo->prepare('INSERT INTO institution (name) VALUES (:name)');
        $stmt->execute(array(':name' => $school));
        return $pdo->lastInsertId();
    }
}
