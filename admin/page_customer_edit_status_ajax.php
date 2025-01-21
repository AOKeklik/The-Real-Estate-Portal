<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["customer_id"]) && isset($_POST["status"])){
        try{
            $customer_id=htmlspecialchars(trim(base64_decode($_POST["customer_id"])));
            $status=htmlspecialchars(trim(base64_decode($_POST["status"])));

            if(!isset($_SESSION["admin"]))
                throw new PDOException("You do not have permission to perform the update operation.");
            
            if(filter_var($customer_id, FILTER_VALIDATE_INT) === false || filter_var($status, FILTER_VALIDATE_INT) === false)
                throw new PDOException("The specified customer could not be found.");

            $stmt=$pdo->prepare("
                UPDATE 
                    customers
                SET 
                    status=?
                WHERE
                    id=?
                LIMIT 1
            ");
            $stmt->execute([$status,$customer_id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The specified customer could not be found.");

            echo json_encode(["success"=>["message"=>"The specified customer was successfully deleted."]]);
        }catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }