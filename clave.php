<?php
require 'config.php';
requiere_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $actual = $_POST['actual'] ?? '';
    $nueva  = $_POST['nueva'] ?? '';
    $repite = $_POST['repite'] ?? '';

    $stmt = $pdo->prepare("SELECT clave_hash FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $hash = $stmt->fetchColumn();

    if (!$hash || !password_verify($actual, $hash)) {
        $error = 'La contraseña actual no es correcta.';
    } elseif (strlen($nueva) < 6) {
        $error = 'La nueva contraseña debe tener al menos 6 caracteres.';
    } elseif ($nueva !== $repite) {
        $error = 'Las contraseñas nuevas no coinciden.';
    } else {
        $pdo->prepare("UPDATE usuarios SET clave_hash = ? WHERE id = ?")
            ->execute([password_hash($nueva, PASSWORD_DEFAULT), $_SESSION['usuario_id']]);
        ir('index.php', 'Contraseña cambiada correctamente.');
    }
}

require 'header.php';
?>
<h1>Cambiar contraseña</h1>
<?php if ($error): ?><div class="alerta error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="tarjeta rejilla angosto">
  <?= csrf_campo() ?>
  <label class="completo">Contraseña actual<input type="password" name="actual" required></label>
  <label class="completo">Nueva contraseña<input type="password" name="nueva" required minlength="6"></label>
  <label class="completo">Repetir nueva contraseña<input type="password" name="repite" required minlength="6"></label>
  <div class="completo botones">
    <button type="submit">Guardar</button>
    <a class="btn-sec" href="index.php">Cancelar</a>
  </div>
</form>
<?php require 'footer.php'; ?>
