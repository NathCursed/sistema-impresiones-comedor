<?php
require 'config.php';

if (!empty($_SESSION['usuario_id'])) ir('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $usuario = trim($_POST['usuario'] ?? '');
    $clave   = $_POST['clave'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $u = $stmt->fetch();

    if ($u && password_verify($clave, $u['clave_hash'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $u['id'];
        $_SESSION['nombre']     = $u['nombre'];
        ir('index.php');
    }
    $error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar sesión · <?= e(NEGOCIO) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="pantalla-login">
  <form method="post" class="tarjeta login">
    <div class="logo">🖨️🍽️</div>
    <h1><?= e(NEGOCIO) ?></h1>
    <p class="sub">Ingrese sus datos para continuar</p>
    <?php if ($error): ?><div class="alerta error"><?= e($error) ?></div><?php endif; ?>
    <?= csrf_campo() ?>
    <label>Usuario<input type="text" name="usuario" required autofocus value="<?= e($_POST['usuario'] ?? '') ?>"></label>
    <label>Contraseña<input type="password" name="clave" required></label>
    <button type="submit" class="btn ancho">Entrar</button>
  </form>
</body>
</html>
