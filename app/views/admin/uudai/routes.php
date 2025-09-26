<?php
// app/routes.php hoặc routes.php

$router->get('/uudai', 'UuDaiController@index');
$router->get('/uudai/create', 'UuDaiController@create');
$router->post('/uudai/store', 'UuDaiController@store');
$router->get('/uudai/edit', 'UuDaiController@edit');
$router->post('/uudai/update', 'UuDaiController@update');
$router->post('/uudai/delete', 'UuDaiController@delete');
?>