-- PHP Authentication system with PHP OOP, MySQL, ajax

- A very simple PHP Authentication system includes (registration, login, dashboard, change password, reset password with OTP).

-- Files and folders
- assets [contains css, js, image assets]
- libs [contains external library {PHPMailer}]
- config.php
- conn.php
- dashboard.php
- SQL file for DB tables
- helper.php
- index.php
- operations.php
- otp.php
- reset.php


-- Front-end
- Bootstrap
- sweetalert js
- jquery
- ajax

-- Back-end
- PHP OOP

-- Database
- MySQL

-- Email
- PHPMailer library

-- configure:
- change config.php and write valid database info.
- import devglit_bracdb.sql for creating DB tables.
- add SMTP host in operations.php L-165, L-192
- add SMTP email username and password in operations.php L-167, 168, L-194, 195
- change Port if your mail server using different PORT, operations L-170, L-197
- add sender and reply email address in operations.php L-172, 173 & L-199, 200