<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$msg = "";
$expense_id = $_GET['id'] ?? null;
if (!$expense_id) {
    header("Location: ../pages/dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM expenses WHERE id = :id AND user_id = :user_id");
$stmt->bindParam(':id', $expense_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();

$expense = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$expense) {
    $msg = "Expense not found or you don't have permission.";
}

if (isset($_POST['edit'])) {
    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("UPDATE expenses SET title = :title, amount = :amount, date = :date, category = :category WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':id', $expense_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    if ($stmt->execute()) {
        $msg = "Expense updated successfully!";
        $expense['title'] = $title;
        $expense['amount'] = $amount;
        $expense['date'] = $date;
        $expense['category'] = $category;
    } else {
        $msg = "Something Went wrong";
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
    <?php include '../layout/navbar.php' ?>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-3" style="width: 400px;">
            <div class="card-header text-center bg-secondary text-white">
                <h3>Edit</h3>
            </div>
            <div class="card-body">
                <?php if ($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title"
                            value="<?php echo htmlspecialchars($expense['title']); ?>"
                            placeholder="Enter your title" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" id="amount"
                            value="<?php echo htmlspecialchars($expense['amount']); ?>"
                            placeholder="Enter your amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" id="date"
                            value="<?php echo htmlspecialchars($expense['date']); ?>"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" id="category"
                            value="<?php echo htmlspecialchars($expense['category']); ?>"
                            placeholder="Enter category" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="edit" class="btn btn-primary">Update</button>
                        <a href="dashboard.php" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>