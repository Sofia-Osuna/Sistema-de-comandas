-- aqui van a estar todos los procedimientos almacenados, mientras que esten pueden usar consultas normales, 
-- Luego simplemente se cambia a la llamadada del procedimiento
-- Igual a esa vez que nos puso a hacer un procedimiento y luego alterar el codigo para llamarlo

-- Usuario
DELIMITER $$
CREATE PROCEDURE insertar_usuario (
    IN p_fk_rol INT,
    IN p_nombres VARCHAR(50),
    IN p_apellido_paterno VARCHAR(40),
    IN p_apellido_materno VARCHAR(40),
    IN p_correo VARCHAR(100),
    IN p_password_hash VARCHAR(255),
    IN p_google_id VARCHAR(50)
)
BEGIN
    INSERT INTO usuarios (
        fk_rol,
        nombres,
        apellido_paterno,
        apellido_materno,
        correo,
        password_hash,
        google_id
    ) VALUES (
        p_fk_rol,
        p_nombres,
        p_apellido_paterno,
        p_apellido_materno,
        p_correo,
        p_password_hash,
        p_google_id
    );
END $$
DELIMITER ;

-- productos
DELIMITER $$
CREATE PROCEDURE insertar_producto (
    IN p_fk_categoria INT,
    IN p_producto VARCHAR(50),
    IN p_descripcion VARCHAR(255),
    IN p_precio DECIMAL(10,2),
    IN p_imagen VARCHAR(255),
    IN p_stock INT,
    IN p_estado VARCHAR(20)
)
BEGIN
    INSERT INTO productos (
        fk_categoria,
        producto,
        descripcion,
        precio,
        imagen,
        stock,
        estado
    ) VALUES (
        p_fk_categoria,
        p_producto,
        p_descripcion,
        p_precio,
        p_imagen,
        p_stock,
        p_estado
    );
END $$
DELIMITER ;

-- Mesas

DELIMITER $$
CREATE PROCEDURE insertar_mesa (
    IN p_cod_mesa VARCHAR(10),
    IN p_fk_usuario_encargado INT,
    IN p_estado VARCHAR(20)
)
BEGIN
    INSERT INTO mesas (
        cod_mesa,
        fk_usuario_encargado,
        estado
    ) VALUES (
        p_cod_mesa,
        p_fk_usuario_encargado,
        p_estado
    );
END $$
DELIMITER ;

-- categorias

DELIMITER $$
CREATE PROCEDURE insertar_categoria (
    IN p_categoria VARCHAR(30)
)
BEGIN
    INSERT INTO categorias (categoria)
    VALUES (p_categoria);
END $$
DELIMITER ;

-- metodos pagos

DELIMITER $$
CREATE PROCEDURE insertar_metodo_pago (
    IN p_metodo VARCHAR(30),
    IN p_estatus CHAR(1)
)
BEGIN
    INSERT INTO metodos_pago (metodo, estatus)
    VALUES (p_metodo, p_estatus);
END $$
DELIMITER ;

-- pedidos
DELIMITER $$
CREATE PROCEDURE insertar_pedido (
    IN p_cod_pedido VARCHAR(10),
    IN p_fk_mesa INT,
    IN p_fk_usuario INT,
    IN p_estado VARCHAR(20),
    IN p_motivo_cancelacion VARCHAR(150)
)
BEGIN
    INSERT INTO pedidos (
        cod_pedido,
        fk_mesa,
        fk_usuario,
        estado,
        motivo_cancelacion
    ) VALUES (
        p_cod_pedido,
        p_fk_mesa,
        p_fk_usuario,
        p_estado,
        p_motivo_cancelacion
    );
END $$
DELIMITER ;

-- EDITARRR -------------------------------
-- usuarios
DELIMITER $$
CREATE PROCEDURE editar_usuario (
    IN p_pk_usuario INT,
    IN p_fk_rol INT,
    IN p_nombres VARCHAR(50),
    IN p_apellido_paterno VARCHAR(40),
    IN p_apellido_materno VARCHAR(40),
    IN p_correo VARCHAR(100),
    IN p_estatus CHAR(1)
)
BEGIN
    UPDATE usuarios
    SET fk_rol = p_fk_rol,
        nombres = p_nombres,
        apellido_paterno = p_apellido_paterno,
        apellido_materno = p_apellido_materno,
        correo = p_correo,
        estatus = p_estatus
    WHERE pk_usuario = p_pk_usuario;
END $$
DELIMITER ;

--productos
DELIMITER $$
CREATE PROCEDURE editar_producto (
    IN p_pk_producto INT,
    IN p_fk_categoria INT,
    IN p_producto VARCHAR(50),
    IN p_descripcion VARCHAR(255),
    IN p_precio DECIMAL(10,2),
    IN p_imagen VARCHAR(255),
    IN p_stock INT,
    IN p_estado VARCHAR(20),
    IN p_estatus CHAR(1)
)
BEGIN
    UPDATE productos
    SET fk_categoria = p_fk_categoria,
        producto = p_producto,
        descripcion = p_descripcion,
        precio = p_precio,
        imagen = p_imagen,
        stock = p_stock,
        estado = p_estado,
        estatus = p_estatus
    WHERE pk_producto = p_pk_producto;
END $$
DELIMITER ;

--mesas
DELIMITER $$
CREATE PROCEDURE editar_mesa (
    IN p_pk_mesa INT,
    IN p_cod_mesa VARCHAR(10),
    IN p_fk_usuario_encargado INT,
    IN p_estado VARCHAR(20),
    IN p_estatus CHAR(1)
)
BEGIN
    UPDATE mesas
    SET cod_mesa = p_cod_mesa,
        fk_usuario_encargado = p_fk_usuario_encargado,
        estado = p_estado,
        estatus = p_estatus
    WHERE pk_mesa = p_pk_mesa;
END $$
DELIMITER ;

--categorias
DELIMITER $$
CREATE PROCEDURE editar_categoria (
    IN p_pk_categoria INT,
    IN p_categoria VARCHAR(30)
)
BEGIN
    UPDATE categorias
    SET categoria = p_categoria
    WHERE pk_categoria = p_pk_categoria;
END $$
DELIMITER ;

-- metodos pagos
DELIMITER $$
CREATE PROCEDURE editar_metodo_pago (
    IN p_pk_metodo_pago INT,
    IN p_metodo VARCHAR(30),
    IN p_estatus CHAR(1)
)
BEGIN
    UPDATE metodos_pago
    SET metodo = p_metodo,
        estatus = p_estatus
    WHERE pk_metodo_pago = p_pk_metodo_pago;
END $$
DELIMITER ;

-- pedidos
DELIMITER $$
CREATE PROCEDURE editar_pedido (
    IN p_pk_pedido INT,
    IN p_cod_pedido VARCHAR(10),
    IN p_fk_mesa INT,
    IN p_fk_usuario INT,
    IN p_estado VARCHAR(20),
    IN p_motivo_cancelacion VARCHAR(150)
)
BEGIN
    UPDATE pedidos
    SET cod_pedido = p_cod_pedido,
        fk_mesa = p_fk_mesa,
        fk_usuario = p_fk_usuario,
        estado = p_estado,
        motivo_cancelacion = p_motivo_cancelacion
    WHERE pk_pedido = p_pk_pedido;
END $$
DELIMITER ;

