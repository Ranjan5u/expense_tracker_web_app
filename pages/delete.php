<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$expense_id = $_GET['id'] ?? null;
if (!$expense_id) {
    header("Location:../pages/dashboard.php");
    exit();
}
$stmt = $conn->prepare("DELETE FROM expenses WHERE id = :id AND user_id = :user_id");
$stmt->bindParam(':id', $expense_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

if ($stmt->execute()) {
    $_SESSION['msg'] = "Expense deleted successfully!";
} else {
    $_SESSION['msg'] = "Something went wrong!";
}
header("Location:../pages/dashboard.php");
exit();
