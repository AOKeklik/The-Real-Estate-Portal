<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agent_id"]) && isset($_POST["status"])){
        try{
            $agent_id=htmlspecialchars(trim(base64_decode($_POST["agent_id"])));
            $status=htmlspecialchars(trim(base64_decode($_POST["status"])));

            if(!isset($_SESSION["admin"]))
                throw new PDOException("You do not have permission to perform the update operation.");
            
            if(filter_var($agent_id, FILTER_VALIDATE_INT) === false || filter_var($status, FILTER_VALIDATE_INT) === false)
                throw new PDOException("The specified agent could not be found.");

            $stmt=$pdo->prepare("
                UPDATE 
                    agents
                SET 
                    status=?
                WHERE
                    id=?
                LIMIT 1
            ");
            $stmt->execute([$status,$agent_id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The specified agent could not be found.");

            echo json_encode(["success"=>["message"=>"The specified agent was successfully deleted."]]);
        }catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }