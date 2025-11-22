<?php
session_start();
require_once '../db_con.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare('DELETE FROM user_requests WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: user_requests.php');
    exit();
}

// Fetch all user requests
$result = $conn->query('SELECT ur.*, u.username FROM user_requests ur JOIN users u ON ur.user_id = u.id ORDER BY ur.created_at DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Requests - Admin</title>
    <link rel="stylesheet" href="../styles/main-style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .actions a { margin-right: 8px; }
    </style>
</head>
<body>
    <h2>User Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Name</th>
            <th>Chair</th>
            <th>Quantity</th>
            <th>Location</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Date</th>
            <th>Notes</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['chair']); ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
            <td><?php echo htmlspecialchars($row['mobile']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['date']); ?></td>
            <td><?php echo htmlspecialchars($row['notes']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td class="actions">
                <a href="edit_user_request.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a href="user_requests.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this request?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
