// Function to fetch unread notifications
function fetchUnreadNotifications() {
    $.ajax({
        url: 'fetchUnreadNotifications.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var notificationList = $('#notificationsDropdown');
            notificationList.empty();
            $('#notificationCount').text(response.length);
            $('#notificationHeader').text(response.length + ' New Notifications');
            response.forEach(function(notification) {
                var formattedDate = moment(notification.created_at).format('DD-MM-YYYY HH:mm:ss');
                var truncatedMessage = notification.message.length > 50 ? notification.message.substring(0, 50) + '...' : notification.message;
                var dropdownItemHtml = `<a href="#" class="dropdown-item" onclick="showFullMessage('${encodeURIComponent(notification.message)}', '${formattedDate}', ${notification.id});">
                    <i class="fas fa-envelope mr-2"></i> ${truncatedMessage}
                    <span class="float-right text-muted text-sm">${formattedDate}</span>
                </a>`;
                notificationList.append(dropdownItemHtml);
            });
        },
        error: function() {
            console.log('Error fetching unread notifications');
        }
    });
}

// Function to fetch all notifications
function fetchAllNotifications() {
    $.ajax({
        url: 'fetchAllNotifications.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            var allNotificationsList = $('#allNotificationsList');
            allNotificationsList.empty();
            response.forEach(function(notification) {
                var formattedDate = moment(notification.created_at).format('DD-MM-YYYY HH:mm:ss');
                var listItemHtml = `<li class="list-group-item">${notification.message} - <small>${formattedDate}</small></li>`;
                allNotificationsList.append(listItemHtml);
            });
        },
        error: function() {
            console.log('Error fetching all notifications');
        }
    });
}

$(document).ready(function() {
    // Fetch unread notifications on page load and every 5 minutes
    fetchUnreadNotifications();
    setInterval(fetchUnreadNotifications, 300000); // Refresh every 5 minutes

    // Bind 'View All Notifications' button to fetch all notifications and show the modal
    $('.dropdown-item.dropdown-footer').on('click', function() {
        fetchAllNotifications();
        $('#allNotificationsModal').modal('show');
    });
});

function showFullMessage(encodedMessage, date, notificationId) {
    var message = decodeURIComponent(encodedMessage);
    $('#fullMessageModal .modal-body').html(`<strong>Full Message:</strong> ${message}<br><small>${date}</small>`);
    $('#fullMessageModal').modal('show');

    markNotificationRead(notificationId); // Pass the notification ID to be marked as read
}


// Function to mark a notification as read
function markNotificationRead(notificationId) {
    $.ajax({
        url: 'mark_notification_read.php',
        type: 'POST',
        data: { notification_id: notificationId },
        success: function(response) {
            fetchUnreadNotifications();  // Refresh the list to show updated read status
        },
        error: function(xhr) {
            console.error('Error marking notification as read', xhr.responseText);
        }
    });
}
