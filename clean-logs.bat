@echo off
echo ====================================
echo  Limpieza de Logs y Cache
echo ====================================
echo.
echo Limpiando logs antiguos...
del /q writable\logs\*.log 2>nul
echo Logs eliminados!
echo.
echo Limpiando cache...
php spark cache:clear
echo Cache limpiada!
echo.
echo Limpiando sesiones antiguas (mas de 1 dia)...
forfiles /p writable\session /s /m ci_session* /d -1 /c "cmd /c del @path" 2>nul
echo Sesiones antiguas eliminadas!
echo.
echo ====================================
echo  Limpieza completada!
echo ====================================
pause
