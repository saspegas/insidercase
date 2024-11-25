# insidercase

Requirements:

* php:8.1^
* database (you can use MySQL, SQLite, or any other database supported by Laravel and PHP)
* Redis

After pulling the project codes, go to the project directory and run the following commands:

Run the command:

```bash
composer install
```

to install project dependencies.

Run the command:

```bash
php artisan migrate
```

to create the necessary database tables.

Add the following line to the `.env` file:

```env
WEBHOOK_SITE_URL=https://webhook.site/6f327d6a-478f-430c-b35e-3c8d5adb0bf3
```

This is the webhook site endpoint information for the SMS sending scenario.

You can create 10 dummy messages by sending a GET request to:

```
localhost/api/live-test
```

We create 2 workers for message sending.

```bash
php artisan queue:work --rest=5 --queue=sms
```

```bash
php artisan queue:work --rest=5 --queue=sms
```

These workers will process at 5-second intervals. With 2 workers, we meet the condition of 2 SMS per 5 seconds.

We record the response from the third-party API for SMS in Redis. This prevents delays in the SMS sending queue. We create a worker to process the records in Redis into the database.

```bash
php artisan queue:work --queue=attempted_sms
```

After performing the above steps, our system is ready to process SMS sending. To start processing, we need to queue our dummy data. We added this as a scheduled task in the kernel file. You can either run it automatically every 5 seconds with the command:

```bash
php artisan schedule:run
```

or manually trigger it with the command:

```bash
php artisan app:add-messages-to-queue
```