#!/usr/bin/env bash

echo "Mark Repository Safe"
git config --global --add safe.directory /var/www/html

echo "Change directory into /var/www/html"
cd /var/www/html

echo "Install NPM Packages"
npm install

echo "Build Frontend for Development"
npm run dev
