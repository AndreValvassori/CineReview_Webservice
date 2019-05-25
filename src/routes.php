<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get('/mensagem/[{msg}]', function ($request, $response, $args) {
        
        
                $file_db = new PDO('sqlite:CineReviewDB.db');      
                $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
                $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY, 
                    title TEXT, 
                    message TEXT, 
                    time INTEGER)");
                


                $insert = "INSERT INTO messages (title, message, time) 
                        VALUES (:title, :message, :time)";

                $stmt = $file_db->prepare($insert);

                $objDateTime = '22/05/2019 13:07';
                
                $stmt->bindParam(':title', $args['msg']);
                $stmt->bindParam(':message', $args['msg']);
                $stmt->bindParam(':time', $objDateTime);

                $stmt->execute();

                //$result = $file_db->query('SELECT * FROM messages');

                $statement = $file_db->prepare("SELECT * FROM messages");
                $statement->execute();
                $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                $json = json_encode($results);
                
                $file_db = null;

                return $this->response->withJson($json, null, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

            });

};
