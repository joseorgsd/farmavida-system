<template>

<div>

    <h1
    class="text-3xl font-bold mb-8 text-black">

        Panel Químico Farmacéutico

    </h1>

    <div
    class="bg-navy-800 border border-white/10 rounded-xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-navy-700">

                <tr>

                    <th class="p-4 text-blue-200 font-semibold">
                        Cliente
                    </th>

                    <th class="p-4 text-blue-200 font-semibold">
                        Producto
                    </th>

                    <th class="p-4 text-blue-200 font-semibold">
                        Cantidad
                    </th>

                    <th class="p-4 text-blue-200 font-semibold">
                        Estado
                    </th>

                    <th class="p-4 text-blue-200 font-semibold">
                        Acción
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                v-for="item in solicitudes"
                :key="item.id"
                class="border-t border-white/10 hover:bg-navy-700/50 transition-colors">

                    <td class="p-4 text-white">

                        {{ item.cliente?.nombre }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.producto?.nombre }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.cantidad_solicitada }}

                    </td>

                    <td class="p-4">

                        <span

                        :class="{

                            'text-yellow-400 font-semibold':
                            item.estado === 'PENDIENTE',

                            'text-green-400 font-semibold':
                            item.estado === 'APROBADO',

                            'text-red-400 font-semibold':
                            item.estado === 'RECHAZADO'
                        }">

                            {{ item.estado }}

                        </span>

                    </td>

                    <td class="p-4">

                        <div
                        class="flex gap-2">

                            <button

                                v-if="item.estado === 'PENDIENTE'"

                                @click="aprobar(item)"

                                class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded transition-colors">

                                Aprobar

                            </button>

                            <button

                                v-if="item.estado === 'PENDIENTE'"

                                @click="rechazar(item.id)"

                                class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded transition-colors">

                                Rechazar

                            </button>

                        </div>

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

const solicitudes = ref([])

const loadData = async () => {

    const response =

        await api.get(
            '/solicitudes-receta'
        )

    solicitudes.value =
        response.data
}

/*
|--------------------------------------------------------------------------
| APROBAR
|--------------------------------------------------------------------------
*/

const aprobar = async (item) => {

    const cantidad = prompt(
        'Cantidad aprobada'
    )

    const receta = prompt(
        'Número receta'
    )

    const medico = prompt(
        'Nombre médico'
    )

    await api.post(

        `/solicitudes-receta/${item.id}/aprobar`,

        {

            cantidad_aprobada:
                cantidad,

            numero_receta:
                receta,

            medico:
                medico
        }
    )

    loadData()
}

/*
|--------------------------------------------------------------------------
| RECHAZAR
|--------------------------------------------------------------------------
*/

const rechazar = async (id) => {

    const observacion = prompt(
        'Motivo rechazo'
    )

    await api.post(

        `/solicitudes-receta/${id}/rechazar`,

        {

            observacion
        }
    )

    loadData()
}

onMounted(() => {

    loadData()
})

</script>