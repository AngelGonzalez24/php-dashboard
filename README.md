# Dashboard PHP: Ventas & Satisfacción

**Tecnologías:** PHP (API), Chart.js (gráficas), Leaflet (mapa), Bootstrap (UI).  
**Cómo correrlo:**

1) Requisitos: PHP 8+ instalado.  
2) Clona este repo o descarga el ZIP.  
3) En la carpeta del proyecto, ejecuta el servidor embebido de PHP:

```bash
php -S localhost:8000
```

4) Abre `http://localhost:8000` en tu navegador.

---

## Archivos
- `index.php` — HTML + JS del dashboard (consume endpoints PHP).
- `api/sales_monthly.php` — ingresos mensuales (JSON).
- `api/products.php` — ventas por producto (JSON).
- `api/satisfaction.php` — satisfacción de clientes (JSON).
- `api/regions.php` — ventas por región con lat/lon (JSON).
- `assets/styles.css` — estilos mínimos.

---

## Gráficas personalizadas
- **Línea** (ingresos mensuales) con relleno, tensión y formato de moneda.
- **Barras** (ventas por producto) con ejes y ticks formateados.
- **Pastel** (satisfacción) con leyenda inferior.
- **Mapa geoespacial** (Leaflet) con *circle markers* escalados por ventas.

## Interpretación de resultados (ejemplo)
- Ingresos crecen a lo largo del año con pico en diciembre.
- El producto A lidera; C requiere estrategia de impulso.
- 80% de clientes satisfechos/muy satisfechos.
- CDMX concentra mayor volumen de ventas.

---

## Autoevaluación (respuestas)
1. **¿Qué es un sistema de coordenadas?**  
   Conjunto de reglas para ubicar un punto mediante números llamados coordenadas (p. ej., cartesiano x–y o geográfico lat–lon).

2. **Tipos de representación y ejemplo geoespacial:**  
   Barras, líneas, pastel, áreas, dispersión, histogramas, mapas.  
   **Geoespacial:** mapa con marcadores proporcionados por valor (Leaflet) o mapa coroplético por estados.

3. **¿En qué consiste la visualización de datos?**  
   En representar datos mediante gráficos interactivos para identificar patrones, comparar categorías y apoyar decisiones.

---

## Licencia
MIT
