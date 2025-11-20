<?php
$session_id = $_SESSION['user_id'];
$stmt = $conn->prepare('select sum(amount) as total_expense from expenses where user_id = :id');
$stmt->bindParam(":id", $session_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$total_ex = $user['total_expense'] ?? 0;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"> <span class="navbar-text text-white me-3">
                Welcome <?php echo $_SESSION['user_name']; ?>
            </span></a>
        <span class="navbar-text text-white me-3">
            Total Expense : <?php echo $total_ex; ?>
        </span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            </ul>

            <a href="../auth/logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>