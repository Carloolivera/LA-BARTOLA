@echo off
echo ====================================
echo  Deteniendo servicios de desarrollo
echo ====================================
echo.
echo Deteniendo MySQL...
docker-compose down
echo.
echo Servicios detenidos!
echo.
pause
