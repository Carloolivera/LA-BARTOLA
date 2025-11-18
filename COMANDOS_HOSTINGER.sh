#!/bin/bash

#############################################
# COMANDOS RÁPIDOS PARA HOSTINGER
# La Bartola - Deploy y Mantenimiento
#############################################

echo "==================================="
echo "COMANDOS PARA HOSTINGER - LA BARTOLA"
echo "==================================="
echo ""

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

#############################################
# 1. PRIMER DEPLOY (Solo la primera vez)
#############################################
primer_deploy() {
    echo -e "${YELLOW}1. PRIMER DEPLOY${NC}"
    echo ""

    # Clonar repositorio
    echo "# 1. Clonar repositorio"
    echo "git clone https://github.com/TU_USUARIO/labartola.git ~/labartola"
    echo ""

    # Instalar dependencias
    echo "# 2. Instalar dependencias"
    echo "cd ~/labartola"
    echo "composer install --no-dev --optimize-autoloader"
    echo ""

    # Configurar .env
    echo "# 3. Configurar .env"
    echo "cp .env.example .env"
    echo "nano .env  # Editar con tus credenciales"
    echo ""

    # Generar clave
    echo "# 4. Generar clave de encriptación"
    echo "php spark key:generate --show"
    echo "# Copiar el resultado y pegarlo en .env"
    echo ""

    # Ejecutar migraciones
    echo "# 5. Ejecutar migraciones"
    echo "php spark migrate --all"
    echo ""

    # Crear usuario admin
    echo "# 6. Crear usuario administrador"
    echo "php spark shield:user create"
    echo "php spark shield:user addgroup admin TU_EMAIL@example.com"
    echo ""

    # Configurar public_html
    echo "# 7. Configurar public_html (elegir UNA opción)"
    echo ""
    echo "OPCIÓN A - Enlace simbólico (recomendado):"
    echo "cd ~"
    echo "rm -rf public_html/*"
    echo "ln -s ~/labartola/public/* ~/public_html/"
    echo "ln -s ~/labartola/public/.htaccess ~/public_html/.htaccess"
    echo ""
    echo "OPCIÓN B - Copiar archivos:"
    echo "cd ~"
    echo "rm -rf public_html/*"
    echo "cp -r ~/labartola/public/* ~/public_html/"
    echo "cp ~/labartola/public/.htaccess ~/public_html/.htaccess"
    echo ""

    # Permisos
    echo "# 8. Configurar permisos"
    echo "cd ~/labartola"
    echo "chmod -R 775 writable/"
    echo "chmod -R 755 public/"
    echo ""

    echo -e "${GREEN}✓ Primer deploy completado!${NC}"
}

#############################################
# 2. ACTUALIZAR CÓDIGO (Deploys siguientes)
#############################################
actualizar() {
    echo -e "${YELLOW}2. ACTUALIZAR CÓDIGO${NC}"
    echo ""

    echo "cd ~/labartola"
    echo "git pull origin main"
    echo "composer install --no-dev --optimize-autoloader"
    echo "php spark migrate"
    echo "php spark cache:clear"
    echo ""

    echo -e "${GREEN}✓ Actualización completada!${NC}"
}

#############################################
# 3. VER LOGS
#############################################
ver_logs() {
    echo -e "${YELLOW}3. VER LOGS${NC}"
    echo ""

    echo "# Ver últimos 50 errores"
    echo "tail -50 ~/labartola/writable/logs/log-*.log"
    echo ""

    echo "# Ver logs en tiempo real"
    echo "tail -f ~/labartola/writable/logs/log-*.log"
    echo ""

    echo "# Buscar un error específico"
    echo "grep -r 'ERROR' ~/labartola/writable/logs/"
}

#############################################
# 4. LIMPIAR CACHÉ
#############################################
limpiar_cache() {
    echo -e "${YELLOW}4. LIMPIAR CACHÉ${NC}"
    echo ""

    echo "cd ~/labartola"
    echo "php spark cache:clear"
    echo "rm -rf writable/cache/*"
    echo "rm -rf writable/debugbar/*"
    echo ""

    echo -e "${GREEN}✓ Caché limpiado!${NC}"
}

