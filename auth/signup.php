<?php
session_start();
include '../config/db.php';
$msg = "";

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("select id from users where email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $msg = "Email Already Registered";
    } else {
        $stmt = $conn->prepare("insert into users(name,email,password) values(:name,:email,:password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        if ($stmt->execute()) {
            $msg = "Registration Successfull";
        } else {
            $msg = "Something Went wrong";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/register.css" rel="stylesheet" />
</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-3" style="width: 400px;">
            <div class="card-header text-center bg-secondary text-white">
                <h3>User Registration</h3>
            </div>
            <div class="card-body">
                <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>

                <form method="post" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                    <p class="mt-3 text-center">
                        Already have an account? <a href="login.php">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>