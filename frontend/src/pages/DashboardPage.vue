<template>
  <div>
    <h1 class="text-4xl font-bold mb-10 text-black">Dashboard Farmacéutico</h1>

    <div v-if="error" class="bg-red-900/30 border border-red-500/40 text-red-300 rounded-lg p-4 mb-6">
      No se pudieron cargar los datos del dashboard. Intenta recargar la página.
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-10">
      <div 
        v-for="(stat, index) in stats" 
        :key="index"
        class="bg-navy-800 border border-white/10 rounded-2xl shadow p-6 transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl"
        :style="{ animationDelay: `${index * 80}ms` }"
      >
        <p class="text-blue-200">{{ stat.title }}</p>
        <h2 class="text-4xl font-bold mt-4 text-white transition-all">
          <span v-if="loading" class="text-white/30">···</span>
          <span v-else class="fade-in">{{ stat.value }}</span>
        </h2>
      </div>
    </div>

    <!-- Gráfico -->
    <div class="bg-navy-800 border border-white/10 rounded-2xl shadow p-6 transition-all duration-700">
      <apexchart
        type="bar"
        height="350"
        :options="chartOptions"
        :series="series">
      </apexchart>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../api/axios'

/*
|--------------------------------------------------------------------------
| STATS
|--------------------------------------------------------------------------
*/
const ventas = ref(0)
const numeroVentas = ref(0)
const productos = ref(0)
const clientes = ref(0)
const stockCritico = ref(0)
const loading = ref(true)
const error = ref(false)

const ventasFormateadas = computed(() =>
  new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(ventas.value)
)

/*
|--------------------------------------------------------------------------
| STATS ARRAY (para animaciones)
|--------------------------------------------------------------------------
*/
const stats = computed(() => [
  { title: 'Ventas (hoy)', value: ventasFormateadas.value },
  { title: 'N° de Ventas (hoy)', value: numeroVentas.value },
  { title: 'Productos', value: productos.value },
  { title: 'Clientes', value: clientes.value },
  { title: 'Stock Crítico', value: stockCritico.value, color: 'text-red-400' }
])

/*
|--------------------------------------------------------------------------
| CHART
|--------------------------------------------------------------------------
*/
const series = ref([{ name: 'Ventas', data: [] }])
const chartOptions = ref({
  chart: { 
    id: 'ventas', 
    background: 'transparent',
    animations: {
      enabled: true,
      easing: 'easeinout',
      speed: 800
    }
  },
  theme: { mode: 'dark' },
  xaxis: { categories: [] },
  yaxis: {
    labels: {
      formatter: (value) => `S/ ${value.toFixed(0)}`
    }
  },
  tooltip: {
    y: {
      formatter: (value) =>
        new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(value)
    }
  }
})

/*
|--------------------------------------------------------------------------
| LOAD DATA
|--------------------------------------------------------------------------
*/
const loadData = async () => {
  loading.value = true
  error.value = false
  try {
    const response = await api.get('/dashboard')
    ventas.value = response.data.ventas || 0
    numeroVentas.value = response.data.numero_ventas || 0
    productos.value = response.data.productos || 0
    clientes.value = response.data.clientes || 0
    stockCritico.value = response.data.stock_critico || 0

    chartOptions.value = {
      ...chartOptions.value,
      xaxis: {
        categories: response.data.grafico?.categorias || []
      }
    }
    series.value = [{ name: 'Ventas', data: response.data.grafico?.valores || [] }]
  } catch (err) {
    console.log(err)
    error.value = true
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadData()
})
</script>

<style scoped>
.fade-in {
  animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Hover suave en tarjetas */
div[class*="bg-navy-800"] {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>