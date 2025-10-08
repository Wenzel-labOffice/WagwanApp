<?php
include('master/config.php');
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$crud = new Crud();
$id = $_SESSION['id'];
$users = $crud->FetchData('register_user', "regid != '$id'");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatApp</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

</head>

<body>

    <aside class="user-list">
        <h3>Friends</h3>
        <div class="users-container">
            <?php foreach ($users as $user): ?>
                <div class="user" onclick="fetchTicketDetails(this)" data-token-id="<?= $user['regid'] ?>">
                    <span class="user-icon">😊</span>
                    <div class="user-details">
                        <span class="user-name"><?= htmlspecialchars($user['name']) ?></span>
                        <small class="user-status">(<?= htmlspecialchars($user['status']) ?>)</small>
                    </div>
                    <span class="unread-badge" id="badge-<?= $user['regid'] ?>"></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="user-actions">
            <button class="action-btn settings-btn" title="Settings">
                <i class="fas fa-cog"></i> Settings
            </button>
            <a href="logout.php" class="action-btn logout-btn" title="Logout">
                <i class="fas fa-power-off"></i> Logout
            </a>
        </div>
    </aside>

    <section class="msger" id="ticket-details-container">
        <header class="msger-header">
            <div class="msger-header-title">
                <span class="user-icon">👯</span> ChatWithFriends
            </div>
        </header>
        <main class="msger-chat">
            <div class="msg right-msg">
                <div class="msg-img" style="background-image: url('https://cdn-icons-png.flaticon.com/512/145/145867.png')"></div>
                <div class="msg-bubble">
                    <div class="msg-info">
                        <div class="msg-info-name"><?= isset($_SESSION['id']) ? 'You' : 'Guest' ?></div>
                        <div class="msg-info-time"><?= date('H:i') ?></div>
                    </div>
                    <div class="msg-text"><i>Hey! <?= isset($_SESSION['id']) ? $_SESSION['name'] : 'You' ?></i>
                        <p>👥 Chat with Friends</p>
                        <p>Start a new conversation 💬 and share the joy 😁!</p>
                        <p>🧑‍🤝‍🧑 Stay close. Stay connected.</p>
                    </div>

                </div>
            </div>
        </main>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/script.js"></script>

</body>

</html>