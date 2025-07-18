<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'] === 'admin' ? 'admin' : 'user';

    $door = $_POST['door'] ?? '';
    $street = $_POST['street'] ?? '';
    $area = $_POST['area'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = "$door, $street, $area, $city, $state";

    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check->num_rows > 0) {
        $error = "Username already taken.";
    } else {
        if ($role === 'user') {
            $conn->query("INSERT INTO users (username, email, password, role, address, phone) 
                          VALUES ('$username', '$email', '$password', 'user', '$address', '$phone')");
        } else {
            $conn->query("INSERT INTO users (username, email, password, role) 
                          VALUES ('$username', '$email', '$password', 'admin')");
        }
        $_SESSION['success'] = "Registration successful! Please login.";
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - Bookstore</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <?php if (!empty($error)) echo '<p class="error">'.$error.'</p>'; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        
        <select name="role" id="role" required onchange="toggleUserFields(this.value)">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <div id="userFields">
            <h3>Address Details</h3>
            <input type="text" name="door" placeholder="Door No" />
            <input type="text" name="street" placeholder="Street" />
            <input type="text" name="area" placeholder="Area" />

            <select name="state" id="state" onchange="filterCities()">
                <option value="">Select State</option>
            </select>

            <select name="city" id="city">
                <option value="">Select City</option>
            </select>

            <input type="text" name="phone" placeholder="Phone Number" />
        </div>

        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="login.php">Login here</a></p>
</div>

<script>
const indianStates = {
    "Andhra Pradesh": ["Visakhapatnam", "Vijayawada", "Guntur"],
    "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru"],
    "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai"],
    "Maharashtra": ["Mumbai", "Pune", "Nagpur"],
    "Delhi": ["New Delhi", "Dwarka", "Rohini"],
    "West Bengal": ["Kolkata", "Asansol", "Siliguri"],
    "Telangana": ["Hyderabad", "Warangal", "Nizamabad"]
};

function populateStates() {
    const stateSelect = document.getElementById('state');
    for (let state in indianStates) {
        stateSelect.innerHTML += `<option value="${state}">${state}</option>`;
    }
}

function filterCities() {
    const state = document.getElementById('state').value;
    const citySelect = document.getElementById('city');
    citySelect.innerHTML = '<option value="">Select City</option>';
    if (state && indianStates[state]) {
        indianStates[state].forEach(city => {
            citySelect.innerHTML += `<option value="${city}">${city}</option>`;
        });
    }
}

function toggleUserFields(role) {
    const userFields = document.getElementById('userFields');
    const inputs = userFields.querySelectorAll('input, select');

    if (role === 'user') {
        userFields.style.display = 'block';
        inputs.forEach(input => input.required = true);
    } else {
        userFields.style.display = 'none';
        inputs.forEach(input => input.required = false);
    }
}

window.onload = () => {
    populateStates();
    toggleUserFields(document.getElementById('role').value);
};
</script>
</body>
</html>
