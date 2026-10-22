# System Diagrams
**Printing & Food System**

GitHub renders the diagrams in this file automatically (Mermaid format). PNG versions are in `docs/img/diagramas/en/` for Word documents or slides. Each diagram's source code is in `docs/diagramas/en/`.

> Database table and field names are in Spanish because that is how they appear in the code (e.g. `pedidos` = orders, `catalogo` = catalog).


---

## 1. Entity-Relationship Diagram (database)

The 4 tables of the `impresiones_comedor` database and their relationships.

```mermaid
erDiagram
    USUARIOS ||--o{ PEDIDOS : "creates"
    PEDIDOS ||--|{ PEDIDO_DETALLE : "contains"
    CATALOGO ||--o{ PEDIDO_DETALLE : "appears in"

    USUARIOS {
        int id PK
        varchar usuario UK "username"
        varchar nombre "full name"
        varchar clave_hash "hashed password"
        timestamp creado "created at"
    }
    CATALOGO {
        int id PK
        enum area "Printing or Food"
        varchar categoria "category"
        varchar nombre "name"
        decimal precio "price"
        varchar unidad "unit: page, plate..."
        tinyint activo "1 available, 0 not"
    }
    PEDIDOS {
        int id PK
        varchar cliente "customer"
        varchar telefono "phone"
        varchar tipo "order type"
        varchar estado "status"
        text notas "notes"
        decimal total
        int usuario_id FK
        timestamp fecha "date"
    }
    PEDIDO_DETALLE {
        int id PK
        int pedido_id FK
        int catalogo_id FK
        int cantidad "quantity"
        decimal precio_unitario "unit price"
        decimal subtotal
    }
```

Image: [`1_entity_relationship.png`](../img/diagramas/en/1_entity_relationship.png)


---

## 2. System Architecture

The user works in a web browser; Apache runs PHP; PHP reads and writes to MySQL.

```mermaid
flowchart LR
    U["User<br/>(cashier / owner)"] -->|Web browser| A
    subgraph XAMPP["XAMPP Server (Ubuntu)"]
        A["Apache<br/>web server"] --> P["PHP 8<br/>.php pages"]
        P -->|PDO + prepared statements| M[("MySQL / MariaDB<br/>DB: impresiones_comedor")]
    end
    P --> C["config.php<br/>connection, session, security"]
    P --> V["assets/<br/>style.css, app.js"]
```

Image: [`2_architecture.png`](../img/diagramas/en/2_architecture.png)


---

## 3. Flowchart: registering an order

Steps from the moment a customer arrives until the order is delivered.

```mermaid
flowchart TD
    I([Start]) --> L{"Logged in?"}
    L -- No --> LG["Log in<br/>username and password"] --> L
    L -- Yes --> N["New order"]
    N --> D["Enter customer, phone,<br/>type and notes"]
    D --> S["Enter quantities of<br/>printing and/or food"]
    S --> T["System shows<br/>the live total"]
    T --> G["Save order"]
    G --> V{"Valid data?"}
    V -- No --> E["Show error"] --> S
    V -- Yes --> BD[("Save order and details<br/>in the database")]
    BD --> K["View order / print ticket"]
    K --> ES["Change status until<br/>Delivered"]
    ES --> F([End])
```

Image: [`3_order_flow.png`](../img/diagramas/en/3_order_flow.png)


---

## 4. Order State Diagram

States an order goes through.

```mermaid
stateDiagram-v2
    [*] --> Pendiente : order is created
    Pendiente --> En_proceso : work starts
    En_proceso --> Listo : finished
    Listo --> Entregado : customer picks it up
    Pendiente --> Cancelado
    En_proceso --> Cancelado
    Listo --> Cancelado
    Entregado --> [*]
    Cancelado --> [*]
    Pendiente : Pendiente (Pending)
    En_proceso : En proceso (In progress)
    Listo : Listo (Ready)
    Entregado : Entregado (Delivered)
    Cancelado : Cancelado (Cancelled, not counted in sales)
```

Image: [`4_order_states.png`](../img/diagramas/en/4_order_states.png)


---

## 5. Use Cases

What the user can do in the system.

```mermaid
flowchart LR
    U(("Business<br/>user"))
    subgraph SIS["Printing & Food System"]
        C1(["Log in / log out"])
        C2(["Register order"])
        C3(["View and search orders"])
        C4(["Change order status"])
        C5(["Print ticket"])
        C6(["Manage catalog"])
        C7(["View sales reports"])
        C8(["Change password"])
    end
    U --- C1 & C2 & C3 & C4 & C5 & C6 & C7 & C8
```

Image: [`5_use_cases.png`](../img/diagramas/en/5_use_cases.png)


---

## 6. Site Map

How the system pages are connected.

```mermaid
flowchart TD
    LOGIN["login.php<br/>Log in"] --> INICIO["index.php<br/>Home / today's summary"]
    INICIO --> NUEVO["pedido_nuevo.php<br/>New order"]
    INICIO --> PED["pedidos.php<br/>Order list"]
    INICIO --> CAT["catalogo.php<br/>Catalog"]
    INICIO --> REP["reportes.php<br/>Reports"]
    INICIO --> CLAVE["clave.php<br/>Change password"]
    INICIO --> SALIR["logout.php<br/>Log out"]
    NUEVO --> VER["pedido_ver.php<br/>Details and ticket"]
    PED --> VER
    VER --> EST["pedido_estado.php<br/>Changes status"]
    CAT --> FORM["catalogo_form.php<br/>Add / edit"]
    CAT --> ELIM["catalogo_eliminar.php<br/>Delete / deactivate"]
```

Image: [`6_site_map.png`](../img/diagramas/en/6_site_map.png)
