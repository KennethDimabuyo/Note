Instruction to run on local

Copy the .env.example and rename to .env

Run "composer update" on your powershell or cmd in the same location of the projec

Setup your .env file make sure to run the "php artisan key:gen" on the same location in powershell or cmd

On your phpmyadmin, create a database name "note". You can change this in your .env file

Run "php artisan migrate --seed" to migrate the DB tables and have a initial data

Run the app by running "php artisan serve" and on other console is "npm run watch" for vue

Once it run successfully, go to your browser and access "localhost:8000". You can use my initial credential if your run the database seed

Username: kenneth
Password: password
