<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM expenses WHERE user_id = :user_id ORDER BY date DESC");
    $stmt->bindParam("user_id", $user_id);
    $stmt->execute();
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Something went wrong';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable</title>
</head>

<body>
    <a href="add.php" class="btn btn-primary mb-4">Add</a>
    <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th class="text-left">Sr No</th>
                <th class="text-left">Title</th>
                <th class="text-left">Amount</th>
                <th class="text-left">Date</th>
                <th class="text-left">Category</th>
                <th class="text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $index = 1;
            foreach ($expenses as $value): ?>
                <tr>
                    <td class="text-left"><?php echo  $index++ ?></td>
                    <td class="text-left"><?php echo $value['title'] ?></td>
                    <td class="text-left"><?php echo $value['amount'] ?></td>
                    <td class="text-left"><?php echo $value['date'] ?></td>
                    <td class="text-left"><?php echo $value['category'] ?></td>
                    <td class="text-left">
                        <a href="edit.php?id=<?php echo $value['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $value['id'] ?>"
                            class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>