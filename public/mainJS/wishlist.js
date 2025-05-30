/**
 * Wishlist functionality for VisitJo
 * Handles adding and removing activities from the wishlist
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all wishlist buttons
    initWishlistButtons();

    // Initialize modal confirm handler
    initModalHandlers();
});

/**
 * Initialize all wishlist buttons with proper state and event handlers
 */
function initWishlistButtons() {
    const wishlistButtons = document.querySelectorAll('.favorite-btn');

    wishlistButtons.forEach(button => {
        const activityId = button.getAttribute('data-activity-id');
        const heartIcon = button.querySelector('i');

        // Check if this activity is already in the wishlist
        checkWishlistStatus(activityId, function(inWishlist) {
            if (inWishlist) {
                heartIcon.classList.remove('far');
                heartIcon.classList.add('fas');
                heartIcon.style.color = '#e74c3c'; // Explicitly set the color
            }
        });

        // Add click event listener
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            toggleWishlistItem(activityId, heartIcon);
        });
    });

    // Initialize remove buttons in profile page
    const removeBtns = document.querySelectorAll('.remove-wishlist-form .remove-btn');
    removeBtns.forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            const form = this.closest('form');
            showConfirmModal(
                'Remove from Wishlist',
                'Are you sure you want to remove this activity from your wishlist?',
                function() {
                    form.submit();
                }
            );
            return false;
        };
    });
}

/**
 * Initialize modal event handlers
 */
function initModalHandlers() {
    // Store callback for the confirm button
    window.modalConfirmCallback = null;

    // Attach click handler to the confirm button
    document.getElementById('modalConfirmBtn').addEventListener('click', function() {
        // Execute the callback if it exists
        if (window.modalConfirmCallback && typeof window.modalConfirmCallback === 'function') {
            window.modalConfirmCallback();
        }

        // Hide the modal
        const modalElement = document.getElementById('appModal');
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        modalInstance.hide();
    });
}

/**
 * Show a confirmation modal dialog
 * @param {string} title - The title of the modal
 * @param {string} message - The message to display
 * @param {function} confirmCallback - Function to execute on confirm
 */
function showConfirmModal(title, message, confirmCallback) {
    const modalElement = document.getElementById('appModal');
    const modalTitle = document.getElementById('appModalLabel');
    const modalBody = document.getElementById('appModalBody');

    // Set the modal content
    modalTitle.textContent = title;
    modalBody.textContent = message;

    // Store the callback
    window.modalConfirmCallback = confirmCallback;

    // Show the modal
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}

/**
 * Show a message modal without confirmation button
 * @param {string} title - The title of the modal
 * @param {string} message - The message to display
 */
function showMessageModal(title, message) {
    const modalElement = document.getElementById('appModal');
    const modalTitle = document.getElementById('appModalLabel');
    const modalBody = document.getElementById('appModalBody');
    const confirmBtn = document.getElementById('modalConfirmBtn');
    const cancelBtn = document.getElementById('modalCancelBtn');

    // Set the modal content
    modalTitle.textContent = title;
    modalBody.textContent = message;

    // Hide the confirm button and rename cancel to Close
    confirmBtn.style.display = 'none';
    cancelBtn.textContent = 'Close';

    // Show the modal
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    // Reset button state when modal is hidden
    modalElement.addEventListener('hidden.bs.modal', function() {
        confirmBtn.style.display = 'block';
        cancelBtn.textContent = 'Cancel';
    }, { once: true });
}

/**
 * Check if an activity is in the user's wishlist
 * @param {number} activityId - The activity ID to check
 * @param {function} callback - Callback function with result (true/false)
 */
function checkWishlistStatus(activityId, callback) {
    fetch(`/wishlist/check?activity_id=${activityId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        callback(data.in_wishlist);
    })
    .catch(error => {
        console.error('Error checking wishlist status:', error);
        callback(false);
    });
}

/**
 * Toggle an activity in the wishlist (add or remove)
 * @param {number} activityId - The activity ID to toggle
 * @param {HTMLElement} heartIcon - The heart icon element to update
 */
function toggleWishlistItem(activityId, heartIcon) {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            activity_id: activityId
        })
    })
    .then(response => {
        if (response.status === 401) {
            // User not logged in, show login modal
            showLoginPrompt();
            return null;
        }
        return response.json();
    })
    .then(data => {
        if (!data) return;

        if (data.success) {
            updateHeartIcon(heartIcon, data.is_added);
            showWishlistNotification(data.message);
        } else {
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Error toggling wishlist item:', error);
    });
}

/**
 * Update the heart icon appearance based on wishlist status
 * @param {HTMLElement} heartIcon - The heart icon element
 * @param {boolean} isAdded - Whether the item was added to the wishlist
 */
function updateHeartIcon(heartIcon, isAdded) {
    if (isAdded) {
        heartIcon.classList.remove('far');
        heartIcon.classList.add('fas');
        heartIcon.style.color = '#e74c3c'; // Force red color with inline style
    } else {
        heartIcon.classList.remove('fas');
        heartIcon.classList.add('far');
        heartIcon.style.color = '#ccc'; // Reset to default color with inline style
    }
}

/**
 * Show login prompt when user is not authenticated
 */
function showLoginPrompt() {
    const modalElement = document.getElementById('appModal');
    const modalTitle = document.getElementById('appModalLabel');
    const modalBody = document.getElementById('appModalBody');
    const confirmBtn = document.getElementById('modalConfirmBtn');

    // Set the modal content
    modalTitle.textContent = 'Login Required';
    modalBody.textContent = 'Please log in to save items to your wishlist.';

    // Change the confirm button text to "Log in"
    confirmBtn.textContent = 'Log in';

    // Store the callback that redirects to login page
    window.modalConfirmCallback = function() {
        window.location.href = '/login';
    };

    // Show the modal
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    // Reset button text when modal is hidden
    modalElement.addEventListener('hidden.bs.modal', function() {
        confirmBtn.textContent = 'Confirm';
    }, { once: true });
}

/**
 * Show a notification about wishlist changes
 * @param {string} message - The message to display
 */
function showWishlistNotification(message) {
    // Check if a notification container exists, if not create one
    let notificationContainer = document.getElementById('wishlist-notification');

    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'wishlist-notification';
        notificationContainer.style.position = 'fixed';
        notificationContainer.style.bottom = '20px';
        notificationContainer.style.right = '20px';
        notificationContainer.style.zIndex = '9999';
        document.body.appendChild(notificationContainer);
    }

    // Create and show the notification
    const notification = document.createElement('div');
    notification.style.backgroundColor = '#92400b';
    notification.style.color = 'white';
    notification.style.padding = '10px 15px';
    notification.style.borderRadius = '4px';
    notification.style.marginTop = '10px';
    notification.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
    notification.textContent = message;

    notificationContainer.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            notificationContainer.removeChild(notification);
        }, 500);
    }, 3000);
}
