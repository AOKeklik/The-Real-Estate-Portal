<?php
    include "./layout_config.php";

    if(!isset($_SESSION["admin"])){
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])){
        try {
            $id = htmlspecialchars(trim($_POST["id"]));

            $stmt=$pdo->prepare("select * from properties where find_in_set(?, amenities) limit 1");
            $stmt->execute([$id]);

            if($stmt->rowCount() > 0)
                throw new PDOException("This record cannot be deleted because it is linked to other data!");
            
            $stmt = $pdo->prepare("delete from amenities where id=?");
            $stmt->execute([$id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The amenity could not be deleted or does not exist!");

            echo json_encode(["success"=>["message"=>"The amenity deleted successfully!"]]);
        } catch (PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }
?>