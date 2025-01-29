<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] && isset($_POST["status"])){
        try{
            $status=htmlspecialchars(trim(base64_decode($_POST["status"])));

            if(!isset($_SESSION["admin"]))
                throw new Exception("Unauthorized access. Please log in as an admin.");

            if(filter_var($status,FILTER_VALIDATE_INT) === false)
                throw new Exception("Invalid data provided.");

            try{
                $stmt=$pdo->prepare("
                    UPDATE
                        terms
                    SET
                        status=?
                    LIMIT
                        1
                ");
                
                if(!$stmt->execute([$status]))
                    throw new PDOException("Failed to update terms of Use status.");

                echo json_encode(["success"=>["message"=>"Terms of Use status has been successfully updated."]]);
            } catch(PDOException $err){
                echo json_encode(["error"=>["message"=>$err->getMessage()]]);
            }
        }catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }