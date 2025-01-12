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

        $stmt=$pdo->prepare("
            SELECT
                *
            FROM
                wishlists
            WHERE
                property_id=?
            AND
                customer_id=?    
            LIMIT
                1
        ");
        $stmt->execute([$property_id,$customer_id]);

        if($stmt->rowCount() > 0)
            throw new PDOException("This item is already in your wishlist.");

        if(empty($property_id) || empty($customer_id))
            throw new PDOException("Missing or incorrect information. Wishlist update failed.");

        $stmt=$pdo->prepare("
            INSERT INTO wishlists
                (property_id,customer_id)
            VALUES
                (?,?)
        ");
        $stmt->execute([$property_id,$customer_id]);
        
        if($stmt->rowCount() == 0)  
            throw new PDOException("An error occurred during the addition process. Please try again!");

        echo json_encode(["success"=>["message"=> "The item has been successfully added to the wishlist."]]);
    } catch(PDOException $err){
        echo json_encode(["error"=>["message"=> $err->getMessage()]]);
    }

    