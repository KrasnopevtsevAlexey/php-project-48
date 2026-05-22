# Вычислитель отличий (Gendiff)

Реализация CLI-утилиты «Вычислитель отличий» (Gendiff) в рамках обучения на платформе Hexlet.

### Hexlet tests and linter status:
[![Actions Status](https://github.com/KrasnopevtsevAlexey/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/KrasnopevtsevAlexey/php-project-48/actions)

### GitHub Actions & SonarCloud Status:
[![PHP CI](https://github.com)](https://github.com/KrasnopevtsevAlexey/php-project-48/actions)


[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=KrasnopevtsevAlexey_php-project-48&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=KrasnopevtsevAlexey_php-project-48)

[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=KrasnopevtsevAlexey_php-project-48&metric=coverage)](https://sonarcloud.io/summary/new_code?id=KrasnopevtsevAlexey_php-project-48)

[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=KrasnopevtsevAlexey_php-project-48&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=KrasnopevtsevAlexey_php-project-48)
---

## Описание
**Gendiff** — это консольная утилита, предназначенная для сравнения двух конфигурационных файлов и вывода разницы между ними. Программа полностью написана в функциональном иммутабельном стиле без использования изменяемых циклов и мутаций данных.

### Возможности:
* Поддержка популярных форматов конфигураций: **JSON** и **YAML** (`.yaml`, `.yml`).
* Сравнение как плоских структур, так и **глубоких рекурсивно вложенных объектов**.
* Три различных формата вывода результатов на выбор: `stylish`, `plain` и `json`.
* Автоматическое определение формата данных на основе расширения исходных файлов.

---

## Требования
* **PHP** >= 8.3
* **Composer** для управления зависимостями

---

## Установка

Склонируйте репозиторий с проектом и установите необходимые зависимости:

```bash
git clone https://github.com/KrasnopevtsevAlexey/php-project-48.git
cd php-project-48
composer install
```

---

## Использование

Для запуска утилиты используйте исполняемый файл `bin/gendiff`.

### Справка утилиты:
```bash
./bin/gendiff -h
```

### Синтаксис команды:
```bash
./bin/gendiff [--format <fmt>] <firstFile> <secondFile>
```

### Доступные форматы вывода (`--format`):
1. **`stylish`** (применяется по умолчанию) — выводит иерархическое дерево изменений с отступами и маркерами `+` и `-`.
2. **`plain`** — выводит плоский список текстовых утверждений, описывающих изменения свойств с указанием полного пути (через точку).
3. **`json`** — возвращает полное дерево отличий (AST) в валидном формате JSON для его последующей машинной обработки.

---

## Демонстрация работы

### Примеры сравнения файлов во всех доступных форматах:

[![asciicast](https://asciinema.org)](https://asciinema.org/a/1054454)

---

## Разработка и тестирование

В проекте настроена автоматическая проверка качества кода и стилей. Для локального запуска проверок используйте следующие команды:

* **Запуск тестов (PHPUnit):**
  ```bash
  make test
  ```
* **Запуск тестов с генерацией отчета о покрытии кода:**
  ```bash
  make test-coverage
  ```
* **Проверка соответствия кода стандартам PSR-12 (линтер):**
  ```bash
  make lint
  ```
