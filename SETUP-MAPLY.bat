@echo off
setlocal
cd /d "%~dp0"
color 0A
echo ========================================
echo        SETUP DATABASE MAPLY
echo ========================================
echo Pastikan MySQL XAMPP menyala dan database 'maply' sudah dibuat di phpMyAdmin
echo (atau biarkan DB_CONNECTION=sqlite pada .env untuk memakai SQLite bawaan).
echo.
pause

REM ---- 0. Pastikan PHP bisa dipanggil ----
where php >nul 2>nul
if errorlevel 1 (
  echo [ERROR] PHP tidak ditemukan di PATH.
  echo         Install XAMPP, lalu tambahkan C:\xampp\php ke PATH Windows.
  pause
  exit /b 1
)

REM ---- 1. Pastikan folder kerja Laravel lengkap (kadang hilang saat ekstrak ZIP) ----
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\cache\data" mkdir "storage\framework\cache\data"
if not exist "storage\logs" mkdir "storage\logs"
if not exist "bootstrap\cache" mkdir "bootstrap\cache"

REM ---- 2. Pastikan .env ada, dan APP_KEY tidak kosong ----
if not exist ".env" (
  echo File .env belum ada. Membuat dari .env.example ...
  copy /y .env.example .env >nul
)
REM APP_KEY hasil "php artisan key:generate" selalu diawali "base64:".
findstr /b /c:"APP_KEY=base64:" .env >nul 2>nul
if errorlevel 1 (
  echo APP_KEY kosong - membuat kunci enkripsi baru ...
  php artisan key:generate --force
  if errorlevel 1 (
    echo GAGAL membuat APP_KEY. Coba manual: php artisan key:generate
    pause
    exit /b 1
  )
)

REM ---- 3. Migrasi + seed ----
php artisan migrate:fresh --seed
if errorlevel 1 (
  echo SETUP GAGAL. Periksa koneksi database pada .env
  pause
  exit /b 1
)

REM ---- 4. Link storage + bersihkan cache ----
php artisan storage:link >nul 2>nul
php artisan optimize:clear >nul

echo.
echo SETUP SELESAI. Akun demo ada di README.md
echo Jalankan START-MAPLY.bat untuk memulai server.
pause
