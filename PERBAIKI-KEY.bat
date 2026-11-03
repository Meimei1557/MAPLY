@echo off
setlocal
cd /d "%~dp0"
color 0B
echo ==========================================
echo   PERBAIKI MAPLY - APP_KEY / .env
echo   (Error: No application encryption key
echo    has been specified)
echo ==========================================
echo.

where php >nul 2>nul
if errorlevel 1 (
  echo [ERROR] PHP tidak ditemukan di PATH.
  echo         Install XAMPP, lalu tambahkan C:\xampp\php ke PATH Windows.
  pause
  exit /b 1
)

REM ---- 1. Pastikan folder kerja Laravel lengkap ----
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\cache\data" mkdir "storage\framework\cache\data"
if not exist "storage\logs" mkdir "storage\logs"
if not exist "bootstrap\cache" mkdir "bootstrap\cache"

REM ---- 2. Pastikan .env ada ----
if not exist ".env" (
  echo [1/3] File .env belum ada - membuat dari .env.example ...
  copy /y .env.example .env >nul
) else (
  echo [1/3] File .env sudah ada.
)

REM ---- 3. Pastikan APP_KEY tidak kosong (selalu diawali "base64:") ----
findstr /b /c:"APP_KEY=base64:" .env >nul 2>nul
if errorlevel 1 (
  echo [2/3] APP_KEY kosong - membuat kunci enkripsi baru ...
  php artisan key:generate --force
  if errorlevel 1 (
    echo GAGAL. Coba manual: php artisan key:generate
    pause
    exit /b 1
  )
) else (
  echo [2/3] APP_KEY sudah terisi - tidak diubah.
)

REM ---- 4. Hapus cache konfigurasi yang mungkin menahan nilai lama ----
echo [3/3] Membersihkan cache konfigurasi ...
php artisan config:clear >nul
php artisan optimize:clear >nul

echo.
echo SELESAI. Sekarang jalankan lagi START-MAPLY.bat
echo lalu buka http://127.0.0.1:8000
pause
