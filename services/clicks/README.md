# Clicks Service — Webhook + Kafka Ingest, Stats & Forward to Finance (Custom DI Remote)

Сервис **Clicks** принимает клики через webhook **или** из Kafka, хранит их в PostgreSQL,
отдаёт агрегированную статистику и форвардит батчами в **Finance**.
Секреты/конфиги может получать из **внешнего DI‑сервиса** (расположен в отдельном контейнере).

## Потоки
1) **Webhook → Kafka → Consumer → DB** (по умолчанию)  
2) **Kafka → Consumer → DB** (если источник сам публикует в Kafka)  
3) **Forward → Finance** (HTTP, батчи)

## Endpoints
- `POST /api/v1/webhooks/click` — приём клика (публикация в Kafka или прямой upsert)
- `GET  /api/v1/clicks/stats?from=&to=&sort=&order=` — статистика
- `POST /api/v1/forward?date=YYYY-MM-DD` — отправка батчей за день в Finance

## Kafka
- topic ingress: `clicks_raw`
- key: `click_id` (равномерное распределение)
- Consumer-команда: `php artisan clicks:kafka-consume`

## Внешний DI‑сервис
- Базовый URL: `DI_BASE_URL` (пример: `http://di:8080`)
- Маршруты (примерные, можно подстроить):
  - `GET /api/v1/config/secrets?source=asd_network_1` → `{ "secret": "..." }`
  - `GET /api/v1/config/finance` → `{ "endpoint": "http://finance:8000", "batch": 5000 }`
Клиент: `App\Services\DI\DiClient` с кэшем (Redis через Cache facade, если доступен).

## ENV
```
KAFKA_BROKERS=kafka:9092
KAFKA_TOPIC_CLICKS=clicks_raw
KAFKA_GROUP_ID=clicks-consumer
KAFKA_SECURITY_PROTOCOL=PLAINTEXT

# режимы
CLICK_INGEST_QUEUE=false
CLICK_PUBLISH_TO_KAFKA=true

# DI
DI_BASE_URL=http://di:8080

# Finance fallback
FINANCE_ENDPOINT=http://finance:8000
FINANCE_FORWARD_BATCH=5000
FINANCE_HTTP_TIMEOUT=10
```
