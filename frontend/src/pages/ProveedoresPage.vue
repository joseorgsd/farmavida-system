<template>

<div>

    <!-- HEADER -->

    <div
    class="flex justify-between items-center mb-8">

        <h1 class="text-3xl font-bold text-black">

            Proveedores

        </h1>

        <button

            @click="abrirModal"

            class="bg-blue-500 hover:bg-blue-400 text-white px-5 py-3 rounded-lg transition-colors">

            Nuevo Proveedor

        </button>

    </div>

    <!-- TABLA -->

    <div
    class="bg-navy-800 border border-white/10 rounded-xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-navy-700">

                <tr>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        RUC
                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        Razón Social
                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        Contacto
                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        Teléfono
                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">
                        Acciones
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                v-for="proveedor in proveedores"
                :key="proveedor.id"
                class="border-t border-white/10 hover:bg-navy-700/50 transition-colors">

                    <td class="p-4 text-white">

                        {{ proveedor.ruc }}

                    </td>

                    <td class="p-4 text-white">

                        {{ proveedor.razon_social }}

                    </td>

                    <td class="p-4 text-white">

                        {{ proveedor.contacto }}

                    </td>

                    <td class="p-4 text-white">

                        {{ proveedor.telefono }}

                    </td>

                    <td class="p-4 flex gap-2">

                        <button

                            @click="editarProveedor(proveedor)"

                            class="bg-yellow-500 hover:bg-yellow-400 text-white px-3 py-2 rounded transition-colors">

                            Editar

                        </button>

                        <button

                            @click="eliminarProveedor(proveedor.id)"

                            class="bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded transition-colors">

                            Eliminar

                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <!-- MODAL -->

    <div
    v-if="mostrarModal"
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">

        <div
        class="bg-navy-800 border border-white/10 p-8 rounded-xl w-full max-w-2xl shadow-2xl">

            <h2 class="text-2xl font-bold mb-6 text-white">

                Proveedor

            </h2>

            <form @submit.prevent="guardarProveedor">

                <div class="grid grid-cols-2 gap-4">

                    <input
                        v-model="form.ruc"
                        placeholder="RUC"
                        class="fm-input"
                    >

                    <input
                        v-model="form.razon_social"
                        placeholder="Razón Social"
                        class="fm-input"
                    >

                    <input
                        v-model="form.telefono"
                        placeholder="Teléfono"
                        class="fm-input"
                    >

                    <input
                        v-model="form.email"
                        placeholder="Email"
                        class="fm-input"
                    >

                    <input
                        v-model="form.contacto"
                        placeholder="Contacto"
                        class="fm-input"
                    >

                    <input
                        v-model="form.direccion"
                        placeholder="Dirección"
                        class="fm-input"
                    >

                </div>

                <div
                class="flex justify-end gap-3 mt-6">

                    <button

                        type="button"

                        @click="cerrarModal"

                        class="bg-navy-600 hover:bg-navy-700 text-white/80 px-4 py-2 rounded-lg transition-colors">

                        Cancelar

                    </button>

                    <button

                        class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-lg transition-colors">

                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</template>

<script setup>

import Swal from 'sweetalert2'

import {

    ref,

    reactive,

    onMounted

} from 'vue'

import api from '../api/axios'

const proveedores = ref([])

const mostrarModal = ref(false)

const editando = ref(false)

const proveedorId = ref(null)

const form = reactive({

    ruc: '',

    razon_social: '',

    telefono: '',

    email: '',

    direccion: '',

    contacto: ''
})

/*
|--------------------------------------------------------------------------
| LISTAR
|--------------------------------------------------------------------------
*/

const cargarProveedores = async () => {

    try {

        const response = await api.get(
            '/proveedores'
        )

        proveedores.value = response.data
    }

    catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| MODAL
|--------------------------------------------------------------------------
*/

const abrirModal = () => {

    limpiarFormulario()

    mostrarModal.value = true
}

const cerrarModal = () => {

    mostrarModal.value = false
}

/*
|--------------------------------------------------------------------------
| EDITAR
|--------------------------------------------------------------------------
*/

const editarProveedor = (proveedor) => {

    editando.value = true

    proveedorId.value = proveedor.id

    form.ruc = proveedor.ruc

    form.razon_social =
        proveedor.razon_social

    form.telefono =
        proveedor.telefono

    form.email =
        proveedor.email

    form.direccion =
        proveedor.direccion

    form.contacto =
        proveedor.contacto

    mostrarModal.value = true
}

/*
|--------------------------------------------------------------------------
| GUARDAR
|--------------------------------------------------------------------------
*/

const guardarProveedor = async () => {

    try {

        if (editando.value) {

            await api.put(

                `/proveedores/${proveedorId.value}`,

                form
            )

            Swal.fire(

                'Actualizado',

                'Proveedor actualizado',

                'success'
            )
        }

        else {

            await api.post(

                '/proveedores',

                form
            )

            Swal.fire(

                'Correcto',

                'Proveedor registrado',

                'success'
            )
        }

        cerrarModal()

        cargarProveedores()

        limpiarFormulario()
    }

    catch (error) {

        console.log(error)

        Swal.fire(

            'Error',

            'Ocurrió un error',

            'error'
        )
    }
}

/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

const eliminarProveedor = async (id) => {

    const result = await Swal.fire({

        title: '¿Eliminar proveedor?',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText:
            'Sí, eliminar'
    })

    if (result.isConfirmed) {

        try {

            await api.delete(

                `/proveedores/${id}`
            )

            Swal.fire(

                'Eliminado',

                'Proveedor eliminado',

                'success'
            )

            cargarProveedores()
        }

        catch (error) {

            console.log(error)
        }
    }
}

/*
|--------------------------------------------------------------------------
| RESET
|--------------------------------------------------------------------------
*/

const limpiarFormulario = () => {

    editando.value = false

    proveedorId.value = null

    form.ruc = ''

    form.razon_social = ''

    form.telefono = ''

    form.email = ''

    form.direccion = ''

    form.contacto = ''
}

onMounted(() => {

    cargarProveedores()
})

</script>

<style scoped>

.fm-input {

    background: var(--navy-700);

    color: var(--white);

    border: 1px solid var(--white-10);

    border-radius: 10px;

    padding: 10px 14px;

    width: 100%;

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