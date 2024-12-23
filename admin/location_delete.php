<?php
    include "./layout_config.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."locations");
        exit();
    }

    if(!isset($_GET["id"])) {
        $_SESSION["error"] = "The selected location is not available.";
        header("Location: ".ADMIN_URL."locations");
        exit();
    }

    $id = $_GET["id"];

    try {
        $sql = "select * from locations where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $location = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() == 0)
            throw new PDOException("The selected location is not available.");

    } catch (PDOException $err) {
        echo $err->getMessage();
    }

    try {
        $sql = "delete from locations where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();

        if($stmt->rowCount() == 0)
            throw new PDOException(json_encode(["error"=>"The location could not be deleted or does not exist!"]));

        $path = "../public/uploads/location/";

        if(is_file($path.$location["photo"]))
            unlink($path.$location["photo"]);

        echo json_encode(["success"=>"The location deleted successfully!"]);

    } catch (PDOException $err) {
        echo $err->getMessage();
    }
?>