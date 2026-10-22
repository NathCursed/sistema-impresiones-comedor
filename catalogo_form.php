<?php
require 'config.php';
requiere_login();

$id = (int)($_GET['id'] ?? 0);
$i = ['area' => 'Impresiones', 'categoria' => '', 'nombre' => '', 'precio' => '', 'unidad' => 'unidad', 'activo' => 1];
$error = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM catalogo WHERE id = ?");
    $stmt->execute([$id]);
    $i = $stmt->fetch() ?: ir('catalogo.php', 'Elemento no encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $i['area']      = $_POST['area'] ?? '';
    $i['categoria'] = trim($_POST['categoria'] ?? '');
    $i['nombre']    = trim($_POST['nombre'] ?? '');
    $i['precio']    = $_POST['precio'] ?? '';
    $i['unidad']    = trim($_POST['unidad'] ?? '') ?: 'unidad';
    $i['activo']    = isset($_POST['activo']) ? 1 : 0;

    if (!in_array($i['area'], AREAS, true)) {
        $error = 'Seleccione un área válida.';
    } elseif ($i['categoria'] === '' || $i['nombre'] === '') {
        $error = 'La categoría y el nombre son obligatorios.';
    } elseif (!is_numeric($i['precio']) || $i['precio'] < 0) {
        $error = 'El precio no es válido.';
    } else {
        $datos = [$i['area'], $i['categoria'], $i['nombre'], $i['precio'], $i['unidad'], $i['activo']];
        if ($id) {
            $datos[] = $id;
            $pdo->prepare("UPDATE catalogo SET area=?, categoria=?, nombre=?, precio=?, unidad=?, activo=? WHERE id=?")->execute($datos);
            ir('catalogo.php', 'Elemento actualizado.');
        }
        $pdo->prepare("INSERT INTO catalogo (area, categoria, nombre, precio, unidad, activo) VALUES (?,?,?,?,?,?)")->execute($datos);
        ir('catalogo.php', 'Elemento agregado al catálogo.');
    }
}

require 'header.php';
?>
<h1><?= $id ? 'Editar elemento' : 'Agregar al catálogo' ?></h1>
<?php if ($error): ?><div class="alerta error"><?= e($error) ?></div><?php endif; ?>

<form method="post" class="tarjeta rejilla">
  <?= csrf_campo() ?>
  <label>Área *
    <select name="area" id="area" required>
      <?php foreach (AREAS as $a): ?>
        <option <?= $i['area'] === $a ? 'selected' : '' ?>><?= $a ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Categoría *
    <input type="text" name="categoria" list="lista-categorias" required value="<?= e($i['categoria']) ?>" placeholder="Ej.: Copias, Almuerzos">
    <datalist id="lista-categorias">
      <?php foreach (CATEGORIAS as $cats) foreach ($cats as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?>
    </datalist>
  </label>
  <label class="completo">Nombre *<input type="text" name="nombre" required value="<?= e($i['nombre']) ?>" placeholder="Ej.: Impresión a color, Almuerzo del día"></label>
  <label>Precio ($) *<input type="number" name="precio" step="0.01" min="0" required value="<?= e($i['precio']) ?>"></label>
  <label>Se cobra por<input type="text" name="unidad" value="<?= e($i['unidad']) ?>" placeholder="página, plato, unidad..."></label>
  <label class="completo check"><input type="checkbox" name="activo" <?= $i['activo'] ? 'checked' : '' ?>> Disponible para vender</label>
  <div class="completo botones">
    <button type="submit">Guardar</button>
    <a class="btn-sec" href="catalogo.php">Cancelar</a>
  </div>
</form>
<?php require 'footer.php'; ?>
