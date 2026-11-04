require('./bootstrap');
try {
    const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');

    if (userId != undefined || userId != null) {
        if (!userId) {
            throw new Error("User ID not found in meta tag.");
        }
        Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                try {
                    updateNotificationUI(notification);
                } catch (uiError) {
                    console.error("Error updating notification UI:", uiError);
                }
            })
            .error((error) => {
                console.error("Error subscribing to private channel:", error);
            });
    }

} catch (err) {
    console.error("Initialization error:", err);
}

try {
    const adminId = document.querySelector('meta[name="admin-id"]')?.getAttribute('content');

    if (adminId != undefined || adminId != null) {
        if (!adminId) {
            throw new Error("Admin ID not found in meta tag.");
        }

        Echo.private(`App.Models.Admin.${adminId}`)
            .notification((notification) => {
                try {
                    updateAdminNotificationUI(notification);
                } catch (uiError) {
                    console.error("Error updating admin notification UI:", uiError);
                }
            })
            .error((error) => {
                console.error("Error subscribing to admin private channel:", error);
            });
    }

} catch (err) {
    console.error("Initialization admin echo error:", err);
}


function updateNotificationUI(notification) {
    const notificationList = document.querySelector('.notification-list');
    if (notificationList) {

        const data = notification || {};
        const createdAt = new Date(data.updated_at); // Use the created_at from the server
        const humanReadableTime = timeAgo(createdAt);

        const newNotification = `
            <li class="border-bottom">
                 <a class="dropdown-item p-3 pb-1 notification-link" data-id="${data.id}" data-click-url="${data.click_url || '#'}" data-read="false">
                    <div class="d-flex py-1">
                        <p class="circle text-green bg-light border me-2">
                            <i class="far fa-bell fs-5 mt-2"></i>
                        </p>
                        <div class="d-flex flex-column text-truncate" style="flex-grow: 1;">
                            <p class="mb-0 text-secondary fs-12 w-100">
                                ${data.message ? data.message.substring(0, 40) : ''}
                            </p>
                            <p class="mb-0 text-secondary fs-12 w-100">
                                ${data.message ? data.message.substring(40, 90) : ''}
                            </p>
                            <p class="mb-0 fs-12 text-secondary">
                                ${humanReadableTime}
                            </p>
                        </div>
                        <i class="ms-2 align-self-center far fa-envelope text-secondary"></i>
                    </div>
                </a>
            </li>`;
        // Check if the list contains only the "No new notifications" placeholder
        const placeholder = notificationList.querySelector(".text-muted");
        if (placeholder) {
            placeholder.remove(); // Remove the placeholder if it exists
        }
        notificationList.insertAdjacentHTML('afterbegin', newNotification);
    }


    const unreadCountElement = document.querySelector('.notification-unread-count');
    const notificationButton = document.querySelector('[data-bs-toggle="dropdown"]');

    if (!unreadCountElement && notificationButton) {
        const newUnreadCountElement = document.createElement('span');

        newUnreadCountElement.className =
            'notification-unread-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fs-10';

        newUnreadCountElement.style.minWidth = '18px';
        newUnreadCountElement.style.height = '18px';
        newUnreadCountElement.style.display = 'flex';
        newUnreadCountElement.style.alignItems = 'center';
        newUnreadCountElement.style.justifyContent = 'center';
        newUnreadCountElement.style.marginLeft = '-8px';
        newUnreadCountElement.style.marginTop = '2px';

        newUnreadCountElement.innerText = 1;

        notificationButton.appendChild(newUnreadCountElement);
    } 
    else if (unreadCountElement) {
        let unreadCount = parseInt(unreadCountElement.innerText, 10) || 0;
        unreadCount++;
        unreadCountElement.innerText = unreadCount;
    }
}

function timeAgo(date) {
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    const intervals = {
        year: 31536000,
        month: 2592000,
        day: 86400,
        hour: 3600,
        minute: 60,
        second: 1,
    };

    for (const [unit, value] of Object.entries(intervals)) {
        const count = Math.floor(seconds / value);
        if (count > 0) {
            return `${count} ${unit}${count > 1 ? 's' : ''} ago`;
        }
    }

    return 'just now';
}

document.addEventListener('click', function (e) {
    const notificationLink = e.target.closest('.notification-link');

    if (notificationLink) {
        e.preventDefault();

        const notificationId = notificationLink.dataset.id;
        const isRead = notificationLink.dataset.read === 'true';
        const clickUrl = notificationLink.dataset.clickUrl || notificationLink.getAttribute('href');

        if (!isRead) {
            fetch('/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    notification_id: notificationId
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notificationLink.dataset.read = 'true';
                    const icon = notificationLink.querySelector('.fa-envelope');
                    if (icon) {
                        icon.classList.remove('fa-envelope');
                        icon.classList.add('fa-envelope-open');
                    }
                }

                window.location.href = clickUrl;
            })
            .catch(() => {
                window.location.href = clickUrl;
            });

        } else {
            window.location.href = clickUrl;
        }
    }
});

function updateAdminNotificationUI(notification) {

    const container = document.querySelector('.admin-dynamic-notification-list');
    if (!container) return;
    const image = notification.user_image
        ? notification.user_image
        : "https://cdn-icons-png.freepik.com/512/7235/7235981.png";


    // Use click_url directly if available, otherwise fall back to the read-notification URL
    const clickUrl = `${'/admin/notification/read'}/${notification.id}`;

    const html = `
        <a href="${clickUrl}" class="dropdown-menu__item">
            <div class="navbar-notifi">

                <div class="navbar-notifi__left bg--green b-radius--rounded">
                    <img src="${image}" alt="Profile Image">
                </div>

                <div class="navbar-notifi__right">
                    <h6 class="notifi__title">${notification.message ?? ''}</h6>

                    <span class="time">
                        <i class="far fa-clock"></i> just now
                    </span>
                </div>

            </div>
        </a>
    `;
    container.insertAdjacentHTML('afterbegin', html);
    updateAdminNotificationCounter();
}

function updateAdminNotificationCounter() {
    const pulse = document.getElementById('adminNotificationPulse');
    if (pulse) {
        pulse.style.display = 'inline-block';
    }

    const header = document.getElementById('adminNotificationHeaderText');

    const totalNotifications = document.querySelectorAll(
        '.admin-dynamic-notification-list .dropdown-menu__item'
    ).length;

    if (header) {
        header.innerHTML = `You have ${totalNotifications} unread notification`;
    }

}