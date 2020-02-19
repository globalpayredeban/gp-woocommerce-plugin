# GlobalPay WooCommerce Plugin
## 1. Pre-requisitos
### 1.1. Wordpress
La documentación necesaria para instalar y configurar Wordpress se encuentra en el siguiente link: [Install Wordpress](https://wordpress.org/support/article/how-to-install-wordpress/). Todos los requisitos mínimos (PHP y MySQL) deben ser cumplidos para que el plugin desarrollado pueda funcionar correctamente.

### 1.2. WooCommerce
La documentación necesaria para instalar WooCommerce se encuentra en el siguiente link: [Install WooCommerce](https://docs.woocommerce.com/documentation/plugins/woocommerce/getting-started/installation-and-updating/). Allí también se encuentra información necesaria para hacer troubleshooting relacionado con la instalación.

## 2. Repositorio en Git
El proyecto entero deberá estar comprimido en formato **.zip** para
poder ser subido como un plugin de Wordpress. Link: [GlobalPay WooCommerce Plugin](https://github.com/globalpayredeban/gp-woocommerce-plugin).

## 3. Instalación en Wordpress
El proyecto funciona como un plugin de Wordpress que se conecta a otro plugin de Wordpress que es WooCommerce. Así que cuando se instala y
activa, se utilizan *hooks* y *actions* de WooCommerce y Wordpress.
### 3.1 Instalación y activación por medio del Wordpress Admin
Cuando ya tengamos el proyecto comprimido en formato .zip, procedemos a la instalación por medio del Wordpress Admin.
1. El primer paso será hacer login dentro del **Wordpress Admin** como administrador.
2. Estando en la pantalla principal del admin damos click en la pestaña de **Plugins**.
3. Dentro de la pantalla de **Plugins** damos click en **Add New**.
4. Dentro de la pantalla de **Add Plugins** damos click en **Upload Plugin**.
5. Se desplegará la opción para subir nuestro plugin en formato **.zip**. Lo subimos y damos click en el botón **Install Now**.
6. Seremos redireccionados a la pantalla de instalación del plugin.
7. Esperamos a obtener el mensaje **Plugin Installed Successfully** y damos click en el botón de **Activate Plugin**.
8. Seremos redireccionados a la pantalla de **Plugins** dónde veremos nuestro plugin instalado y activado.

### 3.2 Creación y Eliminación de la Base de Datos
Por default, cuando se **Activa** y **Desactiva** el plugin, se crea y elimina la tabla correspondiente a la base de datos del proyecto. La tabla se creará en la
base de datos que se configuró en la instalación de Wordpress.
El nómbre de la tabla se compone por el prefijo de tablas configurado en la instalación de Wordpress (por default **wp_**) y el nombre **globalpay_plugin**.
La base de datos almacena los registros con detalle de todas las transacciones que se hagan en el comercio. Los datos mas relevantes que se
almacenan son:
* **orderId** el cual es el identificador único de las transacciones en el comercio.
* **Transaction Code** el cual es el identificador único de las transacciones en GlobalPay

### 3.3 Selección del Idioma
El idioma del plugin es seleccionado dinámicamente de acuerdo al idioma que esté configurado en Wordpress. Los idiomas que están disponibles son:
* Español CO
* Inglés (Por defecto)

Es importante aclarar que si el idioma de Wordpress es alguno que no está en la lista, se seleccionará inglés por default. Ésto no afecta al idioma del
checkout el cual es independiente de Wordpress.

## 4. Activación y Configuración del Plugin en WooCommerce
Después de haberse instalado nuestro plugin en Wordpress debemos proceder a configurarlo en el **Admin** de **WooCommerce**.
Éste se encuentra en la pestaña de **WooCommerce** del dashboard de **Wordpress**. Luego damos click en la opción de **Settings** y posteriormente en
la pestaña de **Payments**.

### 4.1 Activación del Gateway
Para activar nuestro gateway dentro de nuestro comercio WooCommerce, necesitamos estár dentro de la pestaña **WooCommerce->Settings->Payments** y veremos nuestro plugin
instalado y detectado.
Para habilitarlo deberemos activar el botón **Enabled**. Ésta habilitación del plugin es distinta a la de Wordpress la cual hicimos anteriormente.

### 4.2 Configuraciones del Gateway en el Admin de WooCommerce
Al habilitar nuestro plugin en el admin de WooCommerce, tendremos algunas opciones para configurar. Para hacerlo damos click al botón Manage que
aparecerá al costado de nuestro plugin.
Las opciones para configurar son las siguientes:
* **Staging Environment:** Cuando está habilitada, él plugin apuntará al servidor de pruebas de GlobalPay.
* **Title:** Ésta opción configura el texto que verá el cliente en la ventana de checkout junto al logo de GlobalPay.
* **Customer Message:** Ésta opción configura el mensaje que verá el cliente en la ventana del checkout cuando seleccione GlobalPay como método de
pago.
* **Checkout Language:** Ésta opción selecciona el idioma que se mostrará en la ventana del checkout. Las opciones disponibles son Español,
Portugués e Inglés (por default).
* **App Code Client:** Identificador único en GlobalPay.
* **App Key Client:** Clave utilizada para cifrar la comunicación con GlobalPay.
* **App Code Server:** Identificador único en el servidor de GlobalPay.
* **App Key Server:** Clave usada para la comunicación con el servidor de GlobalPay.

## 5. Selección del Plugin en el Checkout de la Tienda
Cuando tengamos todo nuestro plugin activado y configurado en WooCommerce, lo veremos disponible para seleccionarse por los clientes en la
página de **Checkout** de nuestro comercio.
Sólo basta con seleccionarlo, llenar los **Billing Details** y dar click en el botón de **Place Order**.
Dando click llegaremos a la ventana de **Order-Pay** o **Pay For Order** en el cual veremos un resumen de nuestro pedido. Se mostrará el botón de Purchase
el cual abrirá el **checkout** de Paymentez o GlobalPay.

## 6. Proceso Para hacer un Refund
1. El proceso de refund empezará en la ventana principal del **dashboard** de Wordpress.
2. Seleccionamos la pestaña de **WooCommerce** y hacemos click en la opción **Orders**.
3. Seleccionamos el pedido que queremos reembolsar y se abrirá la ventana de **Edit Order**.
4. En el detalle del item encontraremos el botón de **Refund**, hacemos click y se desplegarán las opciones del refund.
5. Tecleamos la cantidad a reembolsar y damos click al botón de **Refund Manually**. 
6. El estatus dentro de WooCommerce cambiará y también el estatus en
GlobalPay.
