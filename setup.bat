@echo off
setlocal

cd /d "%~dp0"

echo ============================================================
echo MoviePinion - Filmportal Setup
echo ============================================================
echo.

set "MYSQL_BIN="
set "PHP_BIN="

if exist "C:\xampp\mysql\bin\mysql.exe" set "MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe"
if exist "C:\xampp\php\php.exe" set "PHP_BIN=C:\xampp\php\php.exe"

if "%MYSQL_BIN%"=="" set "MYSQL_BIN=mysql"
if "%PHP_BIN%"=="" set "PHP_BIN=php"

echo Schritt 1: Neue Datenbank gruppe2 wird angelegt.
"%MYSQL_BIN%" -uroot < "%~dp0sql\create_database.sql"
if errorlevel 1 (
    echo.
    echo FEHLER: Die Datenbank konnte nicht erstellt werden.
    echo Die Datenbank gruppe2 darf fuer die Ersteinrichtung noch nicht existieren.
    echo Bitte pruefen, ob MySQL/XAMPP gestartet ist und ob mysql erreichbar ist.
    echo.
    pause
    exit /b 1
)

echo.
echo Schritt 2: Startfilme werden ueber OMDb geladen und gespeichert.
"%PHP_BIN%" "%~dp0sql\filme_befuellen.php"
if errorlevel 1 (
    echo.
    echo HINWEIS: Die Datenbank wurde erstellt, aber die Startbefuellung war nicht vollstaendig erfolgreich.
    echo Bitte API-Key, Internetverbindung und PHP/XAMPP pruefen.
    echo.
    pause
    exit /b 1
)

echo.
echo ============================================================
echo Setup abgeschlossen.
echo Frontend: http://localhost/moviepinion/
echo Backend:  http://localhost/moviepinion/admin/
echo Admin:    admin / admin
echo ============================================================
echo.
pause
exit /b 0
