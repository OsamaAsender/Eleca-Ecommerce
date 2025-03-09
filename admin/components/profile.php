<?php
include 'connect.php';

session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (!$user_id) {
    header('Location: user_login.php');
    exit();
}


// Ensure the `profile_image` column exists (only runs once if needed)
$check_column = $conn->query("SHOW COLUMNS FROM `user` LIKE 'image'");
if ($check_column->rowCount() === 0) {
    $conn->query("ALTER TABLE `user` ADD `image` VARCHAR(255) DEFAULT 'default.png'");
}

// Fetch user profile
$fetch_user = $conn->prepare("SELECT * FROM `user` WHERE id = ?");
$fetch_user->execute([$user_id]);
$fetch_profile = $fetch_user->fetch(PDO::FETCH_ASSOC) ?: [];

// Profile update message
$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($name) && !empty($email)) {
        $update_profile = $conn->prepare("UPDATE `user` SET name = ?, email = ? WHERE id = ?");
        $update_profile->execute([$name, $email, $user_id]);
        $message[] = 'Profile updated successfully!';
    } else {
        $message[] = 'Name and email cannot be empty!';
    }

    // Handle password change
    if (!empty($new_password)) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_password = $conn->prepare("UPDATE `user` SET password = ? WHERE id = ?");
            $update_password->execute([$hashed_password, $user_id]);
            $message[] = 'Password updated successfully!';
        } else {
            $message[] = 'Passwords do not match!';
        }
    }

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $img_name = $_FILES['profile_image']['name'];
        $img_tmp = $_FILES['profile_image']['tmp_name'];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($img_ext, $allowed_exts)) {
            $new_img_name = "profile_" . time() . ".$img_ext";
            move_uploaded_file($img_tmp, "images/$new_img_name");

            $update_image = $conn->prepare("UPDATE `user` SET profile_image = ? WHERE id = ?");
            $update_image->execute([$new_img_name, $user_id]);

            $message[] = 'Profile picture updated!';
            $fetch_profile['image'] = $new_img_name;  // Update for immediate display
        } else {
            $message[] = 'Invalid file type! Only JPG, PNG, GIF allowed.';
        }
    }
}
// 🛒 Ensure the `order` table has some test data



// Fetch order history
// Ensure `created_at` column exists in `orders` table
$check_created_at = $conn->query("SHOW COLUMNS FROM `order` LIKE 'created_at'");
if ($check_created_at->rowCount() === 0) {
    $conn->query("ALTER TABLE `order` ADD `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
}

$fetch_orders = $conn->prepare("SELECT * FROM `order` WHERE user_id = ? ORDER BY created_at DESC");
$fetch_orders->execute([$user_id]);
$orders = $fetch_orders->fetchAll(PDO::FETCH_ASSOC);

// Fetch wishlist
$fetch_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
$fetch_wishlist->execute([$user_id]);
$wishlist = $fetch_wishlist->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>



<div class="container mt-5">
    <div class="profile-container">
        <h2><i class="fa-solid fa-user"></i> My Profile</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
                <?php foreach ($message as $msg): ?>
                    <p class="mb-0"><?= htmlspecialchars($msg); ?></p>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="text-center profile-img">
            <img src="<?= !empty($fetch_profile['image']) ? 'images' . $fetch_profile['image'] : '/default.png'; ?>" class="rounded-circle" width="120" height="120">
        </div>
        <p><strong>Username:</strong> <?= htmlspecialchars($fetch_profile["name"] ?? 'N/A'); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($fetch_profile["email"] ?? 'N/A'); ?></p>

        <h3>Update Profile</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input type="text" name="name" value="<?= htmlspecialchars($fetch_profile['name'] ?? ''); ?>" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($fetch_profile['email'] ?? ''); ?>" required class="form-control">
            </div>

            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Picture</label>
                <input type="file" name="profile_image" accept="image/*" class="form-control">
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
            </div>

            <input type="submit" value="Save Changes" class="btn btn-primary mt-3">
        </form>
    </div>

    

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>