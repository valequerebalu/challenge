# 📦 GildedRose Kata - Refactorización con SOLID y Clean Code

Una refactorización completa del clásico GildedRose Kata, implementando principios SOLID, patrones de diseño y clean code para mejorar mantenibilidad, extensibilidad y testabilidad. Incluye la nueva funcionalidad de artículos "Conjured" que degradan su calidad el doble de rápido.

## ✨ Características Principales

- **Refactorización Completa:** Código original transformado de un gran método if-else a una arquitectura limpia con patrones de diseño.
- **Principio Abierto/Cerrado:** Fácil añadir nuevos tipos de artículos sin modificar código existente.
- **Artículos Conjured:** Implementación de la nueva regla para artículos conjurados.
- **Testing Completo:** Tests unitarios y de aprobación que validan el comportamiento.
- **Clean Code:** Código legible, mantenible y siguiendo mejores prácticas.

## 🏗️ Arquitectura y Patrones de Diseño

### Principios SOLID Aplicados
- **S (Single Responsibility):** Cada clase `Updater` maneja un solo tipo de artículo.
- **O (Open/Closed):** Sistema extensible para nuevos artículos sin cambios en código existente.
- **L (Liskov Substitution):** Interfaces comunes permiten intercambiar updaters.
- **I (Interface Segregation):** `ItemUpdater` es específica y minimalista.
- **D (Dependency Inversion):** Dependencia de abstracciones, no concretos.

### Patrones de Diseño Implementados
- **Strategy Pattern:** `ItemUpdater` interface con implementaciones específicas por tipo de artículo.
- **Factory Pattern:** `GildedRose::getUpdater()` selecciona el updater apropiado basado en el nombre.
- **Repository Pattern:** (Potencial para futuras expansiones).

### Estructura del Código Refactorizado
```
src/
├── Item.php                 # Clase original (no modificada)
├── GildedRose.php          # Clase principal refactorizada
└── Updaters/
    ├── ItemUpdater.php     # Interface
    ├── NormalItemUpdater.php
    ├── AgedBrieUpdater.php
    ├── BackstageUpdater.php
    ├── SulfurasUpdater.php
    └── ConjuredUpdater.php # Nueva implementación
```

## 🛠️ Stack Tecnológico

- **Lenguaje:** PHP 8.0+
- **Testing:** PHPUnit con ApprovalTests
- **Análisis Estático:** PHPStan
- **Estándares de Código:** Easy Coding Standard (ECS) - PSR-12
- **Gestión de Dependencias:** Composer

## 📋 Requisitos

- PHP 8.0 o superior
- Composer

## 🚀 Instalación y Configuración

### 1. Clonación e Instalación
```bash
git clone <url-del-repositorio>
cd game-02
composer install
```

### 2. Verificación
```bash
composer tests  # Ejecutar tests
composer check-cs  # Verificar estándares de código
composer phpstan  # Análisis estático
```

## 📖 Uso

### Ejecución del Sistema
Para simular el comportamiento del sistema por N días:
```bash
php fixtures/texttest_fixture.php 10
```
Cambia `10` por el número de días deseado.

### Tipos de Artículos Soportados
- **Normal:** Calidad -1 por día, -2 después de sellIn < 0.
- **Aged Brie:** Calidad +1 por día, +2 después de sellIn < 0 (máx. 50).
- **Backstage Passes:** +1 por día, +2 si sellIn <=10, +3 si <=5, 0 después del concierto.
- **Sulfuras:** Nunca cambia (calidad 80).
- **Conjured:** Calidad -2 por día, -4 después de sellIn < 0.

## 🧪 Testing

### Tests Unitarios
```bash
composer tests
```
Incluye tests de aprobación que verifican el output exacto contra archivos aprobados.

### Tests con Cobertura
```bash
composer test-coverage
```
Genera reporte HTML en `/builds/index.html` (requiere Xdebug).

### Análisis Estático
```bash
composer phpstan
```

### Estándares de Código
```bash
composer check-cs  # Verificar
composer fix-cs    # Corregir automáticamente
```

## 🔧 Refactorización Detallada

### Problema Original
El código inicial tenía un método `updateQuality()` monolítico con múltiples if-else anidados, violando SRP y OCP. Difícil de mantener y extender.

### Solución Implementada
1. **Extracción de Lógica:** Creación de interface `ItemUpdater` y clases concretas por tipo.
2. **Polimorfismo:** `GildedRose` delega la actualización a updaters específicos.
3. **Extensibilidad:** Añadir nuevos artículos requiere solo una nueva clase updater.
4. **Artículos Conjured:** Implementados con degradación doble (-2/-4).

### Beneficios
- **Mantenibilidad:** Código modular y fácil de entender.
- **Testabilidad:** Cada updater se puede testear independientemente.
- **Extensibilidad:** Nuevos tipos sin modificar código existente.
- **Legibilidad:** Separación clara de responsabilidades.

## 🎯 Lecciones Aprendidas

Esta refactorización demuestra cómo aplicar principios SOLID en código legacy, transformando un sistema rígido en uno flexible y mantenible. El patrón Strategy permite extensibilidad sin romper cambios, y los tests aseguran que el comportamiento se preserve.

---

*Refactorizado siguiendo las mejores prácticas de desarrollo de software.*
