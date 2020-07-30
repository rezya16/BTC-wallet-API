**Для развёртывания проекта необходимо выполнить команды:**

git clone https://github.com/rezya16/Paxful.git dir_name

cd dir_name

composer install

cp .env.example .env

Настроить параметры подключения к бд в .env

php artisan migrate

php artisan db:seed

php artisan passport:install

php artisan key:generate

php artisan serve


**Запросы**

POST api/users - создание пользователя. Возвращает токен аутентификации.

POST api/login - логин уже зарегетрированного пользователя. Возвращает токен аутентификации.

POST api/wallets - создает кошелёк для пользователя с токеном. Возвращает адрес кошелька и баланс в Биткоинах и долларах.

GET api/wallets/{address} - возвращает адресс кошелька и Баланс в Биткоинах и долларах.

POST api/transactions - Делает транзакцию между кошельками, если кошельки принадлежат разным пользователям, то комиссию переводит на кошелёк Paxful.

GET api/transactions - возвращает все транзакции аутентифицированного пользователя.

GET api/wallets/{address}/transactions - возвращает все транзакции связанные с этим кошельком.

**Данные для пользователя Paxful**

name: paxful@mail.com
password: 12345678

address кошелька: 11111111111111111111111111111111111
