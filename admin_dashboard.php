<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit;
}

require_once "config.php";
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="shortcut icon" href="images/logoo.png" type="image/png">
<title>Admin Dashboard - Digital Age</title>
<style>
    :root {
        --primary: #051405;
        --secondary: #15b400;
        --secondary-dark: #0c8200;
        --accent: #d4ff00;
        --bg-light: #f4faf4;
        --surface: rgba(255, 255, 255, 0.95);
        --text-dark: #0f1c0f;
        --text-muted: #4b5e4b;
        --border: rgba(21, 180, 0, 0.2);
        --radius: 12px;
    }
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        font-family: "Inter", sans-serif;
        background: var(--bg-light);
        color: var(--text-dark);
        -webkit-font-smoothing: antialiased;
    }
    header {
        background: var(--surface);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
        padding: 1.5rem 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .header-logo {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .header-logo img {
        height: 40px;
    }
    header h1 {
        font-size: 1.5rem;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        margin: 0;
        color: var(--primary);
    }
    .badge {
        background: rgba(16, 185, 129, 0.1);
        color: var(--secondary-dark);
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 1rem;
    }
    .btn-logout {
        background: rgba(220, 38, 38, 0.1);
        color: #dc2626;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-logout:hover {
        background: #dc2626;
        color: white;
    }
    main {
        padding: 3rem 2.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }
    .table-container {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        border: 1px solid var(--border);
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    th, td {
        padding: 1.25rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }
    th {
        background: var(--bg-light);
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-family: 'Outfit', sans-serif;
    }
    tr:last-child td {
        border-bottom: none;
    }
    tr:nth-child(even) {
        background: rgba(248, 250, 252, 0.5);
    }
    tr:hover {
        background: rgba(241, 245, 249, 0.8);
    }
    td {
        vertical-align: top;
        font-size: 0.95rem;
        color: var(--text-dark);
    }
    .id-col {
        font-weight: 600;
        color: var(--secondary-dark);
    }
    .date-col {
        color: var(--text-muted);
        font-size: 0.85rem;
        white-space: nowrap;
    }
    .msg-content {
        color: var(--text-muted);
        max-width: 400px;
        line-height: 1.6;
    }
    .empty-state {
        padding: 4rem;
        text-align: center;
        color: var(--text-muted);
        font-family: 'Outfit', sans-serif;
    }
</style>
</head>
<body>
    <header>
        <div class="header-logo">
            <img src="images/logoo.png" alt="Digital Age">
            <h1>Admin Dashboard <span class="badge">Live</span></h1>
        </div>
        <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </header>

    <main>
        <div style="margin-bottom: 2rem;">
            <h2 style="font-family: 'Outfit', sans-serif; font-size: 2rem; color: var(--primary); margin: 0 0 0.5rem 0;">Inbox Messages</h2>
            <p style="color: var(--text-muted); margin: 0;">Manage your contact form submissions.</p>
        </div>

        <div class="table-container">
            <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">Contact Details</th>
                    <th width="20%">Subject</th>
                    <th width="45%">Message</th>
                    <th width="15%">Date Received</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td class="id-col">#<?= htmlspecialchars($row['id']) ?></td>
                    <td>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;"><?= htmlspecialchars($row['full_name']) ?></div>
                        <div style="color: var(--text-muted); font-size: 0.85rem;">
                            <a href="mailto:<?= htmlspecialchars($row['email']) ?>" style="color: var(--secondary-dark); text-decoration: none;">
                                <?= htmlspecialchars($row['email']) ?>
                            </a>
                        </div>
                    </td>
                    <td style="font-weight: 500;"><?= htmlspecialchars($row['subject']) ?></td>
                    <td class="msg-content"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td class="date-col"><?= date('M j, Y, g:i a', strtotime($row['created_at'])) ?></td>
                </tr>
                <?php } ?>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--border); margin-bottom: 1rem;"></i>
                <h3>No messages yet</h3>
                <p>When someone contacts you, their message will appear here.</p>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
