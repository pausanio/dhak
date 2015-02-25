<?php
$routes = array('routes' => $docRoutes);
if(isset($currentIndex)){
    $routes['current'] = $currentIndex;
}
echo json_encode($routes);
