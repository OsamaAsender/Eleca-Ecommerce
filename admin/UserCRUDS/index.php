<?php
session_start();
include('db_connection/conn.php');

$admin_id = $_SESSION['admin_id'] ?? null;

if($admin_id){
        
}

// Handle Update User
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['user_name'];
    $email = $_POST['user_email'];
    $password = $_POST['user_pwd'];
    $phone = $_POST['user_phone'];
    $address = $_POST['user_address'];
    $role = $_POST['user_role'];

    try {
        if (!empty($password)) {
            $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
            $update_user = $conn->prepare("UPDATE `user` SET name=?, email=?, password=?, phone_number=?, address=?, role=? WHERE id=?");
            $update_user->execute([$username, $email, $hashed_pwd, $phone, $address, $role, $user_id]);
        } else {
            $update_user = $conn->prepare("UPDATE `user` SET name=?, email=?, phone_number=?, address=?, role=? WHERE id=?");
            $update_user->execute([$username, $email, $phone, $address, $role, $user_id]);
        }
        
        // Redirect with both message and original ID
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $user_id . '&message');
        exit();
    } catch (PDOException $e) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $user_id . '&error=Update+failed: ' . urlencode($e->getMessage()));
        exit();
    }
}

// Handle Delete User
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    try {
        $delete_user = $conn->prepare("DELETE FROM `user` WHERE id = ?");
        $delete_user->execute([$delete_id]);
        header('Location: ' . $_SERVER['PHP_SELF'] . '?message=User+deleted+successfully');
        exit();
    } catch (PDOException $e) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=Delete+failed: ' . urlencode($e->getMessage()));
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <!-- sidebar -->

    <div class="d-flex">
        <div id="sidebar">
            <button class="btn text-white sidebarHeaderbutton">Dashboard</button>
            <a href="../UserCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\man.png" alt="" id="SadminPrio" class="me-1"> Users</button></a>
            <a href="../CategoryCRUDS/index.php"> <button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon/categories.png" alt="" class="me-1"> Categories</button></a>
            <a href="../ProductCRUDS\index.php">
                <button class="btn CustomSidebarButtons text-white"><img src="../flaticon\product.png" alt=""
                        class="me-1">
                    Products</button>
            </a>


            <a href="../CouponCRUDS/index.php"><button class="btn CustomSidebarButtons text-white"><img src="../flaticon\coupon.png" alt=""
                        class="me-1"> Coupons</button></a>
            <a href="../OrderRU/index.php"><button class="btn CustomSidebarButtons text-white"><img
                        src="../flaticon\received.png" alt="" class="me-1"> Orders</button></a>


        </div>
        <div class="page-content">






            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid d-flex justify-content-between">
                    <div class="d-flex">
                        <button class="btn fw-bold">ELECA SHOP</button>
                        
                    </div>


                    <div class="me-5 pe-4">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                            <li class="nav-item dropdown">
                                <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="../flaticon/profile.png" alt="asdfsadf">
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href='../components/profile.php' class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a href="../components/logout.php?logout=yes" class="logout-btn btn">Log out</a></li>
                                </ul>
                            </li>
                        </ul>

                    </div>

                </div>

            </nav>

            <?php

            include('db_connection/conn.php');

            if (isset($_GET['message'])) {
                echo "<div style='background-color:green'> {$_GET['message']}</div>";
            }
            ?>

            <?php

            include('db_connection/conn.php');

            if (isset($_GET['message'])) {
                echo "<div style='background-color:green'> {$_GET['message']}</div>";
            }
            ?>


            <main>


                <!-- TABLE -->

                <div class="container mt-5">
                    <div class="mb-5">
                        <h1>Users Information</h1>
                        <!-- FIX: Updated data attributes for Bootstrap 5 -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            Add User
                        </button>
                    </div>

                    <table class="table table-striped table-hover ">
                        <thead>
                            <tr>
                                <!-- <th scope="col">id</th> -->
                               
                                <th scope="col" class="text-center">Name</th>
                                <th scope="col" class="text-center">Email</th>
                                <th scope="col" class="text-center">Phonenumber</th>
                                <th scope="col" class="text-center">Address</th>
                                <th scope="col" class="text-center">Role</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM `user`";
                            $users = $conn->query($query);
                            // print_r ($users);
                            foreach ($users as $user) {
                                echo "<tr>
                                   
                                    <td class='text-center'>{$user['name']}</td>
                                    <td class='text-center'>{$user['email']}</td>
                                    <td class='text-center'>{$user['phone_number']}</td>
                                    <td class='text-center'>{$user['address']}</td>
                                    <td class='text-center'>{$user['role']}</td>
                                    <td class='text-center'>
                                        <button type='button' class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#updateModal' 
                                            onclick=\"setUpdateModalData(
                                                {$user['id']}, 
                                                '{$user['name']}', 
                                                '{$user['email']}', 
                                                '{$user['phone_number']}', 
                                                '{$user['address']}', 
                                                '{$user['role']}'
                                            )\">
                                            Edit
                                        </button>
             
                                        |
                                        
                                        <!-- Delete Button -->
                                        <button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                                            onclick=\"setDeleteId({$user['id']})\">
                                            Delete
                                        </button>
                                    </td>
                                </tr>";
                            }
                            
                            ?>
                        </tbody>
                    </table>
                </div>


                <!-- add user modal -->
                <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                                <!-- FIX: Bootstrap 5 requires 'btn-close' instead of 'close' -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form action="insert_data/insert_new.php" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="user_name" class="col-form-label"> Name:</label>
                                        <input type="text" class="form-control" id="user_name" name="user_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="user_email" class="col-form-label"> Email:</label>
                                        <input type="text" class="form-control" id="user_email" name="user_email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="user_pwd" class="col-form-label"> Password:</label>
                                        <input type="password" class="form-control" id="user_pwd" name="user_pwd">
                                    </div>
                                    <div class="mb-3">
                                        <label for="user_phone" class="col-form-label"> Phonenumber:</label>
                                        <input type="text" class="form-control" id="user_phone" name="user_phone">
                                    </div>
                                    <div class="mb-3">
                                        <label for="user_address" class="col-form-label"> Address:</label>
                                        <input type="text" class="form-control" id="user_address" name="user_address">
                                    </div>

                                    <div class="mb-3">
                                        <label for="user-name" class="col-form-label"> Role:</label>
                                        <select name="user_role" id="user_role">
                                            <option value="user">user</option>
                                            <option value="admin">admin</option>
                                            <option value="super admin">super admin</option>
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="add_user" value="Add">
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>




                <!-- Button trigger modal -->


                <!-- Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete
                                    <span style="font-weight:bold;color:red;"><?= $user['name']; ?></span>
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-footer">
                                <form action="delete_data/delete_page.php" method="post">
                                    <input type="hidden" name="id" id="deleteId">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <input type="submit" class="btn btn-danger" name="delete_user" id="delete_user"
                                        value="Delete" onclick="setDeleteId(<?= $user['id']; ?>)">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Update Modal -->
                <div class="modal fade" id="updateModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="updateForm" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" id="userId">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="user_name" id="updateName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="user_email" id="updateEmail" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password (leave blank to keep current)</label>
                                        <input type="password" class="form-control" name="user_pwd" id="updatePassword">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="user_phone" id="updatePhone" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="user_address" id="updateAddress" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select class="form-select" name="user_role" id="updateRole">
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                            <option value="super admin">Super Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="update_user" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>





    </div>

    <script>
    function setUpdateModalData(id, name, email, phone, address, role) {
        document.getElementById('userId').value = id;
        document.getElementById('updateName').value = name;
        document.getElementById('updateEmail').value = email;
        document.getElementById('updatePhone').value = phone;
        document.getElementById('updateAddress').value = address;
        
        const roleSelect = document.getElementById('updateRole');
        for (let option of roleSelect.options) {
            if (option.value === role) {
                option.selected = true;
                break;
            }
        }
    }

    function setDeleteId(id) {
        document.getElementById('deleteId').value = id;
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>