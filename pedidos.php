<?php
require 'config.php';
requiere_login();

// Filtros: fecha (por defecto hoy; vacía = todas) y estado
$fecha  = $_GET['fecha'] ?? date('Y-m-d');
$estado = $_GET['estado'] ?? '';
$q      = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM pedidos WHERE 1=1";
$par = [];
if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) { $sql .= " AND DATE(fecha) = ?"; $par[] = $fecha; }
if (in_array($estado, ESTADOS, true))          { $sql .= " AND estado = ?";      $par[] = $estado; }
if ($q !== '')                                  { $sql .= " AND (cliente LIKE ? OR telefono LIKE ? OR id = ?)"; array_push($par, "%$q%", "%$q%", (int)$q); }
$sql .= " ORDER BY fecha DESC LIMIT 300";

$stmt = $pdo->prepare($sql);
$stmt->execute($par);
$pedidos = $stmt->fetchAll();

$suma = 0;
foreach ($pedidos as $p) if ($p['estado'] !== 'Cancelado') $suma += $p['total'];

require 'header.php';
?>
<div class="fila-titulo">
  <h1>Pedidos</h1>
  <a class="btn" href="pedido_nuevo.php">+ Nuevo pedido</a>
</div>

<form method="get" class="filtros">
  <input type="date" name="fecha" value="<?= e($fecha) ?>" title="Deje vacío para ver todas las fechas">
  <select name="estado">
    <option value="">Todos los estados</option>
    <?php foreach (ESTADOS as $s): ?><option <?= $estado === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?>
  </select>
  <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cliente, teléfono o # de pedido">
  <button type="submit">Filtrar</button>
  <a class="btn-sec" href="pedidos.php?fecha=">Ver todas las fechas</a>
</form>

<table>
  <thead><tr><th>#</th><th>Fecha</th><th>Cliente</th><th>Teléfono</th><th>Tipo</th><th>Total</th><th>Estado</th><th></th></tr></thead>
  <tbody>
  <?php if (!$pedidos): ?>
    <tr><td colspan="8" class="vacio">No hay pedidos con esos filtros.</td></tr>
  <?php endif; ?>
  <?php foreach ($pedidos as $p): ?>
    <tr>
      <td><?= $p['id'] ?></td>
      <td><?= date('d/m/Y H:i', strtotime($p['fecha'])) ?></td>
      <td><?= e($p['cliente']) ?></td>
      <td><?= e($p['telefono']) ?></td>
      <td><?= e($p['tipo']) ?></td>
      <td><?= dinero($p['total']) ?></td>
      <td><span class="etiqueta <?= clase_estado($p['estado']) ?>"><?= e($p['estado']) ?></span></td>
      <td><a href="pedido_ver.php?id=<?= $p['id'] ?>">Ver</a></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  <?php if ($pedidos): ?>
  <tfoot><tr><td colspan="5"><strong>Total (sin cancelados)</strong></td><td colspan="3"><strong><?= dinero($suma) ?></strong></td></tr></tfoot>
  <?php endif; ?>
</table>
<?php require 'footer.php'; ?>
