<?php
session_start();
include '../config/db.php';

$msg = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("select id,name,password from users where email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id'];
        $name = $row['name'];
        $hashed_pass = $row['password'];
        if (password_verify($password, $hashed_pass)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            header("Location:../pages/dashboard.php");
            exit();
        } else {
            $msg = "invalid password";
        }
    } else {
        $msg = "email not registered";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/login.css" rel="stylesheet" />
</head>

<body>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-3" style="width: 400px;">
            <div class="card-header text-center bg-secondary text-white">
                <h3>User Login</h3>
            </div>
            <div class="card-body">
                <?php if ($msg) echo "<div class='alert alert-danger'>$msg</div>"; ?>

                <form method="post" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    <p class="mt-3 text-center">
                        Don't have an account? <a href="signup.php">Register here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>