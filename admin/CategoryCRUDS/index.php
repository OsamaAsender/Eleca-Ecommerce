<?php
session_start();
include('db_connection/conn.php');

// إذا كان المستخدم قد قام بتسجيل الدخول:
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:user_login.php');
    exit();
}

// معالجة تحديث الفئة
if (isset($_POST['update_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $category_image = $_FILES['category_image']['name'];

    if ($category_image) {
        // تحميل الصورة
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["category_image"]["name"]);
        move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file);

        // تحديث الفئة مع الصورة الجديدة
        $query = "UPDATE category SET name = :name, image = :image WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $category_name);
        $stmt->bindParam(":image", $category_image);
        $stmt->bindParam(":id", $category_id, PDO::PARAM_INT);
    } else {
        // تحديث الفئة بدون تغيير الصورة
        $query = "UPDATE category SET name = :name WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $category_name);
        $stmt->bindParam(":id", $category_id, PDO::PARAM_INT);
    }

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "خطأ في تحديث السجل: " . $stmt->errorInfo()[2];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <title>admin dashboard</title>
</head>

<body>
    <div class="d-flex">
        <div id="sidebar">
            <button class="btn text-white sidebarHeaderbutton">Dashboard</button>
            <a href="../UserCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\man.png" alt="" class="me-1"> Users</button></a>
            <a href="#"> <button class="btn CustomSidebarButtons text-white"><img src="../flaticon/categories.png"
                        alt="" class="me-1"> Categories</button></a>
            <a href="../ProductCRUDS\index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\product.png" alt="" class="me-1"> Products</button></a>
            <a href="../CouponCRUDS"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\coupon.png" alt="" class="me-1"> Coupons</button></a>
            <a href="../OrderRU/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\received.png" alt="" class="me-1"> Orders</button></a>
        </div>
        <div class="page-content">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="">
                        <button class="btn fw-bold">ELECA SHOP</button>
                    </div>
                    <div class="me-5 pe-4">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="../flaticon/profile.png" alt="Profile">
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="../components/profile.php" class="dropdown-item">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a href="../components/logout.php?logout=yes" class="logout-btn btn">Log out</a> </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <?php
            if (isset($_GET['message'])) {
                echo "<div style='background-color:green'> {$_GET['message']}</div>";
            }
            ?>

            <main>
                <div class="container mt-5">
                    <div class="mb-5">
                        <h1>Category Information</h1>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Add Category</button>
                    </div>

                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Id</th>
                                <th scope="col" class="text-center">name</th>
                                <th scope="col" class="text-center">image</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM `category`";
                            $categories = $conn->query($query);
                            foreach ($categories as $category) {
                                echo "<tr>
                                    <td class='text-center'>{$category['id']}</td>
                                    <td class='text-center'>{$category['name']}</td>
                                    <td class='text-center'>
                                        <img src='" . (!empty($category['image']) ? 'images/' . $category['image'] : 'images/default.png') . "' width='100' height='60'>
                                    </td>
                                    <td class='text-center'>
                                        <button class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editModal' 
                                            data-id='{$category['id']}' 
                                            data-name='{$category['name']}' 
                                            data-image='{$category['image']}'>
                                            <i class='fa-solid fa-pen-to-square'></i> Edit
                                        </button>
                                        |
                                        <button type='button' class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#deleteCategoryModal' 
                                                 data-id='{$category['id']}'> Delete
                                        </button>
                                    </td>
                                </tr>";

                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Category Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="insert_data/insert_new.php" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="user-name" class="col-form-label"> Name:</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name">
                                    </div>
                                    <div class="mb-3">
                                        <input type="file" class="form-control" name="category_image">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="add_category" value="Add">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Category Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="index.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="category_id" id="edit_category_id">
                                    <div class="mb-3">
                                        <label for="edit_category_name" class="col-form-label">Name:</label>
                                        <input type="text" class="form-control" id="edit_category_name"
                                            name="category_name">
                                    </div>
                                    <div class="mb-3">
                                        <input type="file" class="form-control" name="category_image">
                                    </div>
                                    <div id="current_image" class="mb-3" style="display:none;">
                                        <label>Current Image:</label>
                                        <img id="current_image_preview" width="100" height="60" alt="Current Image">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="update_category"
                                            value="Update">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Event listener for edit button click
        document.addEventListener('DOMContentLoaded', function () {
            var editButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#editModal"]');
            editButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var id = button.getAttribute('data-id');
                    var name = button.getAttribute('data-name');
                    var image = button.getAttribute('data-image');

                    // Fill the modal with the current data
                    document.getElementById('edit_category_id').value = id;
                    document.getElementById('edit_category_name').value = name;

                    // Handle the image URL and show current image preview
                    if (image) {
                        document.getElementById('current_image_preview').src = 'images/' + image;
                        document.getElementById('current_image').style.display = 'block';
                    } else {
                        document.getElementById('current_image').style.display = 'none';
                    }
                });
            });
        });
    </script>









    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this category?</p>
                    <form action="delete_data/delete_page.php" method="post">
                        <input type="hidden" name="category_id" id="delete_category_id" value="">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-danger" name="delete_category" value="Delete">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const deleteCategoryModal = document.getElementById('deleteCategoryModal');
        deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const categoryId = button.getAttribute('data-id'); // Get category ID from button

            // Populate the hidden input field
            document.getElementById('delete_category_id').value = categoryId;
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>