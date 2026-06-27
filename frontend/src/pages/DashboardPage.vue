<template>
  <div>
    <h1 class="text-4xl font-bold mb-10 text-black">Dashboard Farmacéutico</h1>

    <div v-if="error" class="bg-red-900/30 border border-red-500/40 text-red-300 rounded-lg p-4 mb-6">
      No se pudieron cargar los datos del dashboard. Intenta recargar la página.
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-10">

      <div class="bg-navy-800 border border-white/10 rounded-2xl shadow p-6">
        <p class="text-blue-200">Ventas (hoy)</p>
        <h2 class="text-4xl font-bold mt-4 text-white">
          <span v-if="loading" class="text-white/30">···</span>
          <span v-else>{{ ventasFormateadas }}</span>
        </h2>
      </div>

      <div class="bg-navy-800 border border-white/10 rounded-2xl shadow p-6">
        <p class="text-blue-200">N° de Ventas (hoy)</p>
        <h2 class="text-4xl font-bold mt-4 text-white">
          <span v-if="loading" class="text-white/30">···</span>
          <span v-else>{{ numeroVentas }}</span>
        </h2>
      </div>

      <div class="bg-navy-800 border border-white/10 rounded-2xl shadow p-6">
        <p class="text-blue-200">Productos</p>
        <h2 class="text-4xl font-bold mt-4 text-white">
          <span v-if="loading" class="text-white/30">···</span>
          <span v-else>{{ productos }}</span>
        </h2>
      </div>

      <div class="bg-navy-800 border border-white/10 rounded-2xl shadow p-6">
        <p class="text-blue-200">Clientes</p>
        <h2 class="text-4xl font-bold mt-4 text-white">
          <span v-if="loading" class="text-white/30">···</span>
          <span v-else>{{ clientes }}</span>
        </h2>
      </div>

      <div class="bg-navy-800 border border-white/10 rounded-2xl shadow p-6">
        <p class="text-blue-200">Stock Crítico</p>
        <h2 class="text-4xl font-bold mt-4 text-red-400">
          <span v-if="loading" class="text-white/30">···</span>
          <span v-else>{{ stockCritico }}</span>
        </h2>
      </div>

    </div>

    <div class="bg-navy-800 border border-white/10 rounded-2xl shadow p-6">
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
| CHART
|--------------------------------------------------------------------------
*/
const series = ref([{ name: 'Ventas', data: [] }])

const chartOptions = ref({
  chart: { id: 'ventas', background: 'transparent' },
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