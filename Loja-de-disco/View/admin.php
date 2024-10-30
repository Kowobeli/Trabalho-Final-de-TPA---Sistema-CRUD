<?php
require_once '../Controller/DiscoController.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificando se todos os campos foram preenchidos antes de chamar o método create
    if (isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['category'], $_POST['quantity']) && isset($_FILES['image'])) {
        $discoController = new DiscoController();
        // Corrigindo a ordem dos parâmetros ao chamar create
        $discoController->create($_POST['name'], $_POST['description'], $_POST['price'], $_POST['category'], $_FILES['image'], $_POST['quantity']);
    } else {
        echo "<p style='color: red;'>Por favor, preencha todos os campos.</p>";
    }
}

$discoController = new DiscoController();
$products = $discoController->read();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin - Adicionar Lanches</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .table img {
            width: 50px;
            height: 50px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Adicionar Discos</h1>
        <form method="POST" enctype="multipart/form-data" class="mb-4"> 
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <input type="text" class="form-control" name="description" id="description" required>
            </div>
            <div class="form-group">
                <label for="price">Preço</label>
                <input type="number" class="form-control" name="price" id="price" required step="0.01">
            </div>
            <div class="form-group">
                <label for="image">Imagem</label>
                <input type="file" class="form-control-file" name="image" id="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="category">Categoria</label>
                <select class="form-control" name="category" id="category" required>
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <option value="Clássica">Classica</option>
                    <option value="Pop">Pop</option>
                    <option value="Eletrõnica">Eletrõnica</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantidade</label>
                <input type="number" class="form-control" name="quantity" id="quantity" required min="1">
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>

        <h2>Discos Adicionados</h2>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Editar</a> 
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="buttons mt-4">
            <a href="view_sales.php" class="btn btn-info">Ver Compras</a>
            <a href="home.php" class="btn btn-secondary">Voltar para a Home</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>