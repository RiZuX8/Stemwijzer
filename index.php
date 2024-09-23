<?php
// Starten van een eenvoudige router
// Haal de huidige URI op
$uri = trim($_SERVER['REQUEST_URI'], '/');
$uriSegments = explode('/', $uri);
if ($uriSegments[0] == 'products' && isset($uriSegments[1])) {
    // Haal het product-ID op uit de URL
    $productId = htmlspecialchars($uriSegments[1]);
}
// Routes defineren
switch ($uriSegments[0]) {
    case '':
        require 'views/home.php';
        break;
    case 'about':
        require 'views/about.php';
        break;
    case 'products':
        require 'views/products.php';
        break;
    default:
        require 'views/404.php';
        break;
}
