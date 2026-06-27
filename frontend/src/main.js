import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import './style.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

import VueApexCharts from 'vue3-apexcharts'

const pinia = createPinia()

createApp(App)
  .use(router)
  .use(pinia)
  .use(VueApexCharts)
  .component('apexchart', VueApexCharts)
  .mount('#app')