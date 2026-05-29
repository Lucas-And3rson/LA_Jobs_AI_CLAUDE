@echo off
title LA Jobs AI

echo =====================================
echo Iniciando LA Jobs AI
echo =====================================

echo.
echo [1/3] Iniciando PostgreSQL...
docker start la-jobs-ai-postgres-1 >nul 2>&1

echo Aguardando PostgreSQL...
timeout /t 8 /nobreak > nul

echo.
echo [2/3] Abrindo ambiente...

start "" wt ^
new-tab --title "Laravel API" -d "%USERPROFILE%\LAABS\VibeCoding\CLAUDE-OLLAMA\la-jobs-ai\backend" cmd /k "php artisan serve" ; ^
new-tab --title "Queue Worker" -d "%USERPROFILE%\LAABS\VibeCoding\CLAUDE-OLLAMA\la-jobs-ai\backend" cmd /k "php artisan queue:work -vvv" ; ^
new-tab --title "Angular" ^
-d "%USERPROFILE%\LAABS\VibeCoding\CLAUDE-OLLAMA\la-jobs-ai\frontend" ^
cmd /k "ng serve --open" ; ^
new-tab --title "Git" ^
-d "%USERPROFILE%\LAABS\VibeCoding\CLAUDE-OLLAMA\la-jobs-ai" ^
cmd /k "git status"
echo.
echo [3/3] Angular iniciado!


echo.
echo Ambiente iniciado com sucesso.