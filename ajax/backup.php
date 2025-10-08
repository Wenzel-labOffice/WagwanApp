<!-- <?php
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
        <h3 style="margin-bottom: 5px;">Users</h3>
        <?php foreach ($users as $user): ?>
            <div class="user" onclick="fetchTicketDetails(this)" data-token-id="<?php echo $user['regid']; ?>">
                <span><?= htmlspecialchars($user['name']) ?></span>
                <small style="color:gray;">(<?= htmlspecialchars($user['status']) ?>)</small>
            </div>
        <?php endforeach; ?>
    </aside>

    <section class="msger" id="ticket-details-container">
        <header class="msger-header">
            <div class="msger-header-title">
                <i class="fas fa-comment-alt"></i> SimpleChat
            </div>
            <div class="sideright">
                <div class="msger-header-options">
                    <span><i class="fas fa-cog"></i></span>
                </div>
                <div class="msger-header-options">
                    <a href="logout.php" title="Logout"><i class="fas fa-power-off"></i></a>
                </div>
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
                    <div class="msg-text">You can change your name in JS section!</div>
                </div>
            </div>
        </main>
    </section>
    <script src="assets/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function fetchTicketDetails(element) {
            var token_id = $(element).data("token-id");
            $.ajax({
                url: "ajax/send_message.php",
                type: "GET",
                data: {
                    token_id: token_id
                },
                success: function(response) {
                    $("#ticket-details-container").html(response);
                },
                error: function() {
                    alert("Error fetching user chat.");
                }
            });
        }
        $(document).ready(function() {
            // Attach the submit handler using event delegation
            $(document).on("submit", "#chatForm", function(e) {
                e.preventDefault();

                let sender_id = $("#sendor_id").val();
                let receiver_id = $("#receiver_id").val();
                let message = $("#messageInput").val().trim();

                if (message === "") return;

                // console.log("Sending message:", {
                //     sender_id,
                //     receiver_id,
                //     message
                // }); // for debugging

                $.ajax({
                    url: "ajax/send_message.php",
                    method: "POST",
                    data: {
                        sendor_id: sender_id,
                        receiver_id: receiver_id,
                        message: message
                    },
                    success: function(response) {
                        if (response.trim() === "success") {
                            const now = new Date();
                            const time = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
                            const messageHtml = `
                            <div class="msg right-msg">
                                <div class="msg-img" style="background-image: url('https://cdn-icons-png.flaticon.com/512/145/145867.png')"></div>
                                <div class="msg-bubble">
                                    <div class="msg-info">
                                        <div class="msg-info-name">You</div>
                                        <div class="msg-info-time">${time}</div>
                                    </div>
                                    <div class="msg-text">${$('<div>').text(message).html()}</div>
                                </div>
                            </div>`;
                            $(".msger-chat").append(messageHtml);
                            $("#messageInput").val("");
                            $(".msger-chat").scrollTop($(".msger-chat")[0].scrollHeight);
                            // $("#messageInput").val("");

                            // // Refresh messages
                            // fetchTicketDetails({
                            //     dataset: {
                            //         tokenId: receiver_id
                            //     }
                            // });
                        } else {
                            alert("Message not sent: " + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("AJAX error: " + error);
                    }
                });
            });

            // User selection click handler
            window.fetchTicketDetails = function(element) {
                var token_id = $(element).data("token-id");
                $.ajax({
                    url: "ajax/send_message.php",
                    type: "GET",
                    data: {
                        token_id: token_id
                    },
                    success: function(response) {
                        $("#ticket-details-container").html(response);
                    },
                    error: function() {
                        alert("Error fetching user chat.");
                    }
                });
            };
        });
    </script>
</body>

</html> -->

<!-- SEND_MESSAGE.PHP -->
<!-- <?php
        include '../master/config.php';
        session_start();

        $newcrud = new Crud();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'], $_POST['message'], $_POST['sendor_id'])) {
            $sender_id = $_POST['sendor_id'];
            $receiver_id = $_POST['receiver_id'];
            $message = trim($_POST['message']);

            if ($message != "") {
                $data = [
                    'sender_id' => $sender_id,
                    'receiver_id' => $receiver_id,
                    'message' => $message,
                    'attachment' => NULL,
                    'sent_time' => date('Y-m-d H:i:s')
                ];
                $insert = $newcrud->InsertData('message', $data);
                echo $insert ? "success" : "error";
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
                    <i class="fas fa-comment-alt"></i> <?= htmlspecialchars($user['name']) ?>
                </div>
                <div class="sideright">
                    <div class="msger-header-options">
                        <span><i class="fas fa-cog"></i></span>
                    </div>
                    <div class="msger-header-options">
                        <a href="logout.php" title="Logout"><i class="fas fa-power-off"></i></a>
                    </div>
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
                                    <div class="msg-info-time"><?= date('H:i', strtotime($msg['sent_time'])) ?></div>
                                </div>
                                <div class="msg-text"><?= htmlspecialchars($msg['message']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No messages yet. Start chatting!</p>
                <?php endif; ?>
            </main>


            <form class="msger-inputarea" id="chatForm" method="post">
                <input type="hidden" id="receiver_id" value="<?= htmlspecialchars($user['regid']) ?>" name="receiver_id">
                <input type="hidden" id="sendor_id" value="<?= htmlspecialchars($_SESSION['id']) ?>" name="sendor_id">
                <input type="text" name="message" class="msger-input" id="messageInput" placeholder="Enter your message..." required>
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



?> -->

<!-- config.php  -->

<!-- <?php
class Database
{
    private $host = "localhost";
    private $db_name = "practice_php";
    private $username = "root";
    private $password = "";
    public $conn;
    public function __construct()
    {
        $this->connect();
    }
    private function connect()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
class Crud extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function InsertData($table, $data = [])
    {
        if (empty($table) || empty($data)) {
            return false;
        }
        $columns = implode("`,`", array_keys($data));
        $postValues = array_map([$this->conn, 'real_escape_string'], array_values($data));
        $values = implode("','", $postValues);
        $sql = "INSERT INTO `$table` (`$columns`) VALUES('$values') ";
        $query = $this->conn->query($sql);
        return $query ? true : false;
    }
    public function FetchData($table, $where = "", $condition = "")
    {
        $sql = "SELECT * FROM `$table`";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        if (!empty($condition)) {
            $sql .= " $condition";
        }
        $query = $this->conn->query($sql);
        $records = [];
        if ($query && $query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $records[] = $row;
            }
        }
        return $records;
    }
}
class Auth extends Database
{
    public function __construct()
    {
        parent::__construct();
    }
    public function loginUser($table, $email, $password)
    {
        if (empty($table) || empty($email) || empty($password)) {
            return false;
        }
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT * FROM `$table` WHERE `email`='$email'";
        $query = $this->conn->query($sql);
        if ($query && $query->num_rows > 0) {
            $user = $query->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
} -->
  <!-- <script>
         // $(document).ready(function() {
        //     // Your existing send message logic
        //     $(document).on("submit", "#chatForm", function(e) {
        //         e.preventDefault();

        //         let sender_id = $("#sendor_id").val();
        //         let receiver_id = $("#receiver_id").val();
        //         let message = $("#messageInput").val().trim();

        //         if (message === "") return;

        //         $.ajax({
        //             url: "ajax/send_message.php",
        //             method: "POST",
        //             data: {
        //                 sendor_id: sender_id,
        //                 receiver_id: receiver_id,
        //                 message: message
        //             },
        //             success: function(response) {
        //                 if (response.trim() === "success") {
        //                     const now = new Date();
        //                     const time = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        //                     const messageHtml = `
        //                     <div class="msg right-msg">
        //                         <div class="msg-img" style="background-image: url('https://cdn-icons-png.flaticon.com/512/145/145867.png')"></div>
        //                         <div class="msg-bubble">
        //                             <div class="msg-info">
        //                                 <div class="msg-info-name">You</div>
        //                                 <div class="msg-info-time">${time}</div>
        //                             </div>
        //                             <div class="msg-text">${$('<div>').text(message).html()}</div>
        //                         </div>
        //                     </div>
        //                 `;
        //                     $(".msger-chat").append(messageHtml);
        //                     $("#messageInput").val("");
        //                     $(".msger-chat").scrollTop($(".msger-chat")[0].scrollHeight);
        //                 } else {
        //                     alert("Message not sent: " + response);
        //                 }
        //             },
        //             error: function(xhr, status, error) {
        //                 alert("AJAX error: " + error);
        //             }
        //         });
        //     });

        //     // ✅ Add this for polling every 3 seconds
        //     // setInterval(function() {
        //     //     if (currentTokenId) {
        //     //         $.ajax({
        //     //             url: "ajax/send_message.php",
        //     //             type: "GET",
        //     //             data: {
        //     //                 token_id: currentTokenId
        //     //             },
        //     //             success: function(response) {
        //     //                 $("#ticket-details-container").html(response);
        //     //             }
        //     //         });
        //     //     }
        //     // }, 1000); // every 3 seconds
        // });
        function fetchTicketDetails(element) {
            var token_id = $(element).data("token-id");
            $.ajax({
                url: "ajax/send_message.php",
                type: "GET",
                data: {
                    token_id: token_id
                },
                success: function(response) {
                    $("#ticket-details-container").html(response);
                },
                error: function() {
                    alert("Error fetching user chat.");
                }
            });
        }
        $(document).ready(function() {
            // Attach the submit handler using event delegation
            $(document).on("submit", "#chatForm", function(e) {
                e.preventDefault();

                let sender_id = $("#sendor_id").val();
                let receiver_id = $("#receiver_id").val();
                let message = $("#messageInput").val().trim();

                if (message === "") return;

                // console.log("Sending message:", {
                //     sender_id,
                //     receiver_id,
                //     message
                // }); // for debugging

                $.ajax({
                    url: "ajax/send_message.php",
                    method: "POST",
                    data: {
                        sendor_id: sender_id,
                        receiver_id: receiver_id,
                        message: message
                    },
                    success: function(response) {
                        if (response.trim() === "success") {
                            const now = new Date();
                            const time = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
                            const messageHtml = `
                            <div class="msg right-msg">
                                <div class="msg-img" style="background-image: url('https://cdn-icons-png.flaticon.com/512/145/145867.png')"></div>
                                <div class="msg-bubble">
                                    <div class="msg-info">
                                        <div class="msg-info-name">You</div>
                                        <div class="msg-info-time">${time}</div>
                                    </div>
                                    <div class="msg-text">${$('<div>').text(message).html()}</div>
                                </div>
                            </div>`;
                            $(".msger-chat").append(messageHtml);
                            $("#messageInput").val("");
                            $(".msger-chat").scrollTop($(".msger-chat")[0].scrollHeight);
                            // $("#messageInput").val("");

                            // // Refresh messages
                            // fetchTicketDetails({
                            //     dataset: {
                            //         tokenId: receiver_id
                            //     }
                            // });
                        } else {
                            alert("Message not sent: " + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("AJAX error: " + error);
                    }
                });
            });

            // User selection click handler
            window.fetchTicketDetails = function(element) {
                var token_id = $(element).data("token-id");
                $.ajax({
                    url: "ajax/send_message.php",
                    type: "GET",
                    data: {
                        token_id: token_id
                    },
                    success: function(response) {
                        $("#ticket-details-container").html(response);
                    },
                    error: function() {
                        alert("Error fetching user chat.");
                    }
                });
            };
        });
    </script> -->
