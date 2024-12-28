<?php
    include "./layout_config.php";

    if(!isset($_SESSION["agent"])){
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    if(
        $_SERVER["REQUEST_METHOD"] === "POST" && 
        isset($_POST["id"])
    ) {
        try {
            $id = htmlspecialchars(trim($_POST["id"]));

            $stmt = $pdo->prepare("select * from property_photos where id=?");
            $stmt->execute([$id]);
            $photo = $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 0)
                throw new PDOException("The photo could not be deleted or does not exist!");

            $stmt = $pdo->prepare("delete from property_photos where id=?");
            $stmt->execute([$id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The photo could not be deleted or does not exist!");

            $path = "./public/uploads/property/photo/";

            if(is_file($path.$photo["photo"]))
                unlink($path.$photo["photo"]);

            unset($_POST["form"]);
            unset($_POST["id"]);
            
            echo json_encode(["success"=>["message"=>"The photo deleted successfully!"]]);
        }catch(PDOException $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }