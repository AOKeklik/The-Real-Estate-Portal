<?php
    include "./layout_config.php";

    try {
        if(
            !isset($_SESSION["customer"]) ||
            !isset($_POST["property_id"]) ||
            !isset($_POST["customer_id"])
        )
            throw new PDOException("Missing or incorrect information. Wishlist update failed.");

        $property_id=htmlspecialchars(trim(base64_decode($_POST["property_id"])));
        $customer_id=htmlspecialchars(trim(base64_decode($_POST["customer_id"])));

        if(empty($property_id) || empty($customer_id))
            throw new PDOException("Missing or incorrect information. Wishlist update failed.");

        $stmt=$pdo->prepare("
            DELETE FROM
                wishlists
            WHERE
                property_id=?
            AND
                customer_id=?
        ");
        $stmt->execute([$property_id,$customer_id]);
        
        if($stmt->rowCount() == 0)  
            throw new PDOException("The item is not in your wishlist!");

        echo json_encode(["success"=>["message"=> "The item has been successfully removed from your wishlist."]]);
    } catch(PDOException $err){
        echo json_encode(["error"=>["message"=> $err->getMessage()]]);
    }

    