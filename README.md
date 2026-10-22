# 🖨️🍽️ Sistema de Impresiones y Comedor

**Español** · [English](#-english)

Sistema web para un negocio de **impresiones y comida** en un mismo local. Sirve para registrar pedidos, imprimir tickets, controlar el estado de cada pedido y ver reportes de ventas por área.

Hecho con **PHP + MySQL** para funcionar en **XAMPP** (Ubuntu o Windows).

![Pantalla de inicio](docs/img/capturas/02_inicio.png)

## ✨ Funciones

- 🔐 Inicio de sesión con contraseña cifrada
- 🧾 Pedidos que combinan **impresiones** (copias, color, anillado…) y **comida** (almuerzos, bebidas…)
- 💲 Total calculado al instante
- 🚦 Estados: Pendiente → En proceso → Listo → Entregado (o Cancelado)
- 🖨️ Ticket imprimible (sirve para impresora de tickets de 80 mm)
- 📋 Catálogo editable de servicios, platillos y precios
- 📊 Reportes por fechas: total, por área, lo más vendido y ventas por día
- 📱 Funciona en computadora y celular

## 🗄️ Base de datos

**Sí, el sistema usa base de datos MySQL**: `impresiones_comedor`, con 4 tablas:

| Tabla | Guarda |
|---|---|
| `usuarios` | Quién puede entrar al sistema |
| `catalogo` | Servicios de impresión y platillos, con su precio |
| `pedidos` | Cada pedido: cliente, tipo, estado y total |
| `pedido_detalle` | Qué se pidió en cada pedido y cuánto |

Se crea importando [`database/database.sql`](database/database.sql).

## 🚀 Instalación rápida (Ubuntu + XAMPP)

```bash
sudo /opt/lampp/lampp start
sudo git clone https://github.com/USUARIO/sistema-impresiones-comedor.git /opt/lampp/htdocs/impresiones
sudo chown -R $USER:$USER /opt/lampp/htdocs/impresiones
/opt/lampp/bin/mysql -u root < /opt/lampp/htdocs/impresiones/database/database.sql
```

Abra **http://localhost/impresiones** y entre con **admin** / **admin123**. Cambie la contraseña al entrar.

¿Prefiere el ZIP? En GitHub presione **Code → Download ZIP**, descomprímalo en `/opt/lampp/htdocs/impresiones` e importe la base de datos.

## 📚 Documentación

| Documento | Español | English |
|---|---|---|
| Manual de instalación | [manual_instalacion.md](docs/es/manual_instalacion.md) | [installation_manual.md](docs/en/installation_manual.md) |
| Manual de usuario | [manual_usuario.md](docs/es/manual_usuario.md) | [user_manual.md](docs/en/user_manual.md) |
| Manual técnico | [manual_tecnico.md](docs/es/manual_tecnico.md) | [technical_manual.md](docs/en/technical_manual.md) |
| Diagramas | [diagramas.md](docs/es/diagramas.md) | [diagrams.md](docs/en/diagrams.md) |

Las versiones en PDF de los manuales están en [`docs/pdf/`](docs/pdf/).

**Diagramas incluidos:** Entidad-Relación, Arquitectura, Flujo de pedido, Estados del pedido, Casos de uso y Mapa de navegación.

## 📁 Estructura

```
├── *.php            Páginas del sistema
├── assets/          Estilos (CSS) y JavaScript
├── database/        Script de la base de datos
└── docs/            Manuales, diagramas y capturas (ES / EN)
```

## 🛠️ Tecnologías

PHP 8 · MySQL / MariaDB · Apache (XAMPP) · HTML5 · CSS3 · JavaScript

## 📄 Licencia

MIT. Ver [LICENSE](LICENSE).

---

# 🇺🇸 English

Web system for a business that offers **printing services and food** in the same shop. It registers orders, prints tickets, tracks each order's status and shows sales reports per area.

Built with **PHP + MySQL** to run on **XAMPP** (Ubuntu or Windows). The interface is in Spanish.

## Features

- 🔐 Login with hashed passwords
- 🧾 Orders that mix **printing** (copies, color, binding…) and **food** (lunches, drinks…)
- 💲 Instant total
- 🚦 Statuses: Pending → In progress → Ready → Delivered (or Cancelled)
- 🖨️ Printable ticket (works with 80 mm receipt printers)
- 📋 Editable catalog of services, dishes and prices
- 📊 Date-range reports: total, per area, best sellers and daily sales
- 📱 Works on desktop and mobile

## Database

**Yes, it uses a MySQL database**: `impresiones_comedor`, with 4 tables: `usuarios` (users), `catalogo` (catalog), `pedidos` (orders) and `pedido_detalle` (order lines). It is created by importing [`database/database.sql`](database/database.sql).

## Quick install (Ubuntu + XAMPP)

```bash
sudo /opt/lampp/lampp start
sudo git clone https://github.com/USERNAME/sistema-impresiones-comedor.git /opt/lampp/htdocs/impresiones
sudo chown -R $USER:$USER /opt/lampp/htdocs/impresiones
/opt/lampp/bin/mysql -u root < /opt/lampp/htdocs/impresiones/database/database.sql
```

Open **http://localhost/impresiones** and log in with **admin** / **admin123**. Change the password after logging in.

## Documentation

See the table above: installation, user and technical manuals, plus diagrams, in Spanish and English. PDF versions are in [`docs/pdf/`](docs/pdf/).

## License

MIT. See [LICENSE](LICENSE).
