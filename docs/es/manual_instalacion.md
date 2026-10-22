# Manual de Instalación
**Sistema de Impresiones y Comedor** · Versión 1.0

Este manual explica, paso a paso, cómo instalar el sistema en una computadora con **Ubuntu** usando **XAMPP**.

---

## 1. Requisitos

| Elemento | Mínimo |
|---|---|
| Sistema operativo | Ubuntu 20.04 o superior (también funciona en Windows con XAMPP) |
| XAMPP | 8.x (incluye Apache, PHP 8 y MySQL/MariaDB) |
| Navegador | Chrome, Firefox o Edge actualizados |
| Espacio en disco | 1 GB libre (XAMPP) + 5 MB (sistema) |
| Memoria RAM | 2 GB |

> ✅ **¿Tiene base de datos?** Sí. El sistema usa una base de datos **MySQL** llamada `impresiones_comedor` con 4 tablas: `usuarios`, `catalogo`, `pedidos` y `pedido_detalle`. Se crea automáticamente al importar el archivo `database/database.sql`.

---

## 2. Instalar XAMPP en Ubuntu

*(Si ya tiene XAMPP instalado, salte al paso 3.)*

1. Descargue el instalador para Linux desde **https://www.apachefriends.org**.
2. Abra una terminal en la carpeta de descargas y ejecute:

```bash
cd ~/Descargas
chmod +x xampp-linux-x64-*-installer.run
sudo ./xampp-linux-x64-*-installer.run
```

3. Siga el asistente con las opciones por defecto. XAMPP queda instalado en `/opt/lampp`.

---

## 3. Encender Apache y MySQL

```bash
sudo /opt/lampp/lampp start
```

Debe ver mensajes como `Starting Apache...ok` y `Starting MySQL...ok`.

> ⚠️ Si sale un error de **puerto ocupado**, Ubuntu tiene su propio Apache o MySQL encendido. Apáguelos y vuelva a intentarlo:
> ```bash
> sudo systemctl stop apache2 mysql
> sudo /opt/lampp/lampp start
> ```

Para comprobar, abra **http://localhost** en el navegador. Debe aparecer la página de bienvenida de XAMPP.

---

## 4. Descargar el sistema

Elija **una** de estas dos formas.

### Opción A: con Git (recomendada)

```bash
sudo apt install git -y
sudo git clone https://github.com/USUARIO/sistema-impresiones-comedor.git /opt/lampp/htdocs/impresiones
```

*(Cambie `USUARIO` por el nombre de la cuenta de GitHub donde está el proyecto.)*

### Opción B: con el archivo ZIP

1. En la página del repositorio de GitHub, presione **Code → Download ZIP**, o use el ZIP que le entregaron.
2. Descomprima y copie la carpeta a XAMPP:

```bash
cd ~/Descargas
unzip sistema-impresiones-comedor*.zip
sudo cp -r sistema-impresiones-comedor* /opt/lampp/htdocs/impresiones
```

### Dar permisos a la carpeta

```bash
sudo chown -R $USER:$USER /opt/lampp/htdocs/impresiones
```

---

## 5. Crear la base de datos

Elija **una** de estas dos formas.

### Opción A: por terminal

```bash
/opt/lampp/bin/mysql -u root < /opt/lampp/htdocs/impresiones/database/database.sql
```

Si no aparece ningún mensaje, todo salió bien.

### Opción B: con phpMyAdmin

1. Abra **http://localhost/phpmyadmin**.
2. Vaya a la pestaña **Importar**.
3. Presione **Seleccionar archivo** y elija `database/database.sql` (está dentro de `/opt/lampp/htdocs/impresiones`).
4. Presione **Continuar** (abajo).
5. A la izquierda aparecerá la base de datos **impresiones_comedor**.

---

## 6. Configurar (opcional)

Abra el archivo `config.php` con un editor de texto:

```bash
nano /opt/lampp/htdocs/impresiones/config.php
```

| Línea | Para qué sirve |
|---|---|
| `define('NEGOCIO', 'Impresiones y Comedor');` | Nombre del negocio que se ve en pantalla y en el ticket |
| `define('LUGAR', 'Apopa, San Salvador');` | Dirección que sale en el ticket |
| `define('DB_PASS', '');` | Contraseña de MySQL. En XAMPP viene vacía; cámbiela solo si le puso contraseña a MySQL |

Guarde con `Ctrl + O`, `Enter` y salga con `Ctrl + X`.

---

## 7. Entrar por primera vez

1. Abra **http://localhost/impresiones**.
2. Ingrese con:
   - **Usuario:** `admin`
   - **Contraseña:** `admin123`
3. ⚠️ **Cambie la contraseña** de inmediato con el enlace **Contraseña** (arriba a la derecha).
4. Vaya a **Catálogo** y cambie los precios de ejemplo por los reales del negocio.

---

## 8. Usarlo desde otro dispositivo (celular, otra PC)

Si otro equipo está conectado a la **misma red Wi-Fi**:

1. En la computadora con XAMPP, averigüe su IP:
   ```bash
   hostname -I
   ```
   Ejemplo: `192.168.1.25`
2. En el otro dispositivo abra: **http://192.168.1.25/impresiones**

---

## 9. Encender XAMPP automáticamente (opcional)

Para no tener que encenderlo cada vez que se prende la computadora:

```bash
sudo crontab -e
```

Agregue esta línea al final y guarde:

```
@reboot /opt/lampp/lampp start
```

---

## 10. Solución de problemas

| Problema | Solución |
|---|---|
| **"No se pudo conectar a la base de datos"** | Encienda MySQL: `sudo /opt/lampp/lampp start`. Verifique que importó `database.sql`. |
| **"Access denied for user 'root'"** | MySQL tiene contraseña. Escríbala en `DB_PASS` dentro de `config.php`. |
| **"Unknown database 'impresiones_comedor'"** | Falta importar la base de datos (paso 5). |
| **Página en blanco o error 403** | Revise permisos: `sudo chown -R $USER:$USER /opt/lampp/htdocs/impresiones` |
| **"Not Found" (404)** | Verifique que la carpeta se llame `impresiones` dentro de `/opt/lampp/htdocs/`. |
| **Apache no arranca (puerto 80 ocupado)** | `sudo systemctl stop apache2` y vuelva a iniciar XAMPP. |
| **MySQL no arranca (puerto 3306 ocupado)** | `sudo systemctl stop mysql` y vuelva a iniciar XAMPP. |
| **Olvidé la contraseña de admin** | Vea la sección *Restablecer contraseña* del **Manual Técnico**. |

---

## 11. Desinstalar

```bash
/opt/lampp/bin/mysql -u root -e "DROP DATABASE impresiones_comedor;"
sudo rm -rf /opt/lampp/htdocs/impresiones
```
