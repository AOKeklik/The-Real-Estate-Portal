<?php

    class ProviderSetting{
        private static $settings=[];

        static function load($pdo){
            try{
                $stmt=$pdo->prepare("
                    SELECT
                        *
                    FROM
                        settings
                    LIMIT 
                        1
                ");
                $stmt->execute();
                $result = $stmt->fetch(pdo::FETCH_ASSOC);

                foreach($result as $key=>$val){
                    self::$settings[$key] = $val;
                }                
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }

        static function get($key){
            try{
                return self::$settings[$key] ?? false;
            }catch(PDOException $err){
                $error_message=$err->getMessage();
            }
        }
    }