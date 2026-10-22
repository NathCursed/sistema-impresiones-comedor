# Diagramas del sistema
**Sistema de Impresiones y Comedor**

GitHub dibuja automáticamente los diagramas de este archivo (formato Mermaid). Las versiones en imagen PNG están en `docs/img/diagramas/es/` para pegarlas en Word o en presentaciones. El código fuente de cada diagrama está en `docs/diagramas/es/`.


---

## 1. Diagrama Entidad-Relación (base de datos)

Muestra las 4 tablas de la base de datos `impresiones_comedor` y cómo se relacionan.

```mermaid
erDiagram
    USUARIOS ||--o{ PEDIDOS : "registra"
    PEDIDOS ||--|{ PEDIDO_DETALLE : "contiene"
    CATALOGO ||--o{ PEDIDO_DETALLE : "aparece en"

    USUARIOS {
        int id PK
        varchar usuario UK "nombre de usuario"
        varchar nombre
        varchar clave_hash "contraseña cifrada"
        timestamp creado
    }
    CATALOGO {
        int id PK
        enum area "Impresiones o Comida"
        varchar categoria
        varchar nombre
        decimal precio
        varchar unidad "página, plato..."
        tinyint activo "1 disponible, 0 no"
    }
    PEDIDOS {
        int id PK
        varchar cliente
        varchar telefono
        varchar tipo "Para llevar, Comer aquí..."
        varchar estado "Pendiente ... Entregado"
        text notas
        decimal total
        int usuario_id FK
        timestamp fecha
    }
    PEDIDO_DETALLE {
        int id PK
        int pedido_id FK
        int catalogo_id FK
        int cantidad
        decimal precio_unitario
        decimal subtotal
    }
```

Imagen: [`1_entidad_relacion.png`](../img/diagramas/es/1_entidad_relacion.png)


---

## 2. Arquitectura del sistema

El usuario usa el navegador; Apache ejecuta PHP; PHP lee y guarda en MySQL.

```mermaid
flowchart LR
    U["Usuario<br/>(cajero / dueño)"] -->|Navegador web| A
    subgraph XAMPP["Servidor XAMPP (Ubuntu)"]
        A["Apache<br/>servidor web"] --> P["PHP 8<br/>páginas .php"]
        P -->|PDO + consultas preparadas| M[("MySQL / MariaDB<br/>BD: impresiones_comedor")]
    end
    P --> C["config.php<br/>conexión, sesión, seguridad"]
    P --> V["assets/<br/>style.css, app.js"]
```

Imagen: [`2_arquitectura.png`](../img/diagramas/es/2_arquitectura.png)


---

## 3. Diagrama de flujo: registrar un pedido

Pasos desde que llega el cliente hasta que se entrega el pedido.

```mermaid
flowchart TD
    I([Inicio]) --> L{"¿Sesión iniciada?"}
    L -- No --> LG["Iniciar sesión<br/>usuario y contraseña"] --> L
    L -- Sí --> N["Nuevo pedido"]
    N --> D["Escribir cliente, teléfono,<br/>tipo y notas"]
    D --> S["Poner cantidades de<br/>impresiones y/o comida"]
    S --> T["El sistema muestra<br/>el total en vivo"]
    T --> G["Guardar pedido"]
    G --> V{"¿Datos válidos?"}
    V -- No --> E["Mostrar error"] --> S
    V -- Sí --> BD[("Guardar pedido y detalle<br/>en la base de datos")]
    BD --> K["Ver pedido / imprimir ticket"]
    K --> ES["Cambiar estado hasta<br/>Entregado"]
    ES --> F([Fin])
```

Imagen: [`3_flujo_pedido.png`](../img/diagramas/es/3_flujo_pedido.png)


---

## 4. Diagrama de estados del pedido

Estados por los que pasa un pedido.

```mermaid
stateDiagram-v2
    [*] --> Pendiente : se crea el pedido
    Pendiente --> En_proceso : se empieza a preparar
    En_proceso --> Listo : terminado
    Listo --> Entregado : el cliente lo recoge
    Pendiente --> Cancelado
    En_proceso --> Cancelado
    Listo --> Cancelado
    Entregado --> [*]
    Cancelado --> [*]
    En_proceso : En proceso
    Cancelado : Cancelado (no suma en ventas)
```

Imagen: [`4_estados_pedido.png`](../img/diagramas/es/4_estados_pedido.png)


---

## 5. Casos de uso

Lo que el usuario puede hacer en el sistema.

```mermaid
flowchart LR
    U(("Usuario<br/>del negocio"))
    subgraph SIS["Sistema de Impresiones y Comedor"]
        C1(["Iniciar / cerrar sesión"])
        C2(["Registrar pedido"])
        C3(["Ver y buscar pedidos"])
        C4(["Cambiar estado del pedido"])
        C5(["Imprimir ticket"])
        C6(["Administrar catálogo"])
        C7(["Ver reportes de ventas"])
        C8(["Cambiar contraseña"])
    end
    U --- C1 & C2 & C3 & C4 & C5 & C6 & C7 & C8
```

Imagen: [`5_casos_de_uso.png`](../img/diagramas/es/5_casos_de_uso.png)


---

## 6. Mapa de navegación

Cómo se conectan las páginas del sistema.

```mermaid
flowchart TD
    LOGIN["login.php<br/>Iniciar sesión"] --> INICIO["index.php<br/>Inicio / resumen del día"]
    INICIO --> NUEVO["pedido_nuevo.php<br/>Nuevo pedido"]
    INICIO --> PED["pedidos.php<br/>Lista de pedidos"]
    INICIO --> CAT["catalogo.php<br/>Catálogo"]
    INICIO --> REP["reportes.php<br/>Reportes"]
    INICIO --> CLAVE["clave.php<br/>Cambiar contraseña"]
    INICIO --> SALIR["logout.php<br/>Salir"]
    NUEVO --> VER["pedido_ver.php<br/>Detalle y ticket"]
    PED --> VER
    VER --> EST["pedido_estado.php<br/>Cambia el estado"]
    CAT --> FORM["catalogo_form.php<br/>Agregar / editar"]
    CAT --> ELIM["catalogo_eliminar.php<br/>Eliminar / desactivar"]
```

Imagen: [`6_mapa_navegacion.png`](../img/diagramas/es/6_mapa_navegacion.png)
