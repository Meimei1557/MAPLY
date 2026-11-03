@echo off
setlocal
cd /d "%~dp0"
color 0A
echo ========================================
echo              MAPLY - Cari Kos
echo ========================================

REM ---- 0. Pastikan PHP bisa dipanggil ----
where php >nul 2>nul
if errorlevel 1 (
  echo [ERROR] PHP tidak ditemukan di PATH.
  echo         Install XAMPP, lalu tambahkan C:\xampp\php ke PATH Windows,
  echo         atau jalankan file ini dari Command Prompt yang sudah mengenali php.
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
  echo [1/3] File .env belum ada. Membuat dari .env.example ...
  copy /y .env.example .env >nul
)
REM APP_KEY hasil "php artisan key:generate" selalu diawali "base64:".
REM Jika baris APP_KEY kosong atau tidak ada, kunci dibuat otomatis di sini.
findstr /b /c:"APP_KEY=base64:" .env >nul 2>nul
if errorlevel 1 (
  echo [1/3] APP_KEY kosong - membuat kunci enkripsi baru ...
  php artisan key:generate --force
  if errorlevel 1 (
    echo GAGAL membuat APP_KEY. Coba manual: php artisan key:generate
    pause
    exit /b 1
  )
)

REM ---- 3. Bersihkan cache lalu jalankan server ----
echo [2/3] Membersihkan cache...
php artisan optimize:clear >nul

echo [3/3] Menjalankan server di http://127.0.0.1:8000
echo (Tekan Ctrl+C untuk berhenti)
php artisan serve --host=127.0.0.1 --port=8000
pause
