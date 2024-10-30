<?php
require_once '../Controller/DiscoController.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: home.php');
    exit();
}

$discoController = new DiscoController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'], $_POST['name'], $_POST['description'], $_POST['price'], $_POST['category'])) {
        // Captura o ID do produto que está sendo atualizado
        $id = $_POST['id'];

        // Obtém os dados do produto atual para manter a imagem, caso não tenha sido alterada
        $currentProduct = $discoController->readOne($id);
        
        // Verifica se uma nova imagem foi enviada; caso contrário, usa a imagem existente
        if ($_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            $image = $currentProduct['image']; // Mantém a imagem atual
        } else {
            $image = $_FILES['image']; // Usa a nova imagem
        }

        $result = $discoController->update(
            $id,
            $_POST['name'],
            $_POST['description'],
            $image, // Envia a imagem (nova ou existente)
            $_POST['price'],
            $_POST['category']
        );

        if ($result) {
            header('Location: admin.php');
            exit();
        } else {
            $error_message = "Erro ao atualizar o produto.";
        }
    }
}

if (isset($_GET['id'])) {
    $product = $discoController->readOne($_GET['id']);
} else {
    header('Location: admin.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f5f5f5; }
        .form-container { background-color: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 500px; text-align: center; }
        h1 { margin-bottom: 2rem; color: #333; }
        form { display: flex; flex-direction: column; gap: 1rem; }
        form input, form select { padding: 0.8rem; border: 1px solid #ccc; border-radius: 5px; font-size: 1rem; }
        button { padding: 0.8rem; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; }
        button:hover { background-color: #0056b3; }
        .error-message { color: red; margin-bottom: 1rem; }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Editar Produto</h1>

    <?php if (isset($error_message)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="product-image">
        <img src="<?php echo '../uploads/' . htmlspecialchars($product['image'] ?? 'default.jpg'); ?>" alt="Imagem do produto">
    </div>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id'] ?? ''); ?>">
        <input type="text" name="name" placeholder="Nome" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
        <input type="text" name="description" placeholder="Descrição" value="<?php echo htmlspecialchars($product['description'] ?? ''); ?>" required>
        <input type="number" name="price" placeholder="Preço" value="<?php echo $product['price'] ?? ''; ?>" required step="0.01">
        <label for="image">Imagem do Produto (opcional):</label>
        <input type="file" name="image" accept="image/*">
        <input type="text" name="category" placeholder="Categoria" value="<?php echo htmlspecialchars($product['category'] ?? ''); ?>" required>
        <button type="submit">Atualizar</button>
    </form>

    <a href="home.php">Voltar para a Home</a>
</div>

</body>
</html>
