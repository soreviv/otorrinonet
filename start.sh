#!/bin/bash

php artisan migrate --force

npm run dev &
VITE_PID=$!

php artisan serve --host=0.0.0.0 --port=5000 &
LARAVEL_PID=$!

echo "Started Vite (PID: $VITE_PID) and Laravel (PID: $LARAVEL_PID)"

wait $VITE_PID $LARAVEL_PID
