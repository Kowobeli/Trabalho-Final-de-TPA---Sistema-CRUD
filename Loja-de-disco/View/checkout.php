<?php
session_start();
require_once '../Conexao/database.php';

$database = new Database();
$db = $database->getConnection(); 

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Verificação se o carrinho está definido e é um array
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header('Location: cart.php'); 
    exit();
}

$total = 0;

// Calcular o total dos itens no carrinho
foreach ($_SESSION['cart'] as $item) {
    if (isset($item['price']) && isset($item['quantity'])) {
        $total += $item['price'] * $item['quantity']; 
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user']['id'];

    require_once '../Controller/PurchaseController.php';
    $purchaseController = new PurchaseController($db); 

    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id']; 
        $quantity = $item['quantity'];

        // Criar a compra
        if (!$purchaseController->createPurchase($user_id, $product_id, $quantity)) {
            // Você pode adicionar uma mensagem de erro aqui, se necessário
        }
    }

    unset($_SESSION['cart']);
    echo "<div class='success-message'>Compra realizada com sucesso! Total: R$ " . number_format($total, 2, ',', '.') . "</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        h2 {
            margin-top: 30px;
            color: #444;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }
        form {
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        a {
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #0056b3;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Finalizar Compra</h1>

    <h2>Itens no Carrinho:</h2>
    <table>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço</th>
            <th>Subtotal</th>
        </tr>
        <?php if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="error-message">Carrinho está vazio ou mal definido.</td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="total">
        Total: R$ <?php echo number_format($total, 2, ',', '.'); ?>
    </div>

    <h2>Informações de Pagamento</h2>
    <form method="POST" action="checkout.php">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="address">Endereço:</label>
        <input type="text" id="address" name="address" required>

        <input type="submit" value="Finalizar Compra">
    </form>

    <div class="buttons">
        <a href="cart.php">Voltar ao Carrinho</a>
        <a href="home.php">Continuar Comprando</a>
    </div>
</body>
</html>
