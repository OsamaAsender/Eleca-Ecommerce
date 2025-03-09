<?php
// echo "delete page";
// include('../db_connection/conn.php');
// if(isset($_GET['id'])){
//    $id= $_GET['id'];
//     try{
//         echo $id;
//         $query="DELETE FROM `user` WHERE `id`=:id";
//         $statment=$conn->prepare($query);
//         $statment->bindParam(':id',$id);
//         $statment->execute();
//         header('location:../index.php?message=delete sucesssfuly');



//     }catch(PDOEception $err){
//         echo $err->getMessage();
//     }
// }





?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if ($id) {
        require_once '../db_connection/conn.php'; // Your database connection file

        // Prepare delete query
        $query = "DELETE FROM `user` WHERE `id` = :id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $statement->execute();
            header('Location: ../index.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid ID.";
    }
} else {
    echo "Invalid request method.";
}
?>
