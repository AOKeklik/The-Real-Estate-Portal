<?php
    include("./layout_config.php");

    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["faq_id"])){
        try{

            $faq_id=htmlspecialchars(trim(base64_decode($_POST["faq_id"])));

            if(!isset($_SESSION["admin"]))
                throw new Exception("Unauthorized action request has been denied.");

            if(filter_var($faq_id,FILTER_VALIDATE_INT) === false)
                throw new Exception("The requested FAQ could not be found.");

            if(empty($errors)){
                try{
                    $stmt=$pdo->prepare("
                        DELETE FROM
                            faqs
                        WHERE
                            id=?
                        LIMIT
                            1
                    ");

                    if(!$stmt->execute([$faq_id]))
                        throw new PDOException("An error occurred while deleting the FAQ. Please try again.");

                    echo json_encode(["success"=>["message"=>"The FAQ has been successfully deleted."]]);
                }catch(PDOException $err){
                    echo json_encode(["error"=>["message"=>$err->getMessage()]]);
                }
            }
        } catch(Exception $err){
            echo json_encode(["error"=>["message"=>$err->getMessage()]]);
        }
    }
?>