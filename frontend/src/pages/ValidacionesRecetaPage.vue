<template>

<div class="container-fluid p-4">

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2
        class="text-3xl font-bold text-black">

            Validación de Recetas
        </h2>

        <button

            class="btn btn-primary"

            data-bs-toggle="modal"

            data-bs-target="#modalNuevaValidacion"
        >

            + Nueva Validación
        </button>
    </div>

    <!-- TABLA -->

    <div class="card shadow-sm">

        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>

                        <th>Cliente</th>

                        <th>Producto</th>

                        <th>Cantidad</th>

                        <th>Tipo</th>

                        <th>Estado</th>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <tr

                        v-for="item in validaciones"

                        :key="item.id"
                    >

                        <td>

                            {{ item.id }}
                        </td>

                        <td>

                            {{ item.cliente?.nombre }}
                        </td>

                        <td>

                            {{ item.producto?.nombre }}
                        </td>

                        <td>

                            {{ item.cantidad_aprobada }}
                        </td>

                        <td>

                            {{ item.tipo_venta }}
                        </td>

                        <td>

                            <span

                                class="badge"

                                :class="{

                                    'bg-warning':

                                        item.estado === 'PENDIENTE',

                                    'bg-success':

                                        item.estado === 'APROBADO',

                                    'bg-danger':

                                        item.estado === 'RECHAZADO'
                                }"
                            >

                                {{ item.estado }}
                            </span>
                        </td>

                        <td>

                            <div class="d-flex gap-2">

                                <button

                                    class="btn btn-success btn-sm"

                                    @click="aprobar(item.id)"

                                    :disabled="item.estado !== 'PENDIENTE'"
                                >

                                    Aprobar
                                </button>

                                <button

                                    class="btn btn-danger btn-sm"

                                    @click="rechazar(item.id)"

                                    :disabled="item.estado !== 'PENDIENTE'"
                                >

                                    Rechazar
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="validaciones.length === 0">

                        <td

                            colspan="7"

                            class="text-center"
                        >

                            No existen validaciones
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL -->

    <div

        class="modal fade"

        id="modalNuevaValidacion"

        tabindex="-1"
    >

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <!-- HEADER -->

                <div class="modal-header">

                    <h5 class="modal-title">

                        Nueva Validación
                    </h5>

                    <button

                        type="button"

                        class="btn-close"

                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <!-- BODY -->

                <div class="modal-body">

                    <div class="row g-3">

                        <!-- CLIENTE -->

                        <div class="col-md-6">

                            <label class="form-label">

                                Cliente
                            </label>

                            <select

                                class="form-select"

                                v-model="form.cliente_id"
                            >

                                <option disabled value="null" >

                                    Seleccione cliente
                                </option>

                                <option

                                    v-for="cliente in clientes"

                                    :key="cliente.id"

                                    :value="cliente.id"
                                >

                                    {{ cliente.nombre }}
                                </option>
                            </select>
                        </div>

                        <!-- PRODUCTO -->

                        <div class="col-md-6">

                            <label class="form-label">

                                Producto
                            </label>

                            <select

                                class="form-select"

                                v-model="form.producto_id"
                            >

                                <option disabled value="null" >

                                    Seleccione producto
                                </option>

                                <option

                                    v-for="producto in productos"

                                    :key="producto.id"

                                    :value="producto.id"
                                >

                                    {{ producto.nombre }}
                                </option>
                            </select>
                        </div>

                        <!-- CANTIDAD -->

                        <div class="col-md-4">

                            <label class="form-label">

                                Cantidad
                            </label>

                            <input

                                type="number"

                                class="form-control"

                                min="1"

                                v-model="form.cantidad"
                            >
                        </div>

                        <!-- TIPO -->

                        <div class="col-md-4">

                            <label class="form-label">

                                Tipo venta
                            </label>

                            <select

                                class="form-select"

                                v-model="form.tipo_venta"
                            >

                                <option value="CAJA">

                                    Caja
                                </option>

                                <option value="BLISTER">

                                    BLISTER
                                </option>

                                <option value="UNIDAD">

                                    UNIDAD
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->

                <div class="modal-footer">

                    <button

                        class="btn btn-secondary"

                        data-bs-dismiss="modal"
                    >

                        Cancelar
                    </button>

                    <button

                        class="btn btn-primary"

                        @click="guardarValidacion"
                    >

                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script setup>

import {

    ref,

    onMounted

} from 'vue'

import api from '../services/api'

/*
|--------------------------------------------------------------------------
| ARRAYS
|--------------------------------------------------------------------------
*/

const validaciones = ref([])

const clientes = ref([])

const productos = ref([])

/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = ref({

    cliente_id: null,

    producto_id: null,

    cantidad: 1,

    tipo_venta: 'CAJA'
})

/*
|--------------------------------------------------------------------------
| OBTENER VALIDACIONES
|--------------------------------------------------------------------------
*/

