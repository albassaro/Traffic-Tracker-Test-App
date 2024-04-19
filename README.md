<h1 align="center">Traffic Tracker on Laravel 10</h1>
<h2>Описание</h2>
    Приложение SF-AdTech — это трекер трафика, созданный для организации взаимодействия компаний (рекламодателей), которые хотят привлечь к себе на сайт посетителей и покупателей (клиентов), и владельцев сайтов (веб-мастеров), на которые люди приходят, например, чтобы почитать новости или пообщаться на форуме.
    

Рекламодатель создаёт предложение (offer), определяя URL страницы, на которую он хочет приводить людей. 

Веб-мастера в системе видят создаваемые офферы, подписываются на них, после чего система выдаёт им специальные ссылки, которые они должны разместить в любом виде у себя на ресурсе. Ссылка эта ведёт не на целевой URL, а на систему-редиректор, которая фиксирует переход, а затем перенаправляет клиента на страницу сайта рекламодателя.

В проекте реализовано:
1. Регистрация и авторизация пользователей с учетом ролей: (рекламодатель или веб-мастер).
2. Возможность создания, удаления и подписки на оффер.
3. Вывод детальной информации по разным офферам в разрезе дня, месяца, года.
4. Реализована Админ-панель для отображения всей информации о пользователях, офферах и общей статистики приложения.
    

<h2>Установка</h2>
1. Клонируйте репозиторий.

2. COMPOSER

```
composer install
   
```
   
3. Через терминал запустите миграцию базы данных с seeder-ом
```
php artisan migrate --seed
```

После этого будет создана БД, таблицы, а также администратор для админ-панели (email: admin@example.com; password: admin) и роли пользователей (advertiser и webmaster).

4. NPM
```
npm install

npm run dev
```
5. Запустите у себя локальный сервер и выполните команду:
```
php artisan serve
```



