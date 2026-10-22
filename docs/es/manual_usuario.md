# Manual de Usuario
**Sistema de Impresiones y Comedor** · Versión 1.0

Este manual es para las personas que atienden el negocio: cajeros, encargados y dueños. No necesita saber de computación para usar el sistema.

---

## 1. ¿Qué hace el sistema?

El negocio vende **dos tipos de cosas**:

- 🖨️ **Impresiones**: impresiones en blanco y negro o a color, copias, escaneos, anillados, plastificados…
- 🍽️ **Comida**: almuerzos, desayunos, snacks y bebidas.

Con el sistema usted puede:

- Registrar los **pedidos** de los clientes, juntando impresiones y comida en el mismo pedido.
- Saber qué pedidos están **pendientes, en proceso o listos**.
- **Imprimir un ticket** para el cliente.
- Cambiar **precios** y agregar productos o servicios nuevos.
- Ver **cuánto se vendió** por día, por área y qué es lo más vendido.

---

## 2. Entrar al sistema

1. Abra el navegador (Chrome o Firefox) y escriba la dirección que le dieron, por ejemplo **http://localhost/impresiones**.
2. Escriba su **usuario** y **contraseña**.
3. Presione **Entrar**.

![Pantalla de inicio de sesión](../img/capturas/01_login.png)

> Si escribe mal los datos, aparece el mensaje *"Usuario o contraseña incorrectos"*. Intente de nuevo.

Para **salir**, presione **Salir** (arriba a la derecha). Hágalo siempre al terminar su turno.

---

## 3. Menú principal

En la barra azul de arriba están todas las opciones:

| Opción | Para qué sirve |
|---|---|
| **Inicio** | Resumen del día y pedidos por entregar |
| **+ Nuevo pedido** | Registrar lo que pide un cliente |
| **Pedidos** | Ver y buscar todos los pedidos |
| **Catálogo** | Ver y cambiar productos, servicios y precios |
| **Reportes** | Ver cuánto se vendió en un rango de fechas |
| **Contraseña** | Cambiar su contraseña |
| **Salir** | Cerrar la sesión |

---

## 4. Inicio (resumen del día)

![Pantalla de inicio](../img/capturas/02_inicio.png)

Aquí ve de un vistazo:

- **Vendido hoy**: todo el dinero del día (no cuenta los pedidos cancelados).
- **🖨️ Impresiones** y **🍽️ Comida**: cuánto dejó cada área hoy.
- **Pedidos hoy**: cuántos pedidos se hicieron.
- Botones de colores con cuántos pedidos están **Pendientes**, **En proceso** y **Listos**. Si presiona uno, verá esos pedidos.
- **Pedidos por entregar**: la lista de lo que falta entregar. Los **Listos** aparecen primero.

---

## 5. Registrar un pedido nuevo

1. Presione **+ Nuevo pedido**.
2. Escriba el **nombre del cliente** (obligatorio) y, si quiere, su **teléfono**.
3. Elija el **tipo**: *Para llevar*, *Comer aquí* o *Recoger después*.
4. En **Notas** escriba indicaciones especiales, por ejemplo *"doble cara"* o *"sin cebolla"*.
5. Escriba la **cantidad** de cada cosa que pide el cliente:
   - A la izquierda están las **Impresiones**.
   - A la derecha está la **Comida**.
   - Las líneas con cantidad se marcan en celeste.
6. Abajo verá el **Total** calculado automáticamente.
7. Presione **Guardar pedido**.

![Nuevo pedido](../img/capturas/03_nuevo_pedido.png)

**Ejemplo:** María pide 20 impresiones en blanco y negro, un anillado, un almuerzo y un refresco. Total: **$6.50**.

> Si no escribe el nombre del cliente o no pone ninguna cantidad, el sistema le avisa y no guarda el pedido.

---

## 6. Ver un pedido e imprimir el ticket

Al guardar el pedido se abre su detalle:

![Detalle del pedido](../img/capturas/04_ver_pedido.png)

- A la **izquierda** está el **ticket**, con todo lo que pidió el cliente y el total.
- A la **derecha** están los botones para **cambiar el estado**.

### Imprimir el ticket
Presione **🖨️ Imprimir ticket**. Solo se imprime el ticket, sin menús ni botones. Funciona con impresoras normales y con impresoras de tickets de 80 mm.

![Ticket impreso](../img/capturas/09_ticket_impreso.png)

---

## 7. Estados de un pedido

Cada pedido pasa por estos pasos:

