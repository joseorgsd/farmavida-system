<template>

<div>

    <h1
    class="text-3xl font-bold mb-8 text-black">

        Kardex de Inventario

    </h1>

    <div
    class="bg-navy-800 border border-white/10 rounded-xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-navy-700">

                <tr>

                    <th class="p-4 text-blue-200 font-semibold">
                        Producto
                    </th>

                    <th class="p-4 text-blue-200 font-semibold">
                        Tipo
                    </th>

                    <th class="p-4 text-blue-200 font-semibold">
                        Cantidad
                    </th>

                    <th class="p-4 text-blue-200 font-semibold">
                        Stock Anterior
                    </th>

                    <th class="p-4 text-blue-200 font-semibold">
                        Stock Nuevo
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                v-for="item in movimientos"
                :key="item.id"
                class="border-t border-white/10 hover:bg-navy-700/50 transition-colors">

                    <td class="p-4 text-white">

                        {{ item.producto?.nombre }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.tipo }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.cantidad }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.stock_anterior }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.stock_nuevo }}

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

</template>

<script setup>

import {

    ref,

    onMounted

} from 'vue'

import api from '../api/axios'

const movimientos = ref([])

const loadData = async () => {

    const response =
        await api.get('/kardex')

    movimientos.value =
        response.data
}

onMounted(() => {

    loadData()
})

</script>