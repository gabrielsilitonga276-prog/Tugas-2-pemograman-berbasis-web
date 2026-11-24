<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Tambah Tugas
if (isset($_POST['add_task'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $stmt = $pdo->prepare("INSERT INTO todos (user_id, task, status) VALUES (?, ?, 'Belum Selesai')");
        $stmt->execute([$user_id, $task]);
    }
    header("Location: index.php");
    exit;
}

// Ubah status jadi Selesai
if (isset($_GET['done'])) {
    $id = $_GET['done'];
    $stmt = $pdo->prepare("UPDATE todos SET status = 'Selesai' WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    header("Location: index.php");
    exit;
}

// Hapus Tugas
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user_id]);
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$todos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="app-header">
        <div class="profile-info">
            <h2>Gabriel Hagabean Silitonga</h2>
            <p>NIM: 255314159</p>
        </div>
        <div class="profile-avatar">
            <img src="foto.jpg" alt="Foto" onerror="this.src='DIRIKU.jpeg'">
        </div>
        <div class="logout-container">
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <div class="container">
        <h1>To-Do List Manager</h1>

        <form action="" method="POST" class="task-form">
            <input type="text" name="task" placeholder="<Teks to do>" required autocomplete="off">
            <button type="submit" name="add_task" class="btn-add">Tambah</button>
        </form>

        <div class="table-responsive">
            <table class="task-table">
                <?php if (count($todos) > 0): ?>
                    <?php foreach ($todos as $row): ?>
                    <tr>
                        <td class="task-text <?= ($row['status'] == 'Selesai') ? 'completed' : ''; ?>">
                            <?= htmlspecialchars($row['task']); ?>
                            <?php if($row['status'] == 'Selesai'): ?>
                                <span class="badge-done">(Selesai)</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-cell" colspan="2">
                            <?php if ($row['status'] != 'Selesai'): ?>
                                <a href="index.php?done=<?= $row['id']; ?>" class="btn-action btn-selesai">Selesai</a>
                            <?php else: ?>
                                <span class="text-muted" style="color: #27ae60; font-weight: bold; margin-right: 5px;">Selesai</span>
                            <?php endif; ?>
                            <a href="index.php?delete=<?= $row['id']; ?>" class="btn-action btn-hapus" onclick="return confirm('Hapus tugas ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="empty-task">Belum ada tugas. Silakan tambahkan!</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>