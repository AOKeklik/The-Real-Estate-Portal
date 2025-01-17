<?php
    include "./layout_config.php";

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["agent_id"])){
        try{
            $agent_id=htmlspecialchars(trim(base64_decode($_POST["agent_id"])));

            if(!isset($_SESSION["admin"]))
                throw new PDOException("You do not have permission to perform the delete operation.");
            
            if(filter_var($agent_id, FILTER_VALIDATE_INT) === false)
                throw new PDOException("The specified agent could not be found.");

            $stmt=$pdo->prepare("
                SELECT
                    *
                FROM
                    agents
                WHERE
                    id=?
                LIMIT 
                    1
            ");
            $stmt->execute([$agent_id]);
            $agent=$stmt->fetch(pdo::FETCH_ASSOC);

            if($stmt->rowCount() == 0)
                throw new PDOException("The specified agent could not be found.");

            $stmt=$pdo->prepare("
                DELETE FROM
                    message_replies
                WHERE
                    agent_id=?
            ");
            $stmt->execute([$agent_id]);

            $stmt=$pdo->prepare("
                DELETE FROM
                    messages
                WHERE
                    agent_id=?
            ");
            $stmt->execute([$agent_id]);

            $stmt=$pdo->prepare("
                DELETE FROM
                    orders
                WHERE
                    agent_id=?
            ");
            $stmt->execute([$agent_id]);

            $stmt = $pdo->prepare("
                select 
                    * 
                FROM
                    property_photos
                WHERE
                    property_id
                IN
                    (SELECT id FROM properties WHERE agent_id=?)
            ");
            $stmt->execute([$agent_id]);
            $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $path = "../public/uploads/property/photo/";
            foreach($photos as $photo) {
                if(is_file($path.$photo["photo"]))
                    unlink($path.$photo["photo"]);
            }

            $stmt=$pdo->prepare("
                DELETE FROM
                    property_photos
                WHERE
                    property_id
                IN
                    (SELECT id FROM properties WHERE agent_id=?)
            ");
            $stmt->execute([$agent_id]);

            $stmt=$pdo->prepare("
                DELETE FROM
                    property_videos
                WHERE
                    property_id
                IN
                    (SELECT id FROM properties WHERE agent_id=?)
            ");
            $stmt->execute([$agent_id]);

            $stmt = $pdo->prepare("
                select 
                    * 
                FROM
                    properties
                WHERE
                    agent_id=?
            ");
            $stmt->execute([$agent_id]);
            $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $path = "../public/uploads/property/";
            foreach($properties as $property) {
                if(is_file($path.$property["featured_photo"]))
                    unlink($path.$property["featured_photo"]);
            }

            $stmt=$pdo->prepare("
                DELETE FROM
                    properties
                WHERE
                    agent_id=?
            ");
            $stmt->execute([$agent_id]);

            $path = "../public/uploads/agent/";
            if(is_file($path.$agent["photo"]))
                unlink($path.$agent["photo"]);

            $stmt=$pdo->prepare("
                DELETE FROM
                    agents
                WHERE
                    id=?
                LIMIT 1
            ");
            $stmt->execute([$agent_id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The specified agent could not be found.");

            echo json_encode(["success"=>["message"=>"The specified agent was successfully deleted."]]);
        }catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }