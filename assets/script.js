let currentTokenId = null;

function fetchTicketDetails(element) {
  var token_id = $(element).data("token-id");
  $.ajax({
    url: "ajax/mark_read.php",
    method: "POST",
    data: {
      sender_id: token_id,
    },
    success: function () {
      $("#badge-" + token_id)
        .hide()
        .text("");
      $.ajax({
        url: "ajax/send_message.php",
        type: "GET",
        data: {
          token_id: token_id,
        },
        success: function (response) {
          $("#ticket-details-container").html(response);
        },
        error: function () {
          alert("Error fetching user chat.");
        },
      });
    },
  });
}

$(document).on("submit", "#chatForm", function (e) {
  e.preventDefault();

  let sender_id = $("#sendor_id").val();
  let receiver_id = $("#receiver_id").val();
  let message = $("#messageInput").val().trim();
  let form = document.getElementById("chatForm");
  let formData = new FormData(form);
  $.ajax({
    url: "ajax/send_message.php",
    method: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      let parts = response.trim().split("|");
      let status = parts[0];
      let uploadedFileName = parts[1] || "";

      if (status === "success") {
        const now = new Date();
        const time = now.getHours().toString().padStart(2, "0") + ":" + now.getMinutes().toString().padStart(2, "0");
        const message = $("#messageInput").val().trim();

        let attachmentHtml = "";
        if (uploadedFileName !== "") {
          attachmentHtml = `
                        <div class="msg-attachment">
                            <img src="attachment/${uploadedFileName}" 
                                alt="attachment" 
                                style="max-width:200px; max-height:200px; margin-top: 5px;">
                        </div>`;
        }
        const messageHtml = `
                        <div class="msg right-msg">
                            <div class="msg-img" style="background-image: url('https://cdn-icons-png.flaticon.com/512/145/145867.png')"></div>
                            <div class="msg-bubble">
                                <div class="msg-info">
                                    <div class="msg-info-name">You</div>
                                    <div class="msg-info-time">${time}</div>
                                </div>
                                <div class="msg-text">${$("<div>").text(message).html()}</div>
                                ${attachmentHtml}
                            </div>
                        </div>`;

        $(".msger-chat").append(messageHtml);
        $("#messageInput").val("");
        $("#fileInput").val("");
        $(".msger-chat").scrollTop($(".msger-chat")[0].scrollHeight);
      } else {
        alert("Message not sent: " + response);
      }
    },
    error: function (xhr, status, error) {
      alert("AJAX error: " + error);
    },
  });
});

function updateUnreadBadges() {
  $.ajax({
    url: "ajax/unread_counts.php",
    method: "GET",
    dataType: "json",
    success: function (data) {
      $(".unread-badge").hide().text("");
      for (const sender_id in data) {
        const count = data[sender_id];
        if (count > 0) {
          $("#badge-" + sender_id)
            .show()
            .text(count);
        }
      }
    },
  });
}
setInterval(updateUnreadBadges, 2000);
updateUnreadBadges();
