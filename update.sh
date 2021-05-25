#!/bin/bash
/usr/bin/php index.php
git pull origin main
git add .
git commit -m "$(date +"%D %T") updating reading log" || echo "No changes, nothing to commit!"
git push origin main
