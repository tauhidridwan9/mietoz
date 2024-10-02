const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css') // Jika Anda menggunakan SASS
   .version(); // Menambahkan versioning untuk cache-busting
