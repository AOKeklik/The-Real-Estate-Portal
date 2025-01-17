<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["order_id"])){
        try{
            $order_id=htmlspecialchars(trim(base64_decode($_POST["order_id"])));

            if(!isset($_SESSION["admin"]))
                throw new PDOException("Deletion failed due to unauthorized access.");
            
            if($order_id==="")
                throw new PDOException("The specified order could not be found. Please try again later.");

            $stmt=$pdo->prepare("
                DELETE FROM
                    orders
                WHERE
                    id=?
            ");
            $stmt->execute([$order_id]);
            
            if($stmt->rowCount() == 0)
                throw new PDOException("The specified order could not be found. Please try again later.");

            echo json_encode(["success"=>["message"=>"Order successfully deleted."]]);
        } catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }