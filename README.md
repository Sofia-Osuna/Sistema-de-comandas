# Restaurante Comandas

Sistema web de gestión de pedidos para restaurante, desarrollado en **PHP puro** (sin frameworks) como proyecto de la materia de Seguridad Informática.

## ¿Qué problema resuelve?

Los meseros toman pedidos en papel y los llevan a cocina, lo que causa errores de comunicación, mesas olvidadas y cuentas mal sumadas. Este sistema digitaliza el flujo mesero → cocina → caja:

- Los meseros capturan el pedido por mesa y se envía directo a cocina.
- El chef ve los pedidos agrupados por mesa, con tiempo de espera.
- La cuenta se calcula automáticamente en el servidor.

## Roles

| Rol | Permisos |
|---|---|
| Admin | Gestiona usuarios, menú y ve la bitácora |
| Mesero | Abre mesas y toma pedidos |
| Cocina | Ve pedidos y cambia su estado |
| Cajero | Cierra cuentas y cobra |

## Equipo
- Lizbeth
- Gema
- Yahir
- Sofía
