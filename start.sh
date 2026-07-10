#!/bin/bash
PORT=5400
echo "Checking if port $PORT is already in use..."
PID=$(lsof -t -i:$PORT)
if [ ! -z "$PID" ]; then
    echo "Killing process $PID using port $PORT..."
    kill -9 $PID
fi
echo "Starting PHP built-in web server at http://localhost:$PORT..."
export PHP_CLI_SERVER_WORKERS=4
php -S 0.0.0.0:$PORT router.php
