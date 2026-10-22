// Calcula el total del pedido en vivo (solo es una ayuda visual;
// el total real lo calcula el servidor con los precios de la base de datos).
(function () {
  const form = document.getElementById('form-pedido');
  if (!form) return;

  const campos = form.querySelectorAll('input.cant');
  const salida = document.getElementById('total-pedido');

  function calcular() {
    let total = 0;
    campos.forEach(function (c) {
      const n = parseInt(c.value, 10) || 0;
      total += n * parseFloat(c.dataset.precio);
      c.closest('.linea-item').classList.toggle('con-cantidad', n > 0);
    });
    salida.textContent = '$' + total.toFixed(2);
  }

  campos.forEach(function (c) { c.addEventListener('input', calcular); });
  calcular();
})();