| Estado | Significado | Color |
|---|---|---|
| **Pendiente** | Recién registrado, todavía no se empieza | 🟡 Amarillo |
| **En proceso** | Se está imprimiendo o cocinando | 🔵 Azul |
| **Listo** | Terminado, esperando que el cliente lo recoja | 🟢 Verde |
| **Entregado** | El cliente ya se lo llevó | ⚪ Gris |
| **Cancelado** | El cliente ya no lo quiso. **No se suma** en las ventas | 🔴 Rojo |

Para cambiarlo, abra el pedido y presione el botón del nuevo estado. Para **Cancelar**, el sistema le pide confirmar.

![Diagrama de estados](../img/diagramas/es/4_estados_pedido.png)

---

## 8. Buscar pedidos

Presione **Pedidos**.

![Lista de pedidos](../img/capturas/05_pedidos.png)

- Por defecto se ven los pedidos de **hoy**.
- Cambie la **fecha** para ver otro día, o presione **Ver todas las fechas**.
- Filtre por **estado** (por ejemplo, solo *Listos*).
- Busque por **nombre del cliente**, **teléfono** o **número de pedido**.
- Abajo de la tabla está el **total** de los pedidos mostrados (sin contar los cancelados).
- Presione **Ver** para abrir un pedido.

---

## 9. Catálogo (productos, servicios y precios)

Presione **Catálogo**.

![Catálogo](../img/capturas/06_catalogo.png)

### Agregar algo nuevo
1. Presione **+ Agregar al catálogo**.
2. Elija el **área**: *Impresiones* o *Comida*.
3. Escriba la **categoría** (por ejemplo *Copias*, *Almuerzos* o *Bebidas*). Puede elegir una de la lista o escribir una nueva.
4. Escriba el **nombre** y el **precio**.
5. En **Se cobra por** escriba la unidad: *página*, *plato*, *unidad*…
6. Deje marcado **Disponible para vender**.
7. Presione **Guardar**.

![Formulario del catálogo](../img/capturas/07_catalogo_form.png)

### Cambiar un precio
Presione **Editar** en la fila, cambie el precio y presione **Guardar**. Los pedidos anteriores **conservan el precio de cuando se vendieron**.

### Quitar algo del menú por un día
Presione **Editar** y desmarque **Disponible para vender**. Ya no aparecerá en *Nuevo pedido*, pero tampoco se borra.

### Eliminar
Presione **Eliminar**. Si ese producto ya se vendió alguna vez, el sistema **no lo borra**: lo marca como *No disponible* para no perder el historial de ventas.

---

## 10. Reportes de ventas

Presione **Reportes**.

![Reportes](../img/capturas/08_reportes.png)

1. Elija las fechas **Desde** y **Hasta**. Por defecto se muestra el mes actual.
2. Presione **Ver reporte**.

El reporte muestra:
- **Total vendido**, lo que dejó cada área y el número de pedidos.
- **🏆 Lo más vendido**: los 10 productos o servicios que más dinero dejaron.
- **📅 Ventas por día**: cuánto se vendió cada día, separado en Impresiones y Comida.

Presione **🖨️ Imprimir** para tener el reporte en papel o guardarlo como PDF.

---

## 11. Cambiar su contraseña

1. Presione **Contraseña** (arriba a la derecha).
2. Escriba su contraseña actual.
3. Escriba la nueva dos veces. Debe tener **al menos 6 caracteres**.
4. Presione **Guardar**.

---

## 12. Atender a un cliente: resumen rápido

1. **+ Nuevo pedido** → nombre → cantidades → **Guardar pedido**.
2. **🖨️ Imprimir ticket** y cobrar.
3. Al empezar a trabajarlo: **En proceso**.
4. Al terminar: **Listo**.
5. Cuando el cliente se lo lleva: **Entregado**.

---

## 13. Preguntas frecuentes

**¿Puedo usarlo desde el celular?**
Sí. Si el celular está en la misma red Wi-Fi, abra la dirección que le dé el encargado (por ejemplo `http://192.168.1.25/impresiones`).

**Me equivoqué en un pedido, ¿lo puedo editar?**
No se puede editar, para que nadie cambie un pedido ya cobrado. Márquelo como **Cancelado** y haga uno nuevo.

**¿Se pierde la información si apago la computadora?**
No. Todo queda guardado en la base de datos. Al volver a encender, inicie XAMPP y siga trabajando.

**Olvidé mi contraseña.**
Pida al encargado técnico que la restablezca (ver el *Manual Técnico*).
