<?php
require_once '../Controller/UserController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new UserController();
    $role = $_POST['role'] ?? 'client';
    $protectedPassword = $_POST['protected_password'] ?? ''; 
    $correctAdminPassword = 'admin'; 

  
    if ($role === 'admin' && $protectedPassword !== $correctAdminPassword) {
        $error = "A senha do administrador está incorreta.";
    } else {
        
        if ($auth->register($_POST['name'], $_POST['password'], $role)) {
            header('Location: login.php');
        } else {
            $error = "Erro ao registrar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .register-container {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .register-container h1 {
            margin-bottom: 2rem;
            color: #333;
        }

        .register-container form {
            display: flex;
            flex-direction: column;
        }

        .register-container input, .register-container select {
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .register-container button {
            padding: 0.8rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .register-container button:hover {
            background-color: #0056b3;
        }

        .login-link {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-top: 1rem;
        }

        .protected-password {
            display: none;
        }
    </style>
    <script>
        function toggleProtectedPassword() {
            const roleSelect = document.querySelector('select[name="role"]');
            const protectedPasswordInput = document.getElementById('protected-password');

            if (roleSelect.value === 'admin') {
                protectedPasswordInput.style.display = 'block';
            } else {
                protectedPasswordInput.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<div class="register-container">
    <h1>Registro</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Nome" required>
        <input type="password" name="password" placeholder="Senha" required>
        <select name="role" onchange="toggleProtectedPassword()">
            <option value="client">Cliente</option>
            <option value="admin">Administrador</option>
        </select>
        <input type="password" id="protected-password" class="protected-password" name="protected_password" placeholder="Senha do Administrador">
        <button type="submit">Registrar</button>
    </form>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="login-link">
        Já tem uma conta? <a href="login.php">Faça login aqui</a>
    </div>
</div>

</body>
</html>