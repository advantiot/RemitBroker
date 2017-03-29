#!/bin/bash
if [ $1 == "" ]
    echo "Command is gitpush <comment>"
    exit 1
fi
echo "Starting..."
git init
git add .
git commit -m $1
git remote add github https://github.com/advantiot/RemitBroker
git push origin master
echo "Done!"
