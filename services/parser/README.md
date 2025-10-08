# URL Parser - README

Парсер URL, выдающий те же или **больше** данных, чем стандартный `parse_url` в PHP.
Поддерживает корректную обработку IPv6, IDNA (punycode), нормализацию, разбор query-параметров,
определение доменных уровней (через Public Suffix List, опционально) и реконструкцию URL.

## Цели

1. Совместимость с `parse_url`: возвращаем как минимум поля `scheme`, `host`, `port`, `path`, `origin` и так далее.
2. Расширенный вывод: метаданные хоста, разбор query в массив, сегменты пути, нормализованные варианты URL, эвристики для портов.
3. Корректность по основным RFC: 3986, 3987 (IDN), 6874 (IPv6 zone IDs), 7230/7231 (HTTP URI).

---



Пример результата (сокращённо):

```php
{
    "data": {
        "scheme": "http",
        "host": "onliner.by",
        "port": null,
        "path": "",
        "host_unicode": "onliner.by",
        "tld": "onliner",
        "sld": "by",
        "domain": "by.onliner",
        "subdomain": null,
        "origin": "http://onliner.by"
    }
}
```


