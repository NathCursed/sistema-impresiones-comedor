# Installation Manual
**Printing & Food System** (*Sistema de Impresiones y Comedor*) · Version 1.0

This manual explains, step by step, how to install the system on an **Ubuntu** computer using **XAMPP**.

---

## 1. Requirements

| Item | Minimum |
|---|---|
| Operating system | Ubuntu 20.04 or later (also works on Windows with XAMPP) |
| XAMPP | 8.x (includes Apache, PHP 8 and MySQL/MariaDB) |
| Browser | Up-to-date Chrome, Firefox or Edge |
| Disk space | 1 GB free (XAMPP) + 5 MB (system) |
| RAM | 2 GB |

> ✅ **Does it use a database?** Yes. The system uses a **MySQL** database named `impresiones_comedor` with 4 tables: `usuarios` (users), `catalogo` (catalog), `pedidos` (orders) and `pedido_detalle` (order details). It is created automatically when you import `database/database.sql`.

---

## 2. Install XAMPP on Ubuntu

*(If XAMPP is already installed, skip to step 3.)*

1. Download the Linux installer from **https://www.apachefriends.org**.
2. Open a terminal in your downloads folder and run:

```bash
cd ~/Downloads
chmod +x xampp-linux-x64-*-installer.run
sudo ./xampp-linux-x64-*-installer.run
```

3. Follow the wizard with the default options. XAMPP is installed in `/opt/lampp`.

---

## 3. Start Apache and MySQL

```bash
sudo /opt/lampp/lampp start
```

You should see messages like `Starting Apache...ok` and `Starting MySQL...ok`.

> ⚠️ If you get a **port already in use** error, Ubuntu has its own Apache or MySQL running. Stop them and try again:
> ```bash
> sudo systemctl stop apache2 mysql
> sudo /opt/lampp/lampp start
> ```

To check, open **http://localhost** in your browser. The XAMPP welcome page should appear.

---

## 4. Download the system

Choose **one** of these two options.

### Option A: with Git (recommended)

```bash
sudo apt install git -y
sudo git clone https://github.com/USERNAME/sistema-impresiones-comedor.git /opt/lampp/htdocs/impresiones
```

*(Replace `USERNAME` with the GitHub account that hosts the project.)*

### Option B: with the ZIP file

1. On the GitHub repository page click **Code → Download ZIP**, or use the ZIP you were given.
2. Unzip it and copy the folder into XAMPP:

```bash
cd ~/Downloads
unzip sistema-impresiones-comedor*.zip
sudo cp -r sistema-impresiones-comedor* /opt/lampp/htdocs/impresiones
```

### Set folder permissions

```bash
sudo chown -R $USER:$USER /opt/lampp/htdocs/impresiones
```

---

## 5. Create the database

Choose **one** of these two options.

### Option A: terminal

```bash
/opt/lampp/bin/mysql -u root < /opt/lampp/htdocs/impresiones/database/database.sql
```

If no message appears, everything went fine.

### Option B: phpMyAdmin

1. Open **http://localhost/phpmyadmin**.
2. Go to the **Import** tab.
3. Click **Choose file** and select `database/database.sql` (inside `/opt/lampp/htdocs/impresiones`).
4. Click **Go** (at the bottom).
5. The **impresiones_comedor** database will appear on the left.

---

## 6. Configure (optional)

Open `config.php` in a text editor:

```bash
nano /opt/lampp/htdocs/impresiones/config.php
```

| Line | Purpose |
|---|---|
| `define('NEGOCIO', 'Impresiones y Comedor');` | Business name shown on screen and on the ticket |
| `define('LUGAR', 'Apopa, San Salvador');` | Address printed on the ticket |
| `define('DB_PASS', '');` | MySQL password. Empty by default in XAMPP; change it only if you set a MySQL password |

Save with `Ctrl + O`, `Enter`, and exit with `Ctrl + X`.

---

## 7. First login

1. Open **http://localhost/impresiones**.
2. Log in with:
   - **Username:** `admin`
   - **Password:** `admin123`
3. ⚠️ **Change the password** right away with the **Contraseña** (Password) link at the top right.
4. Go to **Catálogo** (Catalog) and replace the sample prices with the real ones.

---

## 8. Use it from another device (phone, another PC)

If the other device is on the **same Wi-Fi network**:

1. On the XAMPP computer, find its IP address:
   ```bash
   hostname -I
   ```
   Example: `192.168.1.25`
2. On the other device open: **http://192.168.1.25/impresiones**

---

## 9. Start XAMPP automatically (optional)

So you don't have to start it every time the computer boots:

```bash
sudo crontab -e
```

Add this line at the end and save:

```
@reboot /opt/lampp/lampp start
```

---

## 10. Troubleshooting

| Problem | Solution |
|---|---|
| **"No se pudo conectar a la base de datos"** (could not connect) | Start MySQL: `sudo /opt/lampp/lampp start`. Check that you imported `database.sql`. |
| **"Access denied for user 'root'"** | MySQL has a password. Put it in `DB_PASS` in `config.php`. |
| **"Unknown database 'impresiones_comedor'"** | The database has not been imported (step 5). |
| **Blank page or 403 error** | Fix permissions: `sudo chown -R $USER:$USER /opt/lampp/htdocs/impresiones` |
| **"Not Found" (404)** | Make sure the folder is named `impresiones` inside `/opt/lampp/htdocs/`. |
| **Apache won't start (port 80 in use)** | `sudo systemctl stop apache2` and start XAMPP again. |
| **MySQL won't start (port 3306 in use)** | `sudo systemctl stop mysql` and start XAMPP again. |
| **Forgot the admin password** | See *Reset a password* in the **Technical Manual**. |

---

## 11. Uninstall

```bash
/opt/lampp/bin/mysql -u root -e "DROP DATABASE impresiones_comedor;"
sudo rm -rf /opt/lampp/htdocs/impresiones
```
