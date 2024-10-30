<?php
require_once '../Controller/DiscoController.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: home.php');
    exit();
}

if (isset($_GET['id'])) {
    $productController = new DiscoController();
    $productController->delete($_GET['id']); 
    header('Location: admin.php'); 
    exit();
}
?>