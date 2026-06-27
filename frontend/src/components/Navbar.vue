<template>

<div
class="bg-white shadow px-8 py-5 flex justify-between items-center">

    <h1
    class="text-2xl font-bold">

        Sistema ERP

    </h1>

    <div
    class="flex items-center gap-4">

        <div
        class="text-right">

            <div class="font-bold">

                {{ user?.name }}

            </div>

            <div class="text-sm text-gray-500">

                Administrador

            </div>

        </div>

        <button

            @click="logout"

            class="bg-red-600 text-white px-4 py-2 rounded-lg">

            Salir

        </button>

    </div>

</div>

</template>

<script setup>

import { useRouter } from 'vue-router'

const router = useRouter()

const user = JSON.parse(
    localStorage.getItem('user')
)

const logout = () => {

    localStorage.clear()

    router.push('/')
}

</script>