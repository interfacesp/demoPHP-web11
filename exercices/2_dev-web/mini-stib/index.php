<?php
    try {

        $mysqlClient = new PDO(
            sprintf('mysql:host=%s;dbname=%s;port=%s', 
            "localhost",
            "mini-stib",
            3306),
            "root",
            "root"
        );
        $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $mysqlSelEtMiel = new PDO(
            sprintf('mysql:host=%s;dbname=%s;port=%s', 
            "localhost",
            "sel-et-miel",
            3306),
            "root",
            "root"
        );
        $mysqlSelEtMiel->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    } catch(Exception $exception) {
        die('Oops, il y a eu problème : '.$exception->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Stib</title>
</head>
<body>

        <?php 

            echo "<h4>Toutes les Lignes</h4>";
            
            $sqlQueryLines = "SELECT * FROM line";
            $statement = $mysqlClient->prepare($sqlQueryLines); 
            $statement->execute();

            $result = $statement->fetchAll(); 


            foreach($result as $aLine){

                    echo "<p>";
                    echo $aLine['id']; 
                    echo $aLine['line_name']; 
                    echo $aLine['distance']; 
                    echo "</p>";
                
            }

            echo "<h4>Nombre de Lignes</h4>";

            /**
             * Comptage du nombre de lignes 
             */
            $queryCount = "SELECT count(*) FROM line";
            $statement = $mysqlClient->prepare($queryCount); 
            $statement->execute();  

            $resultCount = $statement->fetchAll(); 
    
            // echo "<pre>";
            // print_r($resultCount);
            // echo "</pre>";

            echo "Nombre Lignes ". $resultCount[0]['count(*)'];
            
            
            echo "<h4>Lignes et leurs arrêts</h4>";

            /**
             * Quelles sont les Lignes ainsi que leurs Arrêts
             * 
             */

             $queryLinesAndStops= "
                                SELECT * 
                                FROM line,stop 
                                WHERE line.id = stop.id_line
                            ";

            $statement = $mysqlClient->prepare($queryLinesAndStops);
            $statement->execute();
            $resultLinesStops = $statement->fetchAll();

            $nbResults = count($resultLinesStops);
            for($i = 0; $i < $nbResults; $i++){

               echo  "<p> Ligne: ". $resultLinesStops[$i]['line_name']. "</p>";
               echo  " <p>Distance Ligne: ". $resultLinesStops[$i]['distance']. "</p>";
               echo "<p>Stop: ". $resultLinesStops[$i]['stop_name']."</p>";
            }



            echo "<h4>Sel et Miel - Demo utilisation autre connexion DB</h4>";

            $queryGaufres ="SELECT * from GAUFRES"; 
            $statement = $mysqlSelEtMiel->prepare($queryGaufres);
            $statement->execute();
            $resultGaufres = $statement->fetchAll();

            print_r($resultGaufres); 



        ?>


    

    



</body>
</html>