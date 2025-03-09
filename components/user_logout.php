<?php
// Include connection and start the session
include 'connect.php';
session_start();

// Check if logout is confirmed
if (isset($_GET['logout']) && $_GET['logout'] == 'yes') {
    // Destroy the session
    session_unset();
    session_destroy();
    // Redirect to home page after logout
    header('location:../home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            text-align: center;
        }

        .modal-button {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
        }

        .modal-button:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

    <!-- Modal for logout confirmation -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h3>Are you sure you want to log out?</h3>
            <button class="modal-button" id="confirmLogout">Yes</button>
            <button class="modal-button" id="cancelLogout">No</button>
        </div>
    </div>

    <script>
        // Get the logout link and confirmation modal
        const logoutLink = document.getElementById('logoutLink');
        const confirmModal = document.getElementById('confirmModal');
        const confirmLogout = document.getElementById('confirmLogout');
        const cancelLogout = document.getElementById('cancelLogout');

        // Event listener for the logout link
        logoutLink.onclick = function(e) {
            // Prevent the default action of the link (logout)
            e.preventDefault();

            // Show the confirmation modal
            confirmModal.style.display = 'block';
            
            // If user clicks "Yes", proceed with logout
            confirmLogout.onclick = function() {
                // Redirect to the actual logout URL
                window.location.href = logoutLink.href; // Using the href of the logout link
            };

            // If user clicks "No", hide the modal
            cancelLogout.onclick = function() {
                // Close the modal and do nothing
                confirmModal.style.display = 'none';
            };
        };
    </script>

</body>
</html>
