-- Ehhh no le piensen mucho
-- Crean una bd en xampp llamada "comandas", y copian y pegan esto en el apartado de sql
CREATE TABLE roles (
    pk_rol  INT         NOT NULL AUTO_INCREMENT,
    rol     VARCHAR(20) NOT NULL,
    PRIMARY KEY (pk_rol),
    UNIQUE KEY uq_roles_rol (rol)
) ENGINE=InnoDB;

CREATE TABLE categorias (
    pk_categoria  INT         NOT NULL AUTO_INCREMENT,
    categoria     VARCHAR(30) NOT NULL,
    PRIMARY KEY (pk_categoria),
    UNIQUE KEY uq_categorias_categoria (categoria)
) ENGINE=InnoDB;

CREATE TABLE metodos_pago (
    pk_metodo_pago  INT         NOT NULL AUTO_INCREMENT,
    metodo          VARCHAR(30) NOT NULL,
    estatus         CHAR(1)     NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = inactivo',
    PRIMARY KEY (pk_metodo_pago),
    UNIQUE KEY uq_metodos_pago_metodo (metodo),
    CONSTRAINT ck_metodos_pago_estatus CHECK (estatus IN ('0','1'))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 2. Usuarios
-- ---------------------------------------------------------------------

CREATE TABLE usuarios (
    pk_usuario         INT          NOT NULL AUTO_INCREMENT,
    fk_rol             INT          NOT NULL,
    nombres            VARCHAR(50)  NOT NULL,
    apellido_paterno   VARCHAR(40)  NOT NULL,
    apellido_materno   VARCHAR(40)  NULL,
    correo             VARCHAR(100) NOT NULL,
    password_hash      VARCHAR(255) NULL COMMENT 'password_hash() de PHP; NULL si solo entra con Google',
    google_id          VARCHAR(50)  NULL,
    intentos_fallidos  INT          NOT NULL DEFAULT 0,
    bloqueado_hasta    DATETIME     NULL DEFAULT NULL,
    ultimo_acceso      DATETIME     NULL DEFAULT NULL,
    estatus            CHAR(1)      NOT NULL DEFAULT '1' COMMENT '1 = activo, 0 = desactivado',
    PRIMARY KEY (pk_usuario),
    UNIQUE KEY uq_usuarios_correo (correo),
    UNIQUE KEY uq_usuarios_google_id (google_id),
    CONSTRAINT fk_usuarios_rol
        FOREIGN KEY (fk_rol) REFERENCES roles (pk_rol)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT ck_usuarios_estatus CHECK (estatus IN ('0','1')),
    CONSTRAINT ck_usuarios_intentos CHECK (intentos_fallidos >= 0)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 3. Menu
-- ---------------------------------------------------------------------

CREATE TABLE productos (
    pk_producto   INT           NOT NULL AUTO_INCREMENT,
    fk_categoria  INT           NOT NULL,
    producto      VARCHAR(50)   NOT NULL,
    descripcion   VARCHAR(255)  NULL,
    precio        DECIMAL(10,2) NOT NULL,
    imagen        VARCHAR(255)  NULL DEFAULT NULL,
    stock         INT           NOT NULL DEFAULT 0,
    estado        VARCHAR(20)   NOT NULL DEFAULT 'disponible' COMMENT 'disponible | agotado',
    estatus       CHAR(1)       NOT NULL DEFAULT '1' COMMENT '1 = activo en el menu, 0 = dado de baja',
    PRIMARY KEY (pk_producto),
    CONSTRAINT fk_productos_categoria
        FOREIGN KEY (fk_categoria) REFERENCES categorias (pk_categoria)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT ck_productos_precio CHECK (precio >= 0),
    CONSTRAINT ck_productos_stock CHECK (stock >= 0),
    CONSTRAINT ck_productos_estado CHECK (estado IN ('disponible','agotado')),
    CONSTRAINT ck_productos_estatus CHECK (estatus IN ('0','1'))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 4. Mesas
-- ---------------------------------------------------------------------

CREATE TABLE mesas (
    pk_mesa               INT         NOT NULL AUTO_INCREMENT,
    cod_mesa              VARCHAR(10) NOT NULL,
    fk_usuario_encargado  INT         NULL DEFAULT NULL COMMENT 'Mesero asignado; el backend valida que tenga rol mesero',
    estado                VARCHAR(20) NOT NULL DEFAULT 'libre' COMMENT 'libre | ocupada | esperando_cuenta',
    estatus               CHAR(1)     NOT NULL DEFAULT '1' COMMENT '1 = disponible, 0 = fuera de servicio',
    PRIMARY KEY (pk_mesa),
    UNIQUE KEY uq_mesas_cod_mesa (cod_mesa),
    CONSTRAINT fk_mesas_usuario_encargado
        FOREIGN KEY (fk_usuario_encargado) REFERENCES usuarios (pk_usuario)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT ck_mesas_estado CHECK (estado IN ('libre','ocupada','esperando_cuenta')),
    CONSTRAINT ck_mesas_estatus CHECK (estatus IN ('0','1'))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 5. Pedidos
-- ---------------------------------------------------------------------

CREATE TABLE pedidos (
    pk_pedido           INT          NOT NULL AUTO_INCREMENT,
    cod_pedido          VARCHAR(10)  NOT NULL,
    fk_mesa             INT          NOT NULL,
    fk_usuario          INT          NOT NULL COMMENT 'Mesero que tomo el pedido',
    estado              VARCHAR(20)  NOT NULL DEFAULT 'abierto' COMMENT 'abierto | pagado | cancelado',
    motivo_cancelacion  VARCHAR(150) NULL DEFAULT NULL,
    fecha_hora          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (pk_pedido),
    UNIQUE KEY uq_pedidos_cod_pedido (cod_pedido),
    CONSTRAINT fk_pedidos_mesa
        FOREIGN KEY (fk_mesa) REFERENCES mesas (pk_mesa)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_pedidos_usuario
        FOREIGN KEY (fk_usuario) REFERENCES usuarios (pk_usuario)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT ck_pedidos_estado CHECK (estado IN ('abierto','pagado','cancelado'))
) ENGINE=InnoDB;

CREATE TABLE detalles_pedido (
    pk_detalle_pedido  INT           NOT NULL AUTO_INCREMENT,
    fk_pedido          INT           NOT NULL,
    fk_producto        INT           NOT NULL,
    cantidad           INT           NOT NULL,
    precio_unitario    DECIMAL(10,2) NOT NULL COMMENT 'Precio del producto al momento de pedir',
    notas              VARCHAR(150)  NULL DEFAULT NULL,
    estatus            VARCHAR(20)   NOT NULL DEFAULT 'pendiente' COMMENT 'pendiente | preparando | listo | entregado | cancelado',
    PRIMARY KEY (pk_detalle_pedido),
    CONSTRAINT fk_detalles_pedido_pedido
        FOREIGN KEY (fk_pedido) REFERENCES pedidos (pk_pedido)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_detalles_pedido_producto
        FOREIGN KEY (fk_producto) REFERENCES productos (pk_producto)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT ck_detalles_pedido_cantidad CHECK (cantidad > 0),
    CONSTRAINT ck_detalles_pedido_precio CHECK (precio_unitario >= 0),
    CONSTRAINT ck_detalles_pedido_estatus CHECK (estatus IN ('pendiente','preparando','listo','entregado','cancelado'))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 6. Ventas (cobro de un pedido; relacion 1 a 1 con pedidos)
-- ---------------------------------------------------------------------

CREATE TABLE ventas (
    pk_venta        INT           NOT NULL AUTO_INCREMENT,
    fk_pedido       INT           NOT NULL,
    fk_cajero       INT           NOT NULL,
    fk_metodo_pago  INT           NOT NULL,
    fecha           DATE          NOT NULL,
    hora            TIME          NOT NULL,
    total           DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (pk_venta),
    UNIQUE KEY uq_ventas_pedido (fk_pedido),
    CONSTRAINT fk_ventas_pedido
        FOREIGN KEY (fk_pedido) REFERENCES pedidos (pk_pedido)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ventas_cajero
        FOREIGN KEY (fk_cajero) REFERENCES usuarios (pk_usuario)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ventas_metodo_pago
        FOREIGN KEY (fk_metodo_pago) REFERENCES metodos_pago (pk_metodo_pago)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT ck_ventas_total CHECK (total >= 0)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- 7. Bitacora
-- ---------------------------------------------------------------------

CREATE TABLE bitacora (
    pk_bitacora  INT          NOT NULL AUTO_INCREMENT,
    fk_usuario   INT          NULL DEFAULT NULL COMMENT 'NULL si el intento fue con un correo inexistente',
    accion       VARCHAR(30)  NOT NULL,
    detalle      VARCHAR(255) NULL DEFAULT NULL,
    ip           VARCHAR(45)  NULL DEFAULT NULL,
    fecha        DATE         NOT NULL,
    hora         TIME         NOT NULL,
    PRIMARY KEY (pk_bitacora),
    CONSTRAINT fk_bitacora_usuario
        FOREIGN KEY (fk_usuario) REFERENCES usuarios (pk_usuario)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;