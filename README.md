# Importer API

##Start project
```bash
docker-compose up -d --build
docker-compose exec importer-api php artisan migrate

 
## API Endpoints

### 📥 `POST /api/import`

Импорт данных из Excel-файла.

- **Описание**: Загружает и обрабатывает Excel-файл с данными. Поддерживается формат `.xlsx`.
- **Метод**: `POST`
- **Content-Type**: `multipart/form-data`
- **Тело запроса (form-data)**:
- `file`: Excel-файл (`.xlsx`)

### 📤 `GET /api/export`

Экспорт сгруппированных данных в формате Excel.

- **Описание**: Возвращает json, в котором данные сгруппированы по дате.
- **Метод**: `GET`

