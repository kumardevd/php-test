# POC

  - We have create POC using PHP 7.4
  - We have used Nginx as Webserver
  - We have used composer to install our dependency

# How to run application

  - Set config variable in `config.php` file 
  - Install dependency using `composer install` command
  - Set RabbitMQ credential in `config.php` file
  - Run `php daemon.php` in terminal
  - Post data to /transaction 
  - You will get response in `processOrder` function in `SimpleReceiver.php` file where business logic will be written once data is received.
