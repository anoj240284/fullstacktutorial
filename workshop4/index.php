<?php
// Initialize variables
$name = $email = "";
$errors = [];
$success = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Collect form data safely
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // 2. VALIDATION -----------------------------
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

   

    if ($confirm_password !== $password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    // If no errors, proceed to save user
    if (empty($errors)) {

        // 3. Read users.json
        $jsonFile = "users.json";

        if (!file_exists($jsonFile)) {
            // Create file if not found
            file_put_contents($jsonFile, "[]");
        }

        $jsonData = file_get_contents($jsonFile);

        if ($jsonData === false) {
            die("Error reading users.json file.");
        }

        $users = json_decode($jsonData, true);

        if (!is_array($users)) {
            $users = [];
        }


        // 5. Create new user array
        $newUser = [
            "name" => $name,
            "email" => $email,
            "password" => $password
        ];

        // 6. Add user to array
        $users[] = $newUser;

        // 7. Write back to JSON
        $writeStatus = file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT));

        if ($writeStatus === false) {
            die("Error writing to users.json file.");
        }

        // Success
        $success = "Registration successful!";
        $name = $email = ""; // Clear fields
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
    </style>
</head>
<body>

<h2 style="text-align:center;">Registration Form</h2>

<?php if ($success): ?>
    <div class="success"><?= $success ?></div>
<?php endif; ?>

<form method="POST" action="">

    <!-- Name -->
    <label>Name:</label>
    <input type="text" name="name" value="">
    <div class="error"><?= $errors['name'] ?? "" ?></div>

    <!-- Email -->
    <label>Email:</label>
    <input type="email" name="email" value="">
    <div class="error"><?= $errors['email'] ?? "" ?></div>

    <!-- Password -->
    <label>Password:</label>
    <input type="password" name="password">
    <div class="error"><?= $errors['password'] ?? "" ?></div>

    <!-- Confirm Password -->
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password">
    <div class="error"><?= $errors['confirm_password'] ?? "" ?></div>

    <br><br>
    <button type="submit">Register</button>
</form>

</body>
</html>