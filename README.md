## Environment
1. Windows 10/11
2. **[XAMPP / PHP Version 8.2.12](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/xampp-windows-x64-8.2.12-0-VS16-installer.exe)**
3. Laravel Framework 12.16.0
4. **[php_imagick-3.7.0-8.2-ts-vs16-x64.zip](https://downloads.php.net/~windows/pecl/releases/imagick/3.7.0/php_imagick-3.7.0-8.2-ts-vs16-x64.zip)**

   Once you downloaded the correct files:
    - Extract from php_imagick-….zip the **php_imagick.dll** file, and save it to the **ext** directory of your PHP installation
    - Add this line to your **php.ini** file:
      **extension=php_imagick.dll**
    - Restart the Apache/NGINX Windows service (if applicable)

## Installation
1. environment PATH edit, ganti lokasi xampp/php
2. edit php.ini (aktif zip, aktif gd)
3. composer install
4. copy dan rename .env
5. php artisan key:generate
6. buat database
7. php artisan migrate
8. php artisan db:seed
9. npm install
10. npm run dev
11. php artisan storage:link
