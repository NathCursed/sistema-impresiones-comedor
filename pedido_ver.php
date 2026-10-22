<?php
require 'config.php';
requiere_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("
    SELECT p.*, u.nombre AS atendio
    FROM pedidos p LEFT JOIN usuarios u ON u.id = p.usuario_id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) ir('pedidos.php', 'Pedido no encontrado.');

$stmt = $pdo->prepare("
    SELECT d.*, c.nombre, c.area, c.unidad
    FROM pedido_detalle d JOIN catalogo c ON c.id = d.catalogo_id
    WHERE d.pedido_id = ?
    ORDER BY c.area, c.nombre
");
$stmt->execute([$id]);
$detalle = $stmt->fetchAll();

require 'header.php';
?>
<div class="fila-titulo no-imprimir">
  <h1>Pedido #<?= $p['id'] ?> <span class="etiqueta <?= clase_estado($p['estado']) ?>"><?= e($p['estado']) ?></span></h1>
  <div class="botones">
    <button type="button" onclick="window.print()">🖨️ Imprimir ticket</button>
    <a class="btn-sec" href="pedidos.php">Volver</a>
  </div>
</div>

<div class="dos-col">
  <!-- Ticket (es lo único que sale al imprimir) -->
  <div class="tarjeta ticket">
    <div class="ticket-encabezado">
      <strong><?= e(NEGOCIO) ?></strong><br>
      <small><?= e(LUGAR) ?></small>
    </div>
    <p>
      <strong>Pedido #<?= $p['id'] ?></strong><br>
      Fecha: <?= date('d/m/Y H:i', strtotime($p['fecha'])) ?><br>
      Cliente: <?= e($p['cliente']) ?><?= $p['telefono'] ? ' · ' . e($p['telefono']) : '' ?><br>
      Tipo: <?= e($p['tipo']) ?><br>
      Atendió: <?= e($p['atendio'] ?? '—') ?>
    </p>
    <table class="tabla-ticket">
      <thead><tr><th>Cant.</th><th>Descripción</th><th>P/U</th><th>Subtotal</th></tr></thead>
      <tbody>
      <?php foreach ($detalle as $d): ?>
        <tr>
          <td><?= (int)$d['cantidad'] ?></td>
          <td><?= $d['area'] === 'Impresiones' ? '🖨️' : '🍽️' ?> <?= e($d['nombre']) ?></td>
          <td><?= dinero($d['precio_unitario']) ?></td>
          <td><?= dinero($d['subtotal']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot><tr><td colspan="3"><strong>TOTAL</strong></td><td><strong><?= dinero($p['total']) ?></strong></td></tr></tfoot>
    </table>
    <?php if ($p['notas']): ?><p><strong>Notas:</strong> <?= e($p['notas']) ?></p><?php endif; ?>
    <p class="gracias">¡Gracias por su compra!</p>
  </div>

  <!-- Cambiar estado -->
  <div class="tarjeta no-imprimir">
    <h2>Cambiar estado</h2>
    <p>Estado actual: <span class="etiqueta <?= clase_estado($p['estado']) ?>"><?= e($p['estado']) ?></span></p>
    <form method="post" action="pedido_estado.php" class="botones-estado">
      <?= csrf_campo() ?>
      <input type="hidden" name="id" value="<?= $p['id'] ?>">
      <?php foreach (ESTADOS as $s): if ($s === $p['estado']) continue; ?>
        <button type="submit" name="estado" value="<?= e($s) ?>" class="<?= clase_estado($s) ?>"
          <?= $s === 'Cancelado' ? "onclick=\"return confirm('¿Cancelar este pedido?');\"" : '' ?>>
          <?= e($s) ?>
        </button>
      <?php endforeach; ?>
    </form>
    <p class="nota">Flujo normal: Pendiente → En proceso → Listo → Entregado.<br>
    Los pedidos cancelados no se suman en las ventas.</p>
  </div>
</div>
<?php require 'footer.php'; ?>
