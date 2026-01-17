# 📊 Generador de Reportes Crediticios Masivos (API)

API RESTful diseñada para la generación asíncrona de reportes crediticios de alto volumen. Este proyecto resuelve el desafío de procesar y exportar millones de registros manteniendo la estabilidad del servidor mediante una arquitectura orientada a eventos y streaming de datos.

---

## Estrategia Técnica y Resoluciones

A continuación, se detallan las soluciones implementadas para responder a los desafíos de rendimiento planteados, basadas en el análisis del código y arquitectura del proyecto:

### 1. Optimización de Memoria

**Desafío:** ¿Cómo manejar millones de registros sin agotar la RAM?
**Solución Implementada:**

-   **Streaming (I/O) con FastExcel:** En `CreditReportService::generateExcelReport()`, se utiliza `Rap2hpoutre\FastExcel` con `OpenSpout` como motor subyacente. Esto permite escribir el archivo Excel fila por fila directamente al disco temporal (`tempnam()`), evitando cargar el documento completo en memoria. El código específico: `(new FastExcel($query->cursor()->getIterator()))->export($tempPath, function ($row) {...})`.
-   **Database Cursors (`LazyCollection`):** En `CreditReportRepository::getExportQuery()`, se emplea `$query->cursor()` en lugar de `get()` o `chunk()`. Esto crea un `LazyCollection` que mantiene solo **una fila en memoria a la vez** durante la iteración, garantizando un consumo de memoria constante (<50MB) independientemente del volumen (10k a 10M registros). El iterador se pasa directamente a FastExcel para procesamiento streaming.
-   **Almacenamiento Temporal Seguro:** El archivo se genera en un directorio temporal del sistema (`sys_get_temp_dir()`) y luego se mueve a `Storage::disk('public')` usando `fopen()`, minimizando el uso de memoria adicional.

### 2. Optimización de Consultas

**Desafío:** ¿Cómo evitar problemas de rendimiento en la Base de Datos?
**Solución Implementada:**

-   **Raw SQL con UNION ALL:** En `CreditReportRepository`, se construye una consulta compleja usando `DB::table()` con `UNION ALL` para combinar eficientemente las tablas `report_loans`, `report_credit_cards` y `report_other_debts`. Esto evita múltiples queries separadas y permite optimización a nivel de BD. Ejemplo: `$loans->unionAll($cards)->unionAll($others)`.
-   **Selección Selectiva de Columnas:** Se evita `SELECT *` y se especifican solo los campos necesarios (`report_id`, `full_name`, `document`, etc.), reduciendo el tráfico de red entre la BD y la aplicación. Los campos se mapean explícitamente en el `select()` de cada subquery.
-   **Inyección de Dependencias y Repositorios:** La lógica de consulta se aísla en `CreditReportRepository`, inyectado en `CreditReportService`. Esto permite testing unitario y cambios sin afectar el servicio principal.
-   **Filtros por Fecha:** Las consultas incluyen filtros `whereBetween('created_at', [$startDate, $endDate])` para limitar el dataset, mejorando rendimiento en tablas grandes.
-   **Validación de Rango de Fechas:** Se limita el rango entre 'from' y 'to' a un máximo de 6 meses para prevenir consultas excesivamente amplias que puedan saturar la memoria o la base de datos.

### 3. Escalabilidad

**Desafío:** ¿Qué estrategias usar si el volumen se multiplica?
**Solución Implementada:**

-   **Procesamiento Asíncrono (Jobs & Queues):** En `CreditReportController::export()`, se despacha `GenerateCreditReportJob::dispatch()` inmediatamente, liberando el servidor web. El job se ejecuta en un worker separado (`php artisan queue:work`), permitiendo múltiples requests simultáneas sin bloqueos. Timeout configurado en 1200s para procesos largos.
-   **Infraestructura Horizontal:** Los jobs son desacoplados, permitiendo escalar añadiendo múltiples workers (`queue:work`) en servidores separados. El sistema usa `Database` como driver por defecto, configurable a `Redis/SQS` para entornos de producción.
-   **Almacenamiento Flexible:** Se utiliza `Storage::disk('public')` con facade `Storage`, facilitando migración a nube (AWS S3) cambiando `FILESYSTEM_DISK=s3` en `.env`. Soporta terabytes de reportes con configuración mínima.
-   **Generación de Datos Masivos para Testing:** El comando `RunStressTestCommand` (`php artisan test:stress`) despacha `GenerateSubscriptionReportsJob` en lotes, poblando la BD con factories para pruebas de estrés, validando escalabilidad.

### 4. Arquitectura y Buenas Prácticas

**Desafío:** ¿Cómo mantener el código mantenible y extensible?
**Solución Implementada:**

-   **Patrón Repository:** `CreditReportRepository` encapsula lógica de BD, permitiendo cambios sin afectar servicios.
-   **Patrón Service:** `CreditReportService` maneja lógica de negocio, inyectado en jobs y controladores.
-   **Patrón Job:** `GenerateCreditReportJob` para procesamiento asíncrono, con logging integrado.
-   **Principios SOLID:** Separación de responsabilidades (cada clase una función), inyección de dependencias, interfaces limpias.
-   **Testing:** Uso de `Queue::fake()` y `Storage::fake()` en tests para simular entornos sin side effects.

---

## 🛠️ Stack Tecnológico

-   **Framework:** Laravel 12
-   **Excel Engine:** rap2hpoutre/fastexcel (Streaming Writer)
-   **Queue System:** Database Driver (Configurable a Redis/SQS)
-   **Testing:** PHPUnit (Feature Tests con `Queue::fake` y `Storage::fake`)

---

## 🚀 Instalación y Despliegue

### 1. Clonación e Instalación de Dependencias

```bash
git clone <url-del-repositorio>
cd generador-reporte-crediticio
composer install
```

### 2. Configuración del Entorno

```bash
cp .env.example .env
php artisan key:generate
```

Configura las variables necesarias en `.env` (BD, queues, etc.).

### 3. Migraciones de Base de Datos

```bash
php artisan migrate
```

### 4. (Opcional) Generación de Datos Masivos para Testing

Para validar el rendimiento con datos reales, genera ~1M de registros:

```bash
php artisan test:stress 85000 100
```

### 5. Ejecución del Sistema

Inicia el servidor web:

```bash
php artisan serve
```

En una terminal separada, inicia el worker de queues:

```bash
php artisan queue:work --timeout=0
```

### 6. Testing

Ejecuta los tests para validar el funcionamiento:

```bash
php artisan test
```

### 7. Uso de la API

Envía una petición POST a `/api/reports/export` con el rango de fechas (máximo 6 meses):

```json
{
    "from": "2025-07-15",
    "to": "2026-01-13"
}
```

El reporte se generará de forma asíncrona y estará disponible en `storage/app/public/reports/`.
