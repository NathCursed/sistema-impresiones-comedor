<?php
require 'config.php';
requiere_login();

// Ingresos de hoy por área (no cuenta pedidos cancelados)
$porArea = $pdo->query("
    SELECT c.area, COALESCE(SUM(d.subtotal),0) AS total
    FROM pedido_detalle d
    JOIN pedidos p  ON p.id = d.pedido_id
    JOIN catalogo c ON c.id = d.catalogo_id
    WHERE DATE(p.fecha) = CURDATE() AND p.estado <> 'Cancelado'
    GROUP BY c.area
")->fetchAll(PDO::FETCH_KEY_PAIR);

$totalHoy   = array_sum($porArea);
$pedidosHoy = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE DATE(fecha) = CURDATE() AND estado <> 'Cancelado'")->fetchColumn();

// Conteo de pedidos activos por estado
$conteo = $pdo->query("
    SELECT estado, COUNT(*) FROM pedidos
    WHERE estado IN ('Pendiente','En proceso','Listo')
    GROUP BY estado
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Pedidos que todavía no se entregan
$activos = $pdo->query("
    SELECT * FROM pedidos
    WHERE estado IN ('Pendiente','En proceso','Listo')
    ORDER BY FIELD(estado,'Listo','En proceso','Pendiente'), fecha
")->fetchAll();

require 'header.php';
?>
<h1>Resumen de hoy <small><?= date('d/m/Y') ?></small></h1>

<div class="stats">
  <div class="stat"><strong><?= dinero($totalHoy) ?></strong><span>Vendido hoy</span></div>
  <div class="stat imp"><strong><?= dinero($porArea['Impresiones'] ?? 0) ?></strong><span>🖨️ Impresiones</span></div>
  <div class="stat com"><strong><?= dinero($porArea['Comida'] ?? 0) ?></strong><span>🍽️ Comida</span></div>
  <div class="stat"><strong><?= (int)$pedidosHoy ?></strong><span>Pedidos hoy</span></div>
</div>

<div class="estados-resumen">
  <?php foreach (['Pendiente', 'En proceso', 'Listo'] as $s): ?>
    <a href="pedidos.php?estado=<?= urlencode($s) ?>&fecha=" class="chip <?= clase_estado($s) ?>">
      <?= $s ?>: <strong><?= (int)($conteo[$s] ?? 0) ?></strong>
    </a>
  <?php endforeach; ?>
</div>

<div class="fila-titulo">
  <h2>Pedidos por entregar</h2>
  <a class="btn" href="pedido_nuevo.php">+ Nuevo pedido</a>
</div>

<table>
  <thead><tr><th>#</th><th>Hora</th><th>Cliente</th><th>Tipo</th><th>Total</th><th>Estado</th><th></th></tr></thead>
  <tbody>
  <?php if (!$activos): ?>
    <tr><td colspan="7" class="vacio">No hay pedidos pendientes. 🎉</td></tr>
  <?php endif; ?>
  <?php foreach ($activos as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= date('d/m H:i', strtotime($p['fecha'])) ?></td>
      <td><?= e($p['cliente']) ?></td>
      <td><?= e($p['tipo']) ?></td>
      <td><?= dinero($p['total']) ?></td>
      <td><span class="etiqueta <?= clase_estado($p['estado']) ?>"><?= e($p['estado']) ?></span></td>
      <td><a href="pedido_ver.php?id=<?= $p['id'] ?>">Ver</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php require 'footer.php'; ?>