#############################################
# 5. VERIFICAR ESTADO
#############################################
verificar() {
    echo -e "${YELLOW}5. VERIFICAR ESTADO${NC}"
    echo ""

    echo "# Versión de PHP"
    echo "php -v"
    echo ""

    echo "# Verificar composer"
    echo "composer --version"
    echo ""

    echo "# Verificar permisos de writable"
    echo "ls -la ~/labartola/writable/"
    echo ""

    echo "# Verificar public_html"
    echo "ls -la ~/public_html/"
    echo ""

    echo "# Verificar conexión a BD"
    echo "mysql -u TU_USUARIO -p TU_BASE_DATOS"
}

#############################################
# 6. BACKUP
#############################################
backup() {
    echo -e "${YELLOW}6. CREAR BACKUP${NC}"
    echo ""

    echo "# Backup de base de datos"
    echo "mysqldump -u TU_USUARIO -p TU_BASE_DATOS > ~/backup-$(date +%Y%m%d).sql"
    echo ""

    echo "# Backup de imágenes"
    echo "tar -czf ~/backup-images-$(date +%Y%m%d).tar.gz ~/labartola/public/assets/images/platos/"
    echo ""

    echo "# Backup del archivo .env"
    echo "cp ~/labartola/.env ~/backup-env-$(date +%Y%m%d).txt"
    echo ""

    echo -e "${GREEN}✓ Backups creados!${NC}"
}

#############################################
# 7. RESTAURAR BACKUP
#############################################
restaurar() {
    echo -e "${YELLOW}7. RESTAURAR BACKUP${NC}"
    echo ""

    echo "# Restaurar base de datos"
    echo "mysql -u TU_USUARIO -p TU_BASE_DATOS < ~/backup-YYYYMMDD.sql"
    echo ""

    echo "# Restaurar imágenes"
    echo "tar -xzf ~/backup-images-YYYYMMDD.tar.gz -C ~/"
}

#############################################
# 8. TROUBLESHOOTING
#############################################
troubleshooting() {
    echo -e "${YELLOW}8. SOLUCIÓN DE PROBLEMAS${NC}"
    echo ""

    echo "# Error 500 - Ver logs"
    echo "tail -50 ~/labartola/writable/logs/log-*.log"
    echo ""

    echo "# Página en blanco - Activar errores temporalmente"
    echo "nano ~/labartola/.env"
    echo "# Cambiar: CI_ENVIRONMENT = development"
    echo ""

    echo "# CSS/JS no cargan - Re-enlazar"
    echo "cd ~"
    echo "rm -rf public_html/assets"
    echo "ln -s ~/labartola/public/assets ~/public_html/assets"
    echo ""

    echo "# Permisos incorrectos"
    echo "chmod -R 775 ~/labartola/writable/"
    echo "chmod -R 755 ~/labartola/public/"
}

#############################################
# MENÚ PRINCIPAL
#############################################
menu() {
    echo ""
    echo "Selecciona una opción:"
    echo ""
    echo "1) Primer Deploy (solo primera vez)"
    echo "2) Actualizar código"
    echo "3) Ver logs"
    echo "4) Limpiar caché"
    echo "5) Verificar estado"
    echo "6) Crear backup"
    echo "7) Restaurar backup"
    echo "8) Troubleshooting"
    echo "9) Salir"
    echo ""

    read -p "Opción: " opcion

    case $opcion in
        1) primer_deploy ;;
        2) actualizar ;;
        3) ver_logs ;;
        4) limpiar_cache ;;
        5) verificar ;;
        6) backup ;;
        7) restaurar ;;
        8) troubleshooting ;;
        9) exit 0 ;;
        *) echo "Opción inválida" ;;
    esac
}

# Ejecutar menú
menu
