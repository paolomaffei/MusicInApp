<?php
    /**
     * Step 1: Require the Slim PHP 5 Framework
     *
     * If using the default file layout, the `Slim/` directory
     * will already be on your include path. If you move the `Slim/`
     * directory elsewhere, ensure that it is added to your include path
     * or update this file path as needed.
     */
    require 'Slim/Slim.php';
    require_once 'parse.php';
   
    
    /**
     * Step 2: Instantiate the Slim application
     *
     * Here we instantiate the Slim application with its default settings.
     * However, we could also pass a key-value array of settings.
     * Refer to the online documentation for available settings.
     */
    $app = new Slim();
    $app->get('/track/:idTrack', 'getTrack'); //DONE
    $app->get('/tracks/all',  'getAllTracks');//DONE not checked
    
    
    
    $app->run();
    
 $parse = new parseRestClient(array(
	'appid' => '3oz87vs9DLzbaNPdd2Ih4YhJteoFaQGGNbC4i22D',
	'restkey' => 'QR9BJyJAJHANU6wykltGKVXubp06YOHzNEzCJhal'
));
    
    function getTrack($idTrack) {
        
        $params = array(
    'className' => 'Track',
    'objectId' => $idTrack
);

$request = $parse->get($params);
echo '{"track": ' . json_encode($request) . '}';

}

       
    
    function getTracks() {
           $params = array(
    'className' => 'Track'
);

$request = $parse->get($params);
echo '{"tracks": ' . json_encode($request) . '}';
    }
    
    
            
            
           
    
    
    ?>
