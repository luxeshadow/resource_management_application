
@echo off
cd "C:\laragon\www\PM"
start /min php artisan serve
cd "C:\laragon"
start /min C:\laragon\laragon.exe
 timeout /t 3 /nobreak > null
start http://127.0.0.1:8000/connexion
exit

