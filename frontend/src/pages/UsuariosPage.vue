<template>

<div>

    <!-- HEADER -->

    <div
    class="flex justify-between items-center mb-8">

        <h1
        class="text-3xl font-bold text-black">

            Gestión de Usuarios

        </h1>

        <button

            @click="openModal"

            class="bg-blue-500 hover:bg-blue-400 text-white px-5 py-3 rounded-xl transition-colors">

            Nuevo Usuario

        </button>

    </div>

    <!-- TABLE -->

    <div
    class="bg-navy-800 border border-white/10 rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-navy-700">

                <tr>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        ID
                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        Nombre
                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        Email
                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        Rol
                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        Acciones
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                v-for="item in usuarios"
                :key="item.id"
                class="border-t border-white/10 hover:bg-navy-700/50 transition-colors">

                    <td class="p-4 text-white">

                        {{ item.id }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.name }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.email }}

                    </td>

                    <td class="p-4">

                        <span
                        class="bg-blue-500/20 text-blue-200 border border-blue-500/30 px-3 py-1 rounded-lg text-sm">

                            {{
                                item.roles?.[0]?.name
                                || 'SIN ROL'
                            }}

                        </span>

                    </td>

                    <td class="p-4">

                        <div class="flex gap-2">

                            <button

                                @click="editUser(item)"

                                class="bg-yellow-500 hover:bg-yellow-400 text-white px-4 py-2 rounded-lg transition-colors">

                                Editar

                            </button>

                            <button

                                @click="deleteUser(item.id)"

                                class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg transition-colors">

                                Eliminar

                            </button>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <!-- MODAL -->

    <div

    v-if="showModal"

    class="fixed inset-0 bg-black/60 flex justify-center items-center z-50">

        <div
        class="bg-navy-800 border border-white/10 rounded-2xl p-8 w-full max-w-xl shadow-2xl">

            <!-- TITLE -->

            <h2
            class="text-2xl font-bold mb-6 text-white">

                {{
                    editMode
                    ? 'Editar Usuario'
                    : 'Nuevo Usuario'
                }}

            </h2>

            <!-- FORM -->

            <form
            @submit.prevent="saveUser">

                <!-- NAME -->

                <div class="mb-4">

                    <label
                    class="block mb-2 font-medium text-white/80">

                        Nombre

                    </label>

                    <input

                        v-model="form.name"

                        type="text"

                        class="fm-input w-full">

                </div>

                <!-- EMAIL -->

                <div class="mb-4">

                    <label
                    class="block mb-2 font-medium text-white/80">

                        Email

                    </label>

                    <input

                        v-model="form.email"

                        type="email"

                        class="fm-input w-full">

                </div>

                <!-- PASSWORD -->

                <div class="mb-4">

                    <label
                    class="block mb-2 font-medium text-white/80">

                        Password

                    </label>

                    <input

                        v-model="form.password"

                        type="password"

                        class="fm-input w-full">

                </div>

                <!-- ROLE -->

                <div class="mb-6">

                    <label
                    class="block mb-2 font-medium text-white/80">

                        Rol

                    </label>

                    <select

                        v-model="form.role"

                        class="fm-input w-full">

                        <option value="">
                            Seleccione Rol
                        </option>

                        <option value="ADMIN">
                            ADMIN
                        </option>

                        <option value="CAJERO">
                            CAJERO
                        </option>

                        <option value="ALMACEN">
                            ALMACEN
                        </option>

                        <option value="QUIMICO">
                            QUIMICO
                        </option>

                    </select>

                </div>

                <!-- BUTTONS -->

                <div
                class="flex justify-end gap-3">

                    <button

                        type="button"

                        @click="closeModal"

                        class="bg-navy-600 hover:bg-navy-700 text-white/80 px-5 py-3 rounded-xl transition-colors">

                        Cancelar

                    </button>

                    <button

                        type="submit"

                        class="bg-blue-500 hover:bg-blue-400 text-white px-5 py-3 rounded-xl transition-colors">

                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</template>

<script setup>

import {

    ref,

    onMounted

} from 'vue'

import api from '../api/axios'

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const usuarios = ref([])

const showModal = ref(false)

const editMode = ref(false)

const selectedId = ref(null)

/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = ref({

    name: '',

    email: '',

    password: '',

    role: ''
})

/*
|--------------------------------------------------------------------------
| LOAD USERS
|--------------------------------------------------------------------------
*/

const loadUsers = async () => {

    try {

        const response =
            await api.get('/usuarios')

        usuarios.value =
            response.data.data.data
    }

    catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| OPEN MODAL
|--------------------------------------------------------------------------
*/

const openModal = () => {

    editMode.value = false

    selectedId.value = null

    form.value = {

        name: '',

        email: '',

        password: '',

        role: ''
    }

    showModal.value = true
}

/*
|--------------------------------------------------------------------------
| CLOSE MODAL
|--------------------------------------------------------------------------
*/

const closeModal = () => {

    showModal.value = false
}

/*
|--------------------------------------------------------------------------
| SAVE USER
|--------------------------------------------------------------------------
*/

const saveUser = async () => {

    try {

        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        if (!editMode.value) {

            await api.post(

                '/usuarios',

                form.value
            )
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        else {

            await api.put(

                `/usuarios/${selectedId.value}`,

                form.value
            )
        }

        closeModal()

        loadUsers()
    }

    catch (error) {

        console.log(error)

        alert(
            'Error al guardar usuario'
        )
    }
}

/*
|--------------------------------------------------------------------------
| EDIT USER
|--------------------------------------------------------------------------
*/

const editUser = (item) => {

    editMode.value = true

    selectedId.value = item.id

    form.value = {

        name: item.name,

        email: item.email,

        password: '',

        role:
            item.roles?.[0]?.name || ''
    }

    showModal.value = true
}

/*
|--------------------------------------------------------------------------
| DELETE USER
|--------------------------------------------------------------------------
*/

const deleteUser = async (id) => {

    const confirmar = confirm(
        '¿Eliminar usuario?'
    )

    if (!confirmar) return

    try {

        await api.delete(
            `/usuarios/${id}`
        )

        loadUsers()
    }

    catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| MOUNT
|--------------------------------------------------------------------------
*/

onMounted(() => {

    loadUsers()
})

</script>

<style scoped>

.fm-input {

    background: var(--navy-700);

    color: var(--white);

    border: 1px solid var(--white-10);

    border-radius: 10px;

    padding: 10px 14px;

    transition: 0.2s;
}

.fm-input::placeholder {

    color: var(--white-30);
}

.fm-input:focus {

    outline: none;

    border-color: var(--blue-500);

    box-shadow: 0 0 0 3px var(--blue-glow);
}

</style>