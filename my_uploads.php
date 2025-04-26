<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; // "admin" or "student"

$sql = ($role === 'admin') ?
    "SELECT * FROM uploads" :
    "SELECT * FROM uploads WHERE uploader_id = ?";

$stmt = $conn->prepare($sql);

if ($role !== 'admin') {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Uploads - VidyaSathi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            margin: 20px;
        }

        header h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        nav {
            background-color: #333;
            padding: 10px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .nav-left h1 {
            color: white;
            display: inline-block;
        }

        .nav-right {
            float: right;
        }

        .nav-right a {
            color: white;
            text-decoration: none;
            padding: 10px;
        }

        .nav-right a:hover {
            background-color: #ddd;
            color: black;
        }

        body {
            padding-top: 60px;
            font-family: Arial, sans-serif;
        }

        .btn-approve,
        .btn-reject {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            color: white;
        }

        .btn-approve {
            background-color: #4CAF50;
        }

        .btn-reject {
            background-color: #f44336;
        }

        .btn-approve:hover,
        .btn-reject:hover {
            opacity: 0.8;
        }

        .message {
            background-color: #e7f4e4;
            color: #2d702d;
            padding: 10px;
            margin: 10px 20px;
            border-left: 5px solid #4CAF50;
        }
    </style>
</head>

<body>

    <nav>
        <div class="nav-left">
            <h1>VidyaSathi</h1>
        </div>
        <div class="nav-right">
            <a href="student_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <header class="welcome-section">
        <h2>My Uploads</h2>
    </header>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"><?php echo $_SESSION['message'];
        unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Uploaded On</th>
                    <?php if ($role === 'admin')
                        echo '<th>Actions</th>'; ?>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td>
                            <?php
                            switch ($row['approved']) {
                                case 1:
                                    echo '✅ Approved';
                                    break;
                                case -1:
                                    echo '❌ Rejected';
                                    break;
                                default:
                                    echo '⏳ Pending';
                                    break;
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['upload_date']); ?></td>
                        <?php if ($role === 'admin'): ?>
                            <td>
                                <?php if ($row['approved'] === 0): ?>
                                    <form action="update_status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="file_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="status" value="approved" class="btn-approve">Approve</button>
                                    </form>
                                    <form action="update_status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="file_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="status" value="rejected" class="btn-reject">Reject</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td><a href="<?php echo htmlspecialchars($row['file_path']); ?>" download>Download</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>

</html>