# Configuración de Google OAuth para La Bartola

Esta guía te ayudará a configurar el inicio de sesión con Google (OAuth 2.0) para tu aplicación La Bartola.

## 📋 Tabla de Contenidos

- [¿Qué es Google OAuth?](#qué-es-google-oauth)
- [Requisitos Previos](#requisitos-previos)
- [Paso 1: Crear Proyecto en Google Cloud Console](#paso-1-crear-proyecto-en-google-cloud-console)
- [Paso 2: Configurar Pantalla de Consentimiento](#paso-2-configurar-pantalla-de-consentimiento)
- [Paso 3: Crear Credenciales OAuth 2.0](#paso-3-crear-credenciales-oauth-20)
- [Paso 4: Configurar la Aplicación](#paso-4-configurar-la-aplicación)
- [Paso 5: Probar el Flujo de OAuth](#paso-5-probar-el-flujo-de-oauth)
- [Solución de Problemas](#solución-de-problemas)
- [Seguridad y Mejores Prácticas](#seguridad-y-mejores-prácticas)

---

## ¿Qué es Google OAuth?

Google OAuth 2.0 permite a los usuarios iniciar sesión en tu aplicación usando su cuenta de Google, sin necesidad de crear una contraseña nueva.

**Ventajas:**
- ✅ Inicio de sesión rápido y seguro
- ✅ No requiere recordar otra contraseña
- ✅ Usa la cuenta de Google asociada al dispositivo
- ✅ Autenticación de dos factores automática (si el usuario la tiene)
- ✅ Datos verificados por Google (email, nombre)

---

## Requisitos Previos

Antes de comenzar, asegúrate de tener:

1. ✅ Una cuenta de Google (Gmail)
2. ✅ Acceso a [Google Cloud Console](https://console.cloud.google.com/)
3. ✅ La aplicación La Bartola funcionando localmente
4. ✅ Conocer la URL base de tu aplicación (ej: `http://localhost:8080`)

---

## Paso 1: Crear Proyecto en Google Cloud Console

### 1.1 Acceder a Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Inicia sesión con tu cuenta de Google

### 1.2 Crear un Nuevo Proyecto

1. Haz clic en el selector de proyectos (arriba a la izquierda, al lado del logo de Google Cloud)
2. Haz clic en **"Nuevo Proyecto"** (NEW PROJECT)
3. Completa los datos:
   - **Nombre del proyecto**: `La Bartola OAuth` (o el nombre que prefieras)
   - **Organización**: Puedes dejarla vacía si no tienes una
   - **Ubicación**: Puedes dejarla como "Sin organización"
4. Haz clic en **"Crear"** (CREATE)
5. Espera unos segundos mientras Google crea el proyecto
6. Selecciona el proyecto recién creado desde el selector de proyectos

**Captura de pantalla de referencia:**
```
┌─────────────────────────────────────────┐
│ Seleccionar un proyecto                  │
├─────────────────────────────────────────┤
│ 🔍 Buscar proyectos...                  │
│                                          │
│ ┌─────────────────────────────────────┐ │
│ │ ➕ NUEVO PROYECTO                    │ │
│ └─────────────────────────────────────┘ │
│                                          │
│ Nombre: La Bartola OAuth                │
│ ID del proyecto: la-bartola-oauth-xxxxx │
│                                          │
│ [Cancelar]  [Crear]                     │
└─────────────────────────────────────────┘
```

---

## Paso 2: Configurar Pantalla de Consentimiento

La pantalla de consentimiento es lo que los usuarios verán cuando inicien sesión con Google.

### 2.1 Acceder a la Configuración

1. En el menú lateral izquierdo, haz clic en **"☰"** (menú hamburguesa)
2. Ve a **"APIs y servicios"** → **"Pantalla de consentimiento de OAuth"**
   - O busca "OAuth consent screen" en la barra de búsqueda superior

### 2.2 Seleccionar Tipo de Usuario

Selecciona **"Externo"** (External):
- ✅ Permite que cualquier usuario con cuenta de Google inicie sesión
- ✅ Ideal para aplicaciones públicas como restaurantes

Haz clic en **"Crear"** (CREATE)

### 2.3 Configurar la Pantalla de Consentimiento

**Información de la Aplicación:**

1. **Nombre de la aplicación**: `La Bartola`
2. **Correo electrónico de asistencia al usuario**: Tu email (ej: `admin@labartola.com` o tu Gmail)
3. **Logo de la aplicación** (opcional): Puedes subir el logo de La Bartola (120x120px)

**Información de contacto del desarrollador:**

4. **Direcciones de correo electrónico**: Tu email nuevamente

**Dominios autorizados:**

5. Por ahora, déjalo vacío (solo necesario para producción con dominio propio)

**Enlaces de la aplicación:**

6. **Página principal de la aplicación**: `http://localhost:8080`
7. **Política de privacidad**: `http://localhost:8080/privacidad` (opcional por ahora)
8. **Condiciones de servicio**: `http://localhost:8080/terminos` (opcional por ahora)

Haz clic en **"Guardar y continuar"** (SAVE AND CONTINUE)

### 2.4 Configurar Ámbitos (Scopes)

Los ámbitos definen qué información puede acceder tu aplicación de la cuenta de Google del usuario.

1. Haz clic en **"Agregar o quitar ámbitos"** (ADD OR REMOVE SCOPES)
2. Selecciona los siguientes ámbitos:
   - ✅ `.../auth/userinfo.email` - Ver tu dirección de correo electrónico
   - ✅ `.../auth/userinfo.profile` - Ver tu información personal, incluida la información personal que hayas hecho pública
   - ✅ `openid` - Autenticar usando OpenID Connect

3. Haz clic en **"Actualizar"** (UPDATE)
4. Haz clic en **"Guardar y continuar"** (SAVE AND CONTINUE)

### 2.5 Usuarios de Prueba (Solo Modo Desarrollo)

Si tu app está en modo de prueba (testing), solo estos usuarios podrán iniciar sesión:

1. Haz clic en **"Agregar usuarios"** (ADD USERS)
2. Agrega los emails de los usuarios de prueba (máximo 100)
   - Ejemplo: `tu_email@gmail.com`
3. Haz clic en **"Guardar"** (SAVE)
4. Haz clic en **"Guardar y continuar"** (SAVE AND CONTINUE)

**Nota:** Cuando publiques la app en producción, cambia el estado a "En producción" para permitir cualquier usuario de Google.

### 2.6 Resumen

Revisa la configuración y haz clic en **"Volver al panel"** (BACK TO DASHBOARD)

---

## Paso 3: Crear Credenciales OAuth 2.0

### 3.1 Acceder a Credenciales

1. En el menú lateral, ve a **"APIs y servicios"** → **"Credenciales"**
   - O busca "Credentials" en la barra de búsqueda

### 3.2 Crear ID de Cliente de OAuth

1. Haz clic en **"+ Crear credenciales"** (+ CREATE CREDENTIALS) en la parte superior
2. Selecciona **"ID de cliente de OAuth"** (OAuth client ID)

### 3.3 Configurar el Cliente

1. **Tipo de aplicación**: Selecciona **"Aplicación web"** (Web application)
2. **Nombre**: `La Bartola Web Client` (o el nombre que prefieras)

**Orígenes de JavaScript autorizados:**

3. Haz clic en **"Agregar URI"** (ADD URI)
4. Agrega: `http://localhost:8080`
5. Si usarás HTTPS localmente también agrega: `https://localhost:8080`

**URIs de redireccionamiento autorizados:**

6. Haz clic en **"Agregar URI"** (ADD URI)
7. Agrega: `http://localhost:8080/oauth/google/callback`
8. **¡MUY IMPORTANTE!** Esta URI debe coincidir EXACTAMENTE con la configurada en tu código

**Ejemplo de configuración:**
```
Orígenes de JavaScript autorizados:
┌────────────────────────────────────┐
│ http://localhost:8080              │
└────────────────────────────────────┘

URIs de redireccionamiento autorizados:
┌────────────────────────────────────┐
│ http://localhost:8080/oauth/google/callback │
└────────────────────────────────────┘
```

9. Haz clic en **"Crear"** (CREATE)

### 3.4 Copiar las Credenciales

Aparecerá un modal con tus credenciales:

```
┌─────────────────────────────────────────┐
│ Cliente de OAuth creado                  │
├─────────────────────────────────────────┤
│ ID de cliente:                           │
│ 1234567890-abcdefghijk.apps.googleuser  │
│ content.com                              │
│ [Copiar] 📋                              │
│                                          │
│ Secreto del cliente:                     │
│ GOCSPX-AbCdEfGhIjKlMnOpQrStUvWx         │
│ [Copiar] 📋                              │
│                                          │
│ [Descargar JSON]  [Aceptar]             │
└─────────────────────────────────────────┘
```

**⚠️ IMPORTANTE:**
- **Copia ambas credenciales ahora**, las necesitarás en el siguiente paso
- No compartas estas credenciales públicamente
- Puedes descargar el JSON para guardar una copia segura

---

## Paso 4: Configurar la Aplicación

### 4.1 Actualizar el Archivo .env

1. Abre el archivo `.env` en la raíz de tu proyecto
2. Busca las siguientes líneas:

```ini
#--------------------------------------------------------------------
# GOOGLE OAUTH
#--------------------------------------------------------------------
GOOGLE_CLIENT_ID = 'TU_CLIENT_ID_AQUI.apps.googleusercontent.com'
GOOGLE_CLIENT_SECRET = 'TU_CLIENT_SECRET_AQUI'
```

3. Reemplaza con tus credenciales reales:

```ini
#--------------------------------------------------------------------
# GOOGLE OAUTH
#--------------------------------------------------------------------
GOOGLE_CLIENT_ID = '1234567890-abcdefghijk.apps.googleusercontent.com'
GOOGLE_CLIENT_SECRET = 'GOCSPX-AbCdEfGhIjKlMnOpQrStUvWx'
```

4. **Guarda el archivo**

### 4.2 Verificar la Configuración

La aplicación ya está configurada para usar estas variables. Verifica que el archivo `app/Controllers/OAuth.php` exista y contenga:

```php
$this->googleProvider = new Google([
    'clientId'     => getenv('GOOGLE_CLIENT_ID'),
    'clientSecret' => getenv('GOOGLE_CLIENT_SECRET'),
    'redirectUri'  => base_url('oauth/google/callback'),
]);
```

### 4.3 Reiniciar el Servidor

Si el servidor está corriendo, reinícialo para cargar las nuevas variables de entorno:

```bash
# Detener el servidor (Ctrl + C)
# Iniciar nuevamente
php spark serve
```

O si usas Docker:

```bash
docker-compose restart
```

---

## Paso 5: Probar el Flujo de OAuth

### 5.1 Acceder a la Página de Login

1. Abre tu navegador
2. Ve a: `http://localhost:8080/login`
3. Deberías ver un botón rojo grande que dice **"Continuar con Google"**

### 5.2 Probar el Login

1. Haz clic en **"Continuar con Google"**
2. Serás redirigido a la página de Google
3. Selecciona la cuenta de Google con la que quieres iniciar sesión
4. **Primera vez:** Google te mostrará la pantalla de consentimiento:
   ```
   ┌─────────────────────────────────────────┐
   │ La Bartola quiere acceder a tu          │
   │ cuenta de Google                         │
   ├─────────────────────────────────────────┤
   │ Esto permitirá a La Bartola:            │
   │ • Ver tu dirección de email             │
   │ • Ver tu información personal básica    │
   │                                          │
   │ [Cancelar]  [Continuar]                 │
   └─────────────────────────────────────────┘
   ```
5. Haz clic en **"Continuar"**
6. Serás redirigido de vuelta a La Bartola
7. Deberías ver un mensaje: **"¡Bienvenido, [Tu Nombre]!"**
8. Ya estás logueado con tu cuenta de Google

### 5.3 Verificar en la Base de Datos

Tu usuario debería aparecer en la tabla `users` con:
- **username**: Generado automáticamente desde tu email (ej: `juanperez`)
- **active**: 1

Y en la tabla `auth_identities` con:
- **type**: `google`
- **secret**: Tu Google ID único
- **name**: Tu nombre completo de Google

---

## Solución de Problemas

### Error: "redirect_uri_mismatch"

**Causa:** La URI de redirección no coincide con las configuradas en Google Cloud Console.

**Solución:**
1. Ve a Google Cloud Console → Credenciales
2. Edita tu cliente OAuth
3. Verifica que la URI sea EXACTAMENTE: `http://localhost:8080/oauth/google/callback`
4. Verifica que tu `.env` tenga `app.baseURL = 'http://localhost:8080/'` (con la barra final)

### Error: "Estado de OAuth inválido"

**Causa:** Problema con las cookies de sesión o el estado de OAuth.

**Solución:**
1. Limpia las cookies del navegador para `localhost`
2. Cierra todas las pestañas de `localhost`
3. Intenta nuevamente

### Error: "This app isn't verified"

**Causa:** Tu app está en modo de prueba y el usuario no está en la lista de usuarios de prueba.

**Solución:**
1. Ve a Google Cloud Console → Pantalla de consentimiento de OAuth
2. Agrega el email del usuario a "Usuarios de prueba"
3. O haz clic en "Publicar aplicación" para pasar a producción (requiere verificación de Google)

### No aparece el botón de Google

**Causa:** Las vistas personalizadas no están siendo usadas.

**Solución:**
1. Verifica que `app/Config/Auth.php` tenga:
   ```php
   'login' => '\App\Views\auth\login',
   'register' => '\App\Views\auth\register',
   ```
2. Limpia la caché: `php spark cache:clear`
3. Reinicia el servidor

### El usuario se crea pero no se loguea

**Causa:** Problema con CodeIgniter Shield.

**Solución:**
1. Verifica los logs en `writable/logs/`
2. Asegúrate de que la tabla `users` y `auth_identities` existen
3. Verifica que `auth()->login($user)` se ejecute correctamente

---

## Seguridad y Mejores Prácticas

### 🔒 Proteger las Credenciales

1. **NUNCA** subas el archivo `.env` a Git:
   - Verifica que `.env` esté en `.gitignore`
   - Usa `.env.example` con valores de ejemplo

2. **Regenera las credenciales** si las expones accidentalmente:
   - Ve a Google Cloud Console → Credenciales
   - Elimina el cliente OAuth comprometido
   - Crea uno nuevo

### 🌐 Configuración para Producción

Cuando despliegues a producción:

1. **Actualiza los URIs autorizados:**
   ```
   Origen: https://www.tu-dominio.com
   Callback: https://www.tu-dominio.com/oauth/google/callback
   ```

2. **Publica la aplicación:**
   - Ve a Pantalla de consentimiento de OAuth
   - Haz clic en "Publicar aplicación"
   - Google puede requerir verificación (proceso de revisión)

3. **Actualiza el archivo .env de producción:**
   ```ini
   app.baseURL = 'https://www.tu-dominio.com/'
   GOOGLE_CLIENT_ID = 'nuevo-client-id-produccion'
   GOOGLE_CLIENT_SECRET = 'nuevo-secret-produccion'
   ```

4. **Usa HTTPS obligatoriamente** (Google lo requiere para producción)

### 📊 Monitoreo

Revisa el uso de tu API en Google Cloud Console:
- **APIs y servicios** → **Panel de control**
- Verás gráficos de:
  - Número de logins
  - Errores
  - Cuotas utilizadas

---

## Recursos Adicionales

- [Documentación oficial de Google OAuth 2.0](https://developers.google.com/identity/protocols/oauth2)
- [Guía de configuración de OAuth en Google Cloud](https://support.google.com/cloud/answer/6158849)
- [CodeIgniter Shield Documentation](https://shield.codeigniter.com/)
- [League OAuth2 Client - Google](https://github.com/thephpleague/oauth2-google)

---

## Preguntas Frecuentes

**¿Es gratis usar Google OAuth?**
Sí, Google OAuth es completamente gratuito. No tiene costos ni límites de usuarios.

**¿Los usuarios necesitan tener Gmail?**
Sí, necesitan una cuenta de Google (Gmail, Google Workspace, etc.).

**¿Puedo forzar que solo usen Google?**
Sí, puedes ocultar el formulario de login tradicional y dejar solo el botón de Google. Pero se recomienda mantener ambas opciones.

**¿Qué datos de Google obtengo?**
Solo los datos que el usuario autorice (email, nombre, foto de perfil). No puedes acceder a otros servicios de Google como Gmail o Drive a menos que lo solicites explícitamente.

**¿Funciona en localhost?**
Sí, Google OAuth funciona perfectamente en localhost para desarrollo.

---

## Soporte

Si tienes problemas:
1. Revisa los logs en `writable/logs/`
2. Verifica la consola del navegador (F12) para errores JavaScript
3. Consulta este README
4. Abre un issue en el repositorio de GitHub

---

**¡Listo!** Ahora tus usuarios pueden iniciar sesión con su cuenta de Google de forma rápida y segura. 🎉
