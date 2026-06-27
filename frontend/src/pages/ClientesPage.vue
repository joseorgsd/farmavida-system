<template>

<div>

    <h1
    class="text-3xl font-bold mb-8 text-black">

        Clientes

    </h1>

    <div
    class="bg-navy-800 border border-white/10 rounded-xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-navy-700">

                <tr>

                    <th class="p-4 text-left text-blue-200 font-semibold">

                        Nombre

                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">

                        Documento

                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">

                        Email

                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                v-for="cliente in clientes"
                :key="cliente.id"
                class="border-t border-white/10 hover:bg-navy-700/50 transition-colors">

                    <td class="p-4 text-white">

                        {{ cliente.nombre }}

                    </td>

                    <td class="p-4 text-white">

                        {{ cliente.dni }}

                    </td>

                    <td class="p-4 text-white">

                        {{ cliente.email }}

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

const clientes = ref([])

const loadClientes = async () => {

    try {

        const response =
            await api.get('/clientes')

        console.log(
            'CLIENTES:',
            response.data
        )

        clientes.value =
            response.data.data || []
    }

    catch (error) {

        console.log(error)
    }
}

onMounted(() => {

    loadClientes()
})
</script>