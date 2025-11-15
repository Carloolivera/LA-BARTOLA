# Script para cambiar entre modo Docker y PHP Local
# Uso: .\switch-mode.ps1 [docker|local]

param(
    [Parameter(Mandatory=$true)]
    [ValidateSet('docker', 'local')]
    [string]$Mode
)

$envFile = ".env"
$envDocker = ".env.docker"
$envLocal = ".env.local"

Write-Host "======================================" -ForegroundColor Cyan
Write-Host "  Cambiando a modo: $Mode" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""

if ($Mode -eq "docker") {
    # Cambiar a modo Docker
    Write-Host "[1/3] Guardando configuración actual como .env.local..." -ForegroundColor Yellow
    if (Test-Path $envFile) {
        Copy-Item $envFile $envLocal -Force
    }

    Write-Host "[2/3] Activando configuración Docker..." -ForegroundColor Yellow
    if (Test-Path $envDocker) {
        Copy-Item $envDocker $envFile -Force
    } else {
        # Crear .env para Docker si no existe
        $content = @"
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

# BASE DE DATOS (MySQL en Docker)
database.default.hostname = mysql
database.default.database = labartola
database.default.username = root
database.default.password = root_password_2024
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306

GOOGLE_CLIENT_ID = 'TU_GOOGLE_CLIENT_ID_AQUI'
GOOGLE_CLIENT_SECRET = 'TU_GOOGLE_CLIENT_SECRET_AQUI'

MERCADOPAGO_ACCESS_TOKEN = 'TU_ACCESS_TOKEN_AQUI'
MERCADOPAGO_PUBLIC_KEY = 'TU_PUBLIC_KEY_AQUI'
MERCADOPAGO_MODO = 'sandbox'
"@
        $content | Out-File -FilePath $envFile -Encoding UTF8
    }

    Write-Host "[3/3] Reiniciando contenedores Docker..." -ForegroundColor Yellow
    docker-compose restart web

    Write-Host ""
    Write-Host "✅ Modo Docker activado!" -ForegroundColor Green
    Write-Host "   Accede a: http://localhost:8080" -ForegroundColor Cyan
    Write-Host "   Base de datos: mysql:3306 (interno)" -ForegroundColor Cyan

} elseif ($Mode -eq "local") {
    # Cambiar a modo PHP Local
    Write-Host "[1/3] Guardando configuración actual como .env.docker..." -ForegroundColor Yellow
    if (Test-Path $envFile) {
        Copy-Item $envFile $envDocker -Force
    }

    Write-Host "[2/3] Activando configuración Local..." -ForegroundColor Yellow
    if (Test-Path $envLocal) {
        Copy-Item $envLocal $envFile -Force
    } else {
        # Crear .env para Local si no existe
        $content = @"
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

# BASE DE DATOS (MySQL en Docker - ACCESO DESDE HOST)
database.default.hostname = 127.0.0.1
database.default.database = labartola
database.default.username = root
database.default.password = root_password_2024
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3307

GOOGLE_CLIENT_ID = 'TU_GOOGLE_CLIENT_ID_AQUI'
GOOGLE_CLIENT_SECRET = 'TU_GOOGLE_CLIENT_SECRET_AQUI'

MERCADOPAGO_ACCESS_TOKEN = 'TU_ACCESS_TOKEN_AQUI'
MERCADOPAGO_PUBLIC_KEY = 'TU_PUBLIC_KEY_AQUI'
MERCADOPAGO_MODO = 'sandbox'
"@
        $content | Out-File -FilePath $envFile -Encoding UTF8
    }

    Write-Host "[3/3] Verificando MySQL en Docker..." -ForegroundColor Yellow
    $mysqlRunning = docker-compose ps mysql | Select-String "Up"
    if (-not $mysqlRunning) {
        Write-Host "   Iniciando MySQL..." -ForegroundColor Yellow
        docker-compose up -d mysql phpmyadmin
    } else {
        Write-Host "   MySQL ya está corriendo ✓" -ForegroundColor Green
    }

    Write-Host ""
    Write-Host "✅ Modo PHP Local activado!" -ForegroundColor Green
    Write-Host "   Usa: php spark serve" -ForegroundColor Cyan
    Write-Host "   Base de datos: 127.0.0.1:3307 (externo)" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "======================================" -ForegroundColor Cyan
