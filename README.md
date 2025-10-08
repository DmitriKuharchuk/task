# 4 Laravel microservices: clicks, finance, parser, di
Stack: PHP 8.2 FPM per service + Nginx + Postgres 15 + Kafka (Bitnami, single-node KRaft)

## Quick start
1) Copy env:
   cp .env.example .env

2) (Optional) put your Laravel apps into:
   services/clicks
   services/finance
   services/parser
   services/di
   Each already has a Laravel-style `.env.example` prefilled from root `.env`.

3) Up:
   docker compose up -d --build

4) Open:
   - clicks  → http://localhost:${CLICK_SERVICE_PORT}
   - finance → http://localhost:${FINANCE_SERVICE_PORT}
   - parser  → http://localhost:${PARSER_SERVICE_PORT}
   - di      → http://localhost:${DI_SERVICE_PORT}

## Notes
- Nginx roots are /var/www/services/<service>/public so real Laravel projects will run out-of-the-box.
- Replace placeholders with actual Laravel projects (composer install, php artisan key:generate, migrations).
- Postgres bootstrap user/db defined by POSTGRES_*; init script creates finance_db, parser_db, di_db with users.




## Описание задач уже внутри сервисов в readme