@echo off
echo ====================================
echo  La Bartola - Modo Desarrollo Local
echo ====================================
echo.
echo [1/3] Iniciando MySQL en Docker...
docker-compose up -d mysql phpmyadmin
echo.
echo [2/3] Esperando a que MySQL este listo...
timeout /t 10 /nobreak > nul
echo.
echo [3/3] Limpiando cache y iniciando servidor PHP optimizado...
php spark cache:clear
echo.
echo ====================================
echo   SERVIDOR LISTO!
echo ====================================
echo  Aplicacion: http://localhost:8080
echo  phpMyAdmin: http://localhost:8088
echo ====================================
echo.
echo Presiona Ctrl+C para detener el servidor
echo.
php -c php.ini spark serve
