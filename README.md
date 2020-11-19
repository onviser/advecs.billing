# Биллинг

## 1. установка
версия PHP: 7.1 (такая версия используется в консоли TimeWeb)  

## 2. использование

### 2.1 запуск из консоли:
cd bin  
php demo.php  

### 2.2 запуск через веб:
https://billing.advecs.org/demo.html  

## 3. запуск тестов 
./vendor/bin/phpunit  

### Composer
php composer.phar require --dev phpunit/phpunit  
chmod 0775 vendor/bin/phpunit  
chmod 0775 vendor/phpunit/phpunit/phpunit    