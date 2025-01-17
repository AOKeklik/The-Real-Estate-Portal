<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["customer_id"])){
        try{
            $customer_id=htmlspecialchars(trim(base64_decode($_POST["customer_id"])));

            if(!isset($_SESSION["admin"]))
                throw new PDOException("You do not have permission to perform the delete operation.");
            
            if(filter_var($customer_id, FILTER_VALIDATE_INT) === false)
                throw new PDOException("The specified customer could not be found.");

            $stmt=$pdo->prepare("
                SELECT
                    *
                FROM
                    customers
                WHERE
                    id=?
                LIMIT 
                    1
            ");
            $stmt->execute([$customer_id]);
            $customer=$stmt->fetch(pdo::FETCH_ASSOC);

            if($stmt->rowCount() == 0)
                throw new PDOException("The specified customer could not be found.");

            $stmt=$pdo->prepare("
                DELETE FROM
                    message_replies
                WHERE
                    customer_id=?
            ");
            $stmt->execute([$customer_id]);

            $stmt=$pdo->prepare("
                DELETE FROM
                    messages
                WHERE
                    customer_id=?
            ");
            $stmt->execute([$customer_id]);

            $stmt=$pdo->prepare("
                DELETE FROM
                    wishlists
                WHERE
                    customer_id=?
            ");
            $stmt->execute([$customer_id]);

            if(is_file("../public/uploads/customer/".$customer["photo"]))
                unlink("../public/uploads/customer/".$customer["photo"]);

            $stmt=$pdo->prepare("
                DELETE FROM
                    customers
                WHERE
                    id=?
                LIMIT 1
            ");
            $stmt->execute([$customer_id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The specified customer could not be found.");

            echo json_encode(["success"=>["message"=>"The specified customer was successfully deleted."]]);
        }catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }