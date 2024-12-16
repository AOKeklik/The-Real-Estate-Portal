<?php
    require_once 'vendor/autoload.php';


    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
?>
<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>

        <div
            class="container"
        >
            
            <?php

                $host = $_ENV["DB_HOST"];
                $port = $_ENV["DB_PORT"];
                $dbname = $_ENV["DB_DATABASE"];
                $user = $_ENV["DB_USERNAME"];
                $pass = $_ENV["DB_PASSWORD"];

                try{
                    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port;charset=utf8;",$user,$pass);
                    $pdo->setAttribute(pdo::ATTR_ERRMODE,pdo::ERRMODE_EXCEPTION);
                } catch(PDOException $err) {
                    echo "PDO General: ".$err->getMessage();
                }

                function getAll ($pdo, $limit=10,$offset=0) {
                    try {
                        $sql = "
                            select customers.contactFirstName as customer_name, 
                            employees.firstName as employee_name
                            from customers 
                            join employees 
                            on customers.salesRepEmployeeNumber=employees.employeeNumber 
                            limit :limit 
                            offset :offset
                        ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
                        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
            
                        if($stmt->execute())
                            return $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        return [];
                    } catch (PDOException $err) {
                        error_log("PDO Getall: ".$err->getMessage());
                        return [];
                    }
                }

                foreach(getAll ($pdo) as $customer) {
                    echo $customer["customer_name"] . "<br>" . $customer["employee_name"] . "<br><br>";
                }
                    
            ?>

        </div>
        

        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
