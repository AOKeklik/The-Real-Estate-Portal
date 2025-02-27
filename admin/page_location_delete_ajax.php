<?php
    include "./layout_config.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."locations");
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])){
        try {
            $id = htmlspecialchars(trim($_POST["id"]));

            $stmt = $pdo->prepare("select * from locations where id=?");
            $stmt->execute([$id]);
            $location = $stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() == 0)
                throw new PDOException("The selected location is not available.");

            $stmt = $pdo->prepare("select * from properties where location_id=?");
            $stmt->execute([$id]);

            if($stmt->rowCount() > 0)
                throw new PDOException("This record cannot be deleted because it is linked to other data!");

            $stmt = $pdo->prepare("delete from locations where id=?");
            $stmt->execute([$id]);

            if($stmt->rowCount() == 0)
                throw new PDOException("The location could not be deleted or does not exist!");

            $path = "../public/uploads/location/";

            if(is_file($path.$location["photo"]))
                unlink($path.$location["photo"]);

            unset($_POST["id"]);

            echo json_encode(["success"=>["message"=>"The location deleted successfully!"]]);
        } catch (PDOException $err) {
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }
?>