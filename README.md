# Биллинг

## 1. установка
Для работы нужна версия PHP: 7.1 (такая версия используется в консоли TimeWeb)

### 1.1 подключение репозитория
В папке проекта нужно выполнить эти команды:  
composer config repositories.advecs vcs https://github.com/jurych/advecs.billing  
composer require jurych/advecs.billing:dev-master  

### 1.2 создание базы (локально, для разработки)
Необходимо выполнить в MySQL следующий скрипт:  
https://github.com/onviser/advecs.billing/blob/master/init.sql

## 2. использование

### 2.1 запуск из консоли:
cd bin  
php demo.php  

### 2.2 запуск через веб:
https://billing.advecs.org/pscb/demo.html

### 2.3 подключение в коде:
```php
$hBilling = (new Billing())
        ->setStorage(new MySQLStorage($host, $user, $pass, $database, $port));
```

### 2.4 пример использования:
https://github.com/onviser/advecs.billing/blob/master/bin/demo.php  

## 3. запуск тестов 
./vendor/bin/phpunit  