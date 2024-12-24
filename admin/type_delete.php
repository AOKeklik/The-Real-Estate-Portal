<?php 
    include "./layout_config.php";

    if(!isset($_SESSION["admin"])) {
        header("Location: ".ADMIN_URL."login");
        exit();
    }

    if(!isset($_GET["id"])) {
        header("Location: ".ADMIN_URL."types");
        exit();
    }

    $id = $_GET["id"];

    try {
        $sql = "delete from types where id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id",$id);
        $stmt->execute();

        if($stmt->rowCount() == 0)
            throw new PDOException("The type could not be deleted or does not exist!");

        echo json_encode(["success"=>"The type deleted successfully!"]);
    } catch (PDOException $err){
        echo json_encode(["error"=>$err->getMessage()]);
    }
?>

