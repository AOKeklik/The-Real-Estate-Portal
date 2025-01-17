<?php 
    include "./layout_config.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["id"])) {
        $_SESSION["error"] = "The selected package is not available.";
        header("Location: ".ADMIN_URL."packages");
        exit();
    }

    $id = $_GET["id"];

    try {
        $sql = "delete from packages where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id",$id,PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt->rowCount() == 0)
            throw new PDOException("The package could not be deleted or does not exist!");

        echo json_encode(["success" => "The package deleted successfully."]);
    } catch (PDOException $err) {
        echo json_encode(["error" => $err->getMessage()]);
    }
?>