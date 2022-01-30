# Simple Video Management System
Simple Video is a automated H264 encryption system built on Lumen Laravel Framework

## Installation Video
https://youtu.be/96i6z2hhgdk


## Requirements
1. FFMpeg that support filter_complex ( eg for W10 - https://www.gyan.dev/ffmpeg/builds/ )
2. FFMpeg with H264 and AAC ( make sure FFMPEG is on system environment $PATH)
3. PHP 8
4. MySQL DB
5. Laragon ( on Windows )

## Features
1. Encrypted HLS output with rotating keys

## Installation
1. Git clone this project
2. composer update
3. create a database in MySQL
4. copy env.example to .env and change database parameters in .env 
5. run "php artisan migrate" in console 
6. create folder videos in public folder 
9. run "php artisan queue:listen --queue=video --timeout=0"  ( use laragon terminal 1 )
10. run "php artisan queue:listen --queue=key --timeout=0" ( use laragon terminal 2 )
11. Make sure to edit you web server / php settings to allow large file upload
12. Point App/public as yout web server document root

## License
Simple Video is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
