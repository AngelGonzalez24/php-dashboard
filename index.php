<?php
// index.php - Dashboard en PHP + Chart.js + Leaflet
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard de Ventas y Satisfacción - PHP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/styles.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-light">
  <div class="container py-4">
    <header class="mb-4 text-center">
      <h1 class="fw-bold">📊 Dashboard: Ventas & Satisfacción</h1>
      <p class="text-muted">Caso práctico con PHP (API) + Chart.js + Leaflet</p>
    </header>

    <div class="row g-4">
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">Ingresos mensuales</h5>
            <canvas id="lineChart"></canvas>
            <p class="mt-3 small text-muted" id="interp-linea">
              Interpretación: pendiente positiva durante el año con pico en diciembre.
            </p>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">Ventas por producto</h5>
            <canvas id="barChart"></canvas>
            <p class="mt-3 small text-muted" id="interp-barras">
              Interpretación: el producto A lidera; oportunidad de impulso para C.
            </p>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">Satisfacción de clientes</h5>
            <canvas id="pieChart"></canvas>
            <p class="mt-3 small text-muted" id="interp-pie">
              Interpretación: 80% entre satisfechos y muy satisfechos.
            </p>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">Ventas por región (geoespacial)</h5>
            <div id="map" style="height: 360px;"></div>
            <p class="mt-3 small text-muted" id="interp-mapa">
              Interpretación: CDMX concentra el mayor volumen; Mérida el menor.
            </p>
          </div>
        </div>
      </div>
    </div>

    <section class="mt-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Autoevaluación (respuestas)</h4>
          <ol class="mb-0">
            <li><strong>Sistema de coordenadas:</strong> reglas para ubicar puntos mediante coordenadas; ej. cartesiano (x, y) o geográfico (lat, lon).</li>
            <li><strong>Tipos de representación:</strong> barras, líneas, pastel, áreas, dispersión, mapas. <em>Geoespacial:</em> mapa con marcadores/choropleth en Leaflet/ArcGIS.</li>
            <li><strong>Visualización de datos:</strong> representar información en gráficos interactivos para comprender patrones y apoyar decisiones.</li>
          </ol>
        </div>
      </div>
    </section>

    <footer class="text-center text-muted small py-3">Hecho con ❤️ en PHP + JS • Licencia MIT</footer>
  </div>

<script>
async function fetchJSON(url){ const r = await fetch(url); if(!r.ok) throw new Error('Error '+url); return r.json(); }

function numberFmt(n){ return n.toLocaleString('es-MX'); }

(async () => {
  const [ventas, productos, satisfaccion, regiones] = await Promise.all([
    fetchJSON('api/sales_monthly.php'),
    fetchJSON('api/products.php'),
    fetchJSON('api/satisfaction.php'),
    fetchJSON('api/regions.php'),
  ]);

  // Línea: Ingresos mensuales
  const ctxLine = document.getElementById('lineChart');
  const lineChart = new Chart(ctxLine, {
    type: 'line',
    data: {
      labels: ventas.meses,
      datasets: [{
        label: 'Ingresos ($)',
        data: ventas.ingresos,
        tension: 0.35,
        fill: true,
        pointRadius: 4,
        borderWidth: 2
      }]
    },
    options: {
      plugins: {
        tooltip: { callbacks: { label: c => '$ ' + numberFmt(c.parsed.y) } },
        legend: { display: false },
        title: { display: false }
      },
      scales: {
        y: { ticks: { callback: (v) => '$ ' + numberFmt(v) }, grid: { drawBorder:false } },
        x: { grid: { display: false } }
      }
    }
  });
  const maxIngreso = Math.max(...ventas.ingresos);
  const maxMes = ventas.meses[ventas.ingresos.indexOf(maxIngreso)];
  document.getElementById('interp-linea').textContent =
    `Interpretación: ingresos con tendencia creciente y pico en ${maxMes} ($ ${numberFmt(maxIngreso)}).`;

  // Barras: Ventas por producto
  const ctxBar = document.getElementById('barChart');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: productos.labels,
      datasets: [{
        label: 'Ventas',
        data: productos.data,
        borderWidth: 1
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        y: { ticks: { callback: (v)=> numberFmt(v) }, grid: { drawBorder:false } },
        x: { grid: { display: false } }
      }
    }
  });
  const maxProdValue = Math.max(...productos.data);
  const maxProd = productos.labels[productos.data.indexOf(maxProdValue)];
  document.getElementById('interp-barras').textContent =
    `Interpretación: el producto ${maxProd} lidera con ${numberFmt(maxProdValue)} unidades; oportunidad de mejora en ${productos.labels[productos.data.indexOf(Math.min(...productos.data))]}.`;

  // Pie: Satisfacción
  const totalClientes = satisfaccion.values.reduce((a,b)=>a+b,0);
  const satisfechos = (satisfaccion.values[0] + satisfaccion.values[1]) / totalClientes * 100;
  const ctxPie = document.getElementById('pieChart');
  new Chart(ctxPie, {
    type: 'pie',
    data: {
      labels: satisfaccion.labels,
      datasets: [{ data: satisfaccion.values }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
  });
  document.getElementById('interp-pie').textContent =
    `Interpretación: ${satisfechos.toFixed(1)}% de clientes están satisfechos o muy satisfechos.`;

  // Mapa: Regiones
  const map = L.map('map').setView([23.5, -102], 4.6);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);
  regiones.items.forEach(r => {
    const radius = 6 + (r.ventas / regiones.maxVentas) * 20; // tamaño relativo
    const marker = L.circleMarker([r.lat, r.lon], { radius, weight:1 }).addTo(map);
    marker.bindPopup(`<strong>${r.region}</strong><br>Ventas: $ ${numberFmt(r.ventas)}`);
  });
  const top = regiones.items.reduce((a,b)=> a.ventas>b.ventas?a:b);
  document.getElementById('interp-mapa').textContent =
    `Interpretación: ${top.region} concentra el mayor volumen ($ ${numberFmt(top.ventas)}).`;
})().catch(err => {
  console.error(err);
  alert('Error cargando datos del dashboard. Revisa la consola.');
});
</script>

</body>
</html>
