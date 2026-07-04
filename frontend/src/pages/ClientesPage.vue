<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <h1 class="text-3xl font-bold text-black">Clientes</h1>
      
      <div class="flex items-center gap-3">
        <!-- Buscador -->
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar por nombre o DNI..."
            class="bg-navy-800 border border-white/10 rounded-lg pl-10 py-3 w-full sm:w-80 text-white placeholder:text-blue-200/50 focus:outline-none focus:border-blue-500 transition-colors"
          >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-200/50 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 01-14 0 7 7 0 0114 0z" />
          </svg>
        </div>

        <!-- Botón Agregar -->
        
      </div>
    </div>

    <!-- Tabla -->
    <div class="bg-navy-800 border border-white/10 rounded-2xl shadow-xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full min-w-full">
          <thead class="bg-navy-700">
            <tr>
              <th class="px-6 py-5 text-left text-blue-200 font-semibold">Nombre</th>
              <th class="px-6 py-5 text-left text-blue-200 font-semibold">Documento</th>
              <th class="px-6 py-5 text-left text-blue-200 font-semibold">Email</th>

            </tr>
          </thead>
          
          <!-- Un solo tbody con animaciones -->
          <TransitionGroup 
            name="list" 
            tag="tbody" 
            class="divide-y divide-white/10"
          >
            <!-- Loading -->
            <tr v-if="loading" key="loading" class="h-64">
              <td colspan="4" class="text-center">
                <div class="flex flex-col items-center justify-center text-blue-200/70">
                  <div class="animate-spin w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full mb-3"></div>
                  <p>Cargando clientes...</p>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-else-if="filteredClientes.length === 0" key="empty" class="h-64">
              <td colspan="4" class="text-center text-blue-200/50">
                <p class="text-lg">No se encontraron clientes</p>
                <p class="text-sm mt-1">Intenta con otra búsqueda o agrega uno nuevo</p>
              </td>
            </tr>

            <!-- Clientes -->
            <tr
              v-for="(cliente, index) in filteredClientes"
              :key="cliente.id"
              class="hover:bg-navy-700/70 transition-colors group"
              :style="{ animationDelay: `${index * 30}ms` }"
            >
              <td class="px-6 py-5 text-white font-medium">{{ cliente.nombre }}</td>
              <td class="px-6 py-5 text-white/90">{{ cliente.dni }}</td>
              <td class="px-6 py-5 text-blue-300">{{ cliente.email }}</td>
              <td class="px-6 py-5 text-right">
                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                  
                </div>
              </td>
            </tr>
          </TransitionGroup>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../api/axios'

const clientes = ref([])
const loading = ref(true)
const searchQuery = ref('')

const loadClientes = async () => {
  try {
    loading.value = true
    const response = await api.get('/clientes')
    console.log('CLIENTES:', response.data)
    clientes.value = response.data.data || []
  } catch (error) {
    console.error('Error al cargar clientes:', error)
  } finally {
    loading.value = false
  }
}

const filteredClientes = computed(() => {
  if (!searchQuery.value) return clientes.value
  
  const query = searchQuery.value.toLowerCase()
  return clientes.value.filter(cliente => 
    cliente.nombre?.toLowerCase().includes(query) ||
    cliente.dni?.toLowerCase().includes(query) ||
    cliente.email?.toLowerCase().includes(query)
  )
})

onMounted(() => {
  loadClientes()
})
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
  transition: all 0.35s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateY(15px);
}

.list-move {
  transition: transform 0.4s ease;
}
</style>