const obtenerValidaciones = async () => {

    try {

        const response = await api.get(

            '/validaciones-receta'
        )

        validaciones.value =

            response.data.data

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| CLIENTES
|--------------------------------------------------------------------------
*/

const obtenerClientes = async () => {

    try {

        const response = await api.get(

            '/clientes'
        )

        clientes.value =

            response.data.data

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| PRODUCTOS
|--------------------------------------------------------------------------
*/

const obtenerProductos = async () => {

    try {

        const response = await api.get(

            '/productos'
        )

        /*
        |--------------------------------------------------------------------------
        | SOLO RESTRINGIDOS
        |--------------------------------------------------------------------------
        */

        productos.value =

            response.data.data.filter(

                p => p.requiere_receta == 1
            )

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| GUARDAR
|--------------------------------------------------------------------------
*/
const guardarValidacion = async () => {

    try {

        const data = {

            cliente_id: Number(form.value.cliente_id),

            producto_id: Number(form.value.producto_id),

            cantidad_aprobada: Number(form.value.cantidad),

            tipo_venta: form.value.tipo_venta
        }

        await api.post('/validaciones-receta', data)

        alert('Validación guardada')

        await obtenerValidaciones()

        form.value = {
            cliente_id: null,
            producto_id: null,
            cantidad: 1,
            tipo_venta: 'CAJA'
        }

        const modalElement = document.getElementById('modalNuevaValidacion')

        if (modalElement && window.bootstrap) {
            const modal = window.bootstrap.Modal.getInstance(modalElement)
            if (modal) {
                modal.hide()
            }
        }

    } catch (error) {

        console.log(error)

        alert('Error al guardar la validación')
    }
}






/*
|--------------------------------------------------------------------------
| APROBAR
|--------------------------------------------------------------------------
*/

const aprobar = async (id) => {

    try {

        await api.put(

            `/validaciones-receta/${id}/aprobar`
        )

        obtenerValidaciones()

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| RECHAZAR
|--------------------------------------------------------------------------
*/

const rechazar = async (id) => {

    try {

        await api.put(

            `/validaciones-receta/${id}/rechazar`
        )

        obtenerValidaciones()

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| MOUNT
|--------------------------------------------------------------------------
*/

onMounted(() => {

    obtenerValidaciones()

    obtenerClientes()

    obtenerProductos()
})

</script>



<style scoped>

/* ===================================================== */
/* FARMAVIDA — Bootstrap overrides                        */
/* ===================================================== */

/* Fondo de página */
.container,
.container-fluid {
    color: var(--white);
}

/* Títulos */
h1, h2, h3, h4, h5 {
    color: var(--white);
}

/* Cards */
.card {
    background: var(--navy-800);
    border: 1px solid var(--white-10);
    color: var(--white);
}

.card-body {
    background: var(--navy-800);
}

/* Tabla */
.table {
    color: var(--white);
    border-color: var(--white-10);
}

.table-bordered > :not(caption) > * > * {
    border-color: var(--white-10);
}

.table-bordered {
    border-color: var(--white-10);
}

.table > :not(caption) > * > * {
    background-color: transparent;
    color: var(--white);
}

.table-dark thead th {
    background: var(--navy-700);
    color: var(--blue-200);
    border-color: var(--white-10);
}

.table-light thead th,
thead.table-light th {
    background: var(--navy-700);
    color: var(--blue-200);
    border-color: var(--white-10);
}

.table-hover > tbody > tr:hover > * {
    background-color: rgba(255,255,255,0.04);
    color: var(--white);
}

/* Formularios */
.form-control,
.form-select {
    background: var(--navy-700);
    color: var(--white);
    border: 1px solid var(--white-10);
}

.form-control::placeholder {
    color: var(--white-30);
}

.form-control:focus,
.form-select:focus {
    background: var(--navy-700);
    color: var(--white);
    border-color: var(--blue-500);
    box-shadow: 0 0 0 0.2rem var(--blue-glow);
}

.form-label {
    color: var(--white-80);
}

/* Modal */
.modal-content {
    background: var(--navy-800);
    border: 1px solid var(--white-10);
    color: var(--white);
}

.modal-header {
    border-bottom: 1px solid var(--white-10);
}

.modal-footer {
    border-top: 1px solid var(--white-10);
}

.modal-title {
    color: var(--white);
}

.btn-close {
    filter: invert(1) brightness(2);
}

/* Botones */
.btn-primary {
    background: var(--blue-500);
    border-color: var(--blue-500);
}

.btn-primary:hover {
    background: var(--blue-400);
    border-color: var(--blue-400);
}

.btn-secondary {
    background: var(--navy-600);
    border-color: var(--navy-600);
    color: var(--white-80);
}

.btn-secondary:hover {
    background: var(--navy-700);
    border-color: var(--navy-700);
    color: var(--white);
}

/* Select options */
select option {
    background: var(--navy-800);
    color: var(--white);
}

</style>