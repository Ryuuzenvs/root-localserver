#!/bin/bash
#export PLAYWRIGHT_BROWSERS_PATH="/var/www/html/venv/pw-browsers"
export PLAYWRIGHT_BROWSERS_PATH="/var/www/html/venv/pw-browsers"
# Penggunaan: ./run.sh [use_venv_true_false] [folder_path] [script_name] [arg1] [arg2]...
USE_VENV=$1
FOLDER=$2
SCRIPT=$3
shift 3 # Hapus 3 argumen pertama, sisanya adalah input untuk python

VENV_PATH="/var/www/html/venv/bin/python3"
SYS_PATH="python3"

cd /var/www/html/$FOLDER
#cd /home/ryuu/my-homeserver/$FOLDER

if [ "$USE_VENV" = "1" ]; then
    $VENV_PATH -u $SCRIPT "$@"
else
    $SYS_PATH -u $SCRIPT "$@"
fi
