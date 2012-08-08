#!/bin/bash
rm src/chrome/main.html
rm src/common/css/style.css
rm src/chrome/js/cryptocat.js
cp testing/main.html src/chrome/main.html
cp testing/css/style.css src/common/css/style.css
cp testing/js/cryptocat.js src/chrome/js/cryptocat.js
