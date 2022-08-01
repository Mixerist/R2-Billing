# R2 Billing

### Установка Open Server

Скачайте и установите Open Server с [официального сайта](https://ospanel.io).

### Скачивание скрипта

После установки Open Server перейдите в `C:\OpenServer\domains` и создайте внутри новую папку с
именем **shop.test** (вы можете использовать любое другое имя). Перейдите в созданную папку и распакуйте в неё
содержимое архива. Или воспользуйтесь консолью Open Server и введите команду:

```bash
git clone git@github.com:Mixerist/R2-Billing.git .
```

### Настройка Open Server

Откройте настройки и перейдите в раздел **Модули**. Убедитесь, что
версия PHP установлена не ниже 7.4.

Во вкладке **Домены** в поле **Управление доменами** выберите **Ручное + Автопоиск**. В качестве папки домена
укажите `C:\OpenServer\domains\shop.test\public`. Имя домена оставьте **shop.test**.

### Настройка скрипта

Находясь в папке с проектом воспользуйтесь командой для установки зависимостей:

```bash
composer install
```

Дождитесь окончания установки и скопируйте конфигурационный файл командой:

```bash
cp .env.example .env
```

Сгенерируйте секретный ключ (он нужен для работы приложения):

```bash
php artisan key:generate
```

Отредактируйте конфигурационный файл в соответствии с вашими настройками доступа к SQL Server.

### Скачивание драйвера для работы с SQL Server

Так как Open Server не содержит драйвер для работы с SQL Server по умолчанию, то его необходимо скачать и
установить самому. Для этого перейдите
на [официальный сайт Microsoft](https://docs.microsoft.com/ru-ru/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver16)
и скачайте архив. Разархивируйте его в любое место.

Перейдите в папку `C:\OpenServer\modules\php\PHP_7.4\ext` (обратите внимание, я использую версию 7.4) и скопируйте в неё
файл с названием **php_pdo_sqlsrv_74_ts_x64.dll** из скаченного архива.

Затем необходимо подключить драйвер. Откройте файл `C:\OpenServer\userdata\config\PHP_7.4_php.ini` и добавьте в него
следующую строчку:

```bash
extension = php_pdo_sqlsrv_74_ts_x64.dll
```

Перезагрузите Open Server.

### Изменение настроек сервера R2

Теперь необходимо заставить сервер обращаться к нашему API. Для этого откройте файл `\Data\BillFlexAPI.ini` и
отредактируйте параметры **RequestGetCashUrl** и **RequestOutputUrl** следующим образом:

```bash
RequestGetCashUrl = http://shop.test/balance/get?u_id=%s&g_code=%s&nick_nm=%d&sign=%s
RequestOutputUrl = http://shop.test/balance/purchase?u_id=%s&g_code=%s&nick_nm=%d&user_ip=%s&good_no=%d&good_amt=%d&agc_no=%s&etc2=%s&etc3=%d&etc4=%s&pd_title=%s&sign=%s
```

### Настройка таблицы БД

Для правильной работы скрипта необходимо убедиться, что ваша таблица **Member** базы данных **FNLAccount** имеет
первичный ключ.
Если же ключ не установлен, воспользуйтесь запросом для его установки:

```bash
ALTER TABLE [FNLAccount].[dbo].[Member] ADD PRIMARY KEY CLUSTERED ( [mUserNo] )
```

Внимание! Поле **mUserNo** указано для примера, вы должно указать своё. Но помните, что первичный ключ должен быть
уникальным!

### Изменение первичного ключа в скрипте

Откройте файл `C:\OpenServer\domains\shop.test\app\Models\Member.php` и отредактируйте свойство **$primaryKey**
установив название вашего первичного ключа таблицы **Member**.
