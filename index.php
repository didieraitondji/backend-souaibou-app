<?php

include_once("./api/models.php");
include_once("./api/Users.php");
include_once("./api/UsersAuth.php");
include_once("./api/Categories.php");
include_once("./api/Commandes.php");
include_once("./api/Produits.php");
include_once("./api/Livraisons.php");
include_once("./api/Livreurs.php");
include_once("./api/LivreursAuth.php");


header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$request = explode('/', trim($request_uri, '/'));

// logique des operations CRUD
switch ($method) {
    case 'GET':
        handleGet($request);
        break;
    case 'POST':
        handlePost($request);
        break;
    case 'PUT':
        handlePut($request);
        break;
    case 'DELETE':
        handleDelete($request);
        break;
    default:
        echo json_encode(["message" => "Méthode non supportée"]);
        break;
}

function handleGet($url)
{
    include_once("./api/get/get.php");
}

function handlePost($url)
{
    include_once("./api/post/post.php");
}

function handlePut($url)
{
    include_once("./api/put/put.php");
}

function handleDelete($url)
{
    include_once("./api/delete/delete.php");
}
