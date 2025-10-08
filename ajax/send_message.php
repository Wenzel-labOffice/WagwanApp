<?php
include '../master/config.php';
session_start();

$newcrud = new Crud();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'], $_POST['sendor_id'])) {
    $sender_id = $_POST['sendor_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);
    $attachment = null;

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../attachment/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = basename($_FILES['file']['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $newName = uniqid('file_', true) . '.' . $ext;
        $uploadPath = $uploadDir . $newName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath)) {
            $attachment = $newName;
        }
    }

    if ($message !== "" || $attachment !== null) {
        $data = [
            'sender_id'   => $sender_id,
            'receiver_id' => $receiver_id,
            'message'      => $message,
            'attachment'   => $attachment,
            'sent_time'    => date('Y-m-d H:i:s')
        ];
        $insert = $newcrud->InsertData('message', $data);
        if ($insert) {
            echo "success|" . ($attachment ?? '');
        } else {
            echo "error";
        }
    } else {
        echo "empty";
    }
    exit;
}

if (isset($_GET['token_id']) && !empty($_GET['token_id'])) {
    $token_id = $_GET['token_id'];
    $current_user = $_SESSION['id'];
    $users = $newcrud->FetchData('register_user', "regid = '$token_id'");
    $messages = $newcrud->FetchData(
        'message',
        "(sender_id = '$current_user' AND receiver_id = '$token_id') 
         OR (sender_id = '$token_id' AND receiver_id = '$current_user') 
         ORDER BY sent_time ASC"
    );
    if ($users) {
        foreach ($users as $user) {
?>
            <header class="msger-header">
                <div class="msger-header-title">
                    <span class="user-icon">🤝</span>
                    <?= htmlspecialchars($user['name']) ?>

                </div>
                <div class="sideright">
                    <?php if (!empty($user['last_login'])): ?>
                        <!-- <small style="margin-right: 7px;">Last seen: <?= date('g:i A, d M', strtotime($user['last_login'])) ?></small> -->
                        <small>Last seen: <?= timeAgo($user['last_login']) ?></small>
                    <?php endif; ?>
                    <!-- <div class="msger-header-options">
                        <span><i class="fas fa-cog"></i></span>
                    </div>
                    <div class="msger-header-options">
                        <a href="logout.php" title="Logout"><i class="fas fa-power-off"></i></a>
                    </div> -->
                </div>
            </header>
            <main class="msger-chat">
                <?php if ($messages): ?>
                    <?php foreach ($messages as $msg):
                        $is_sender = $msg['sender_id'] == $_SESSION['id'];
                        $sender_name = $is_sender ? "You" : htmlspecialchars($user['name']);
                    ?>
                        <div class="msg <?= $is_sender ? 'right-msg' : 'left-msg' ?>">
                            <div class="msg-img" style="background-image: url('<?= $is_sender
                                                                                    ? 'https://cdn-icons-png.flaticon.com/512/145/145867.png'
                                                                                    : 'https://cdn-icons-png.flaticon.com/512/327/327779.png' ?>')"></div>
                            <div class="msg-bubble">
                                <div class="msg-info">
                                    <div class="msg-info-name"><?= $sender_name ?></div>
                                    <div class="msg-info-time"><?= date('g:i A', strtotime($msg['sent_time'])) ?></div>
                                </div>
                                <div class="msg-text"><?= htmlspecialchars($msg['message']) ?></div>
                                <?php if (!empty($msg['attachment'])): ?>
                                    <div class="msg-attachment">
                                        <img src="attachment/<?= $msg['attachment'] ?>" alt="attachment" style="max-width:200px;max-height:200px;">

                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($is_sender): ?>
                                <span class="msg-seen-status">
                                    <?= $msg['is_read'] ? '<small style="color:green;">Seen</small>' : '<small style="color:gray;">Delivered</small>' ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <p>No messages yet. Start chatting!</p>
                <?php endif; ?>
            </main>


            <form class="msger-inputarea" id="chatForm" method="post" enctype="multipart/form-data">
                <input type="hidden" id="receiver_id" value="<?= htmlspecialchars($user['regid']) ?>" name="receiver_id">
                <input type="hidden" id="sendor_id" value="<?= htmlspecialchars($_SESSION['id']) ?>" name="sendor_id">
                <input type="file" id="fileInput" name="file" style="display:none;">
                <label for="fileInput" class="file-label" title="Attach a file">
                    <i class="fas fa-paperclip"></i>
                </label>
                <input type="text" name="message" class="msger-input" id="messageInput" placeholder="Enter your message...">
                <button type="submit" class="msger-send-btn">Send</button>
            </form>
<?php
        }
    } else {
        echo '<p class="text-center text-muted">User not found.</p>';
    }
} else {
    echo '<p class="text-center text-muted">No user selected.</p>';
}



?>