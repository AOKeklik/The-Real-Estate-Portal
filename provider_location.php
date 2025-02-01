<?php

    class ProviderLocation{
        private static $locations=[];
        private static $usedLocations=[];

        static function load($pdo){
            try{
                $stmt=$pdo->prepare("
                    SELECT
                        *
                    FROM
                        locations
                ");
                $stmt->execute();
                $results = $stmt->fetchAll(pdo::FETCH_ASSOC);

                foreach($results as $row){
                    self::$locations[] = $row;
                }  
                
                $stmt=$pdo->prepare("
                    SELECT
                        locations.*,
                        COUNT(properties.id) as property_count
                    FROM
                        locations
                    INNER JOIN
                        properties on properties.location_id=locations.id
                    INNER JOIN
                        orders on orders.agent_id=properties.agent_id
                    WHERE
                        now() between orders.purchase_date AND orders.expire_date 
                    AND 
                        orders.currently_active=?
                    GROUP BY
                        locations.id
                    ORDER BY
                        locations.name ASC
                ");
                $stmt->execute([1]);
                $results = $stmt->fetchAll(pdo::FETCH_ASSOC);

                foreach($results as $row){
                    self::$usedLocations[] = $row;
                }
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }
        static function getAll(){
            return self::$locations ?? false;
        }
        static function getUsed(){
            return self::$usedLocations ?? false;
        }
    }