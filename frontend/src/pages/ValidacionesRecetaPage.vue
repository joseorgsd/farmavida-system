<template>

<div class="container-fluid p-4">

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2
        class="text-3xl font-bold text-black">

            Validación de Recetas
        </h2>
    </div>

    <!-- TABLA -->

    <div class="card shadow-sm">

        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>

                        <th>Cliente</th>

                        <th>Médico</th>

                        <th>Productos</th>

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

                            <template v-if="item.nombre_medico">

                                <div>{{ item.nombre_medico }}</div>

                                <small class="text-muted">CMP: {{ item.cmp_medico }}</small>
                            </template>

                            <span

                                v-else

                                class="text-muted"
                            >

                                Sin completar
                            </span>
                        </td>

                        <td>

                            <ul class="mb-0 ps-3">

                                <li

                                    v-for="detalle in item.detalles"

                                    :key="detalle.id"
                                >

                                    {{ detalle.producto?.nombre }}

                                    ({{ detalle.cantidad }} - {{ detalle.tipo_venta }})
                                </li>
                            </ul>
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

                                        item.estado === 'RECHAZADO',

                                    'bg-secondary':

                                        item.estado === 'VENDIDO'
                                }"
                            >

                                {{ item.estado }}
                            </span>
                        </td>

                        <td>

                            <button

                                class="btn btn-primary btn-sm"

                                @click="abrirModalCompletar(item)"

                                :disabled="item.estado !== 'PENDIENTE'"
                            >

                                Completar / Validar
                            </button>
                        </td>
                    </tr>

                    <tr v-if="validaciones.length === 0">

                        <td

                            colspan="6"

                            class="text-center"
                        >

                            No existen validaciones
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | MODAL COMPLETAR / VALIDAR
    |--------------------------------------------------------------------------
    -->

    <div

        class="modal fade"

        id="modalCompletar"

        tabindex="-1"
    >

        <div class="modal-dialog modal-lg">

            <div class="modal-content" v-if="seleccion">

                <!-- HEADER -->

                <div class="modal-header">

                    <h5 class="modal-title">

                        Validación #{{ seleccion.id }} — {{ seleccion.cliente?.nombre }}
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

                        <!-- NOMBRE MEDICO -->

                        <div class="col-md-6">

                            <label class="form-label">

                                Nombre del médico
                            </label>

                            <input

                                type="text"

                                class="form-control"

                                v-model="formDatos.nombre_medico"
                            >
                        </div>

                        <!-- CMP MEDICO -->

                        <div class="col-md-6">

                            <label class="form-label">

                                CMP del médico
                            </label>

                            <input

                                type="text"

                                class="form-control"

                                v-model="formDatos.cmp_medico"
                            >
                        </div>

                        <!-- FECHA RECETA -->

                        <div class="col-md-6">

                            <label class="form-label">

                                Fecha de receta
                            </label>

                            <input

                                type="date"

                                class="form-control"

                                v-model="formDatos.fecha_receta"
                            >
                        </div>

                        <!-- INDICACIONES -->

                        <div class="col-12">

                            <label class="form-label">

                                Indicaciones
                            </label>

                            <textarea

                                class="form-control"

                                rows="2"

                                v-model="formDatos.indicaciones"
                            ></textarea>
                        </div>

                        <!-- OBSERVACIONES -->

                        <div class="col-12">

                            <label class="form-label">

                                Observaciones
                            </label>

                            <textarea

                                class="form-control"

                                rows="2"

                                v-model="formDatos.observaciones"
                            ></textarea>
                        </div>

                        <div class="col-12 text-end">

                            <button

                                class="btn btn-outline-primary btn-sm"

                                @click="guardarDatos"
                            >

                                Guardar datos
                            </button>
                        </div>
                    </div>

                    <hr>

                    <!-- PRODUCTOS -->

                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <label class="form-label mb-0">

                            Productos
                        </label>
                    </div>

                    <ul class="list-group mb-3">

                        <li

                            v-for="detalle in seleccion.detalles"

                            :key="detalle.id"

                            class="list-group-item d-flex justify-content-between align-items-center"
                        >

                            {{ detalle.producto?.nombre }}

                            —

                            {{ detalle.cantidad }}

                            ({{ detalle.tipo_venta }})

                            <button

                                class="btn btn-outline-danger btn-sm"

                                @click="quitarProducto(detalle.id)"
                            >

                                Quitar
                            </button>
                        </li>

                        <li

                            v-if="seleccion.detalles.length === 0"

                            class="list-group-item text-center text-muted"
                        >

                            Sin productos
                        </li>
                    </ul>

                    <!-- AGREGAR PRODUCTO -->

                    <div class="row g-2 align-items-end">

                        <div class="col-md-5">

                            <select

                                class="form-select"

                                v-model="nuevoProducto.producto_id"
                            >

                                <option disabled value="null">

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

                        <div class="col-md-3">

                            <input

                                type="number"

                                class="form-control"

                                min="1"

                                v-model="nuevoProducto.cantidad"

                                placeholder="Cantidad"
                            >
                        </div>

                        <div class="col-md-3">

                            <select

                                class="form-select"

                                v-model="nuevoProducto.tipo_venta"
                            >

                                <option value="CAJA">Caja</option>

                                <option value="BLISTER">Blister</option>

                                <option value="UNIDAD">Unidad</option>
                            </select>
                        </div>

                        <div class="col-md-1">

                            <button

                                type="button"

                                class="btn btn-outline-primary btn-sm"

                                @click="agregarProducto"
                            >

                                +
                            </button>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->

                <div class="modal-footer">

                    <button

                        class="btn btn-secondary"

                        data-bs-dismiss="modal"
                    >

                        Cerrar
                    </button>

                    <button

                        class="btn btn-danger"

                        @click="rechazar(seleccion.id)"
                    >

                        Rechazar
                    </button>

                    <button

                        class="btn btn-success"

                        @click="aprobar(seleccion.id)"
                    >

                        Aprobar
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

import {

    Modal

} from 'bootstrap'

/*
|--------------------------------------------------------------------------
| ARRAYS
|--------------------------------------------------------------------------
*/

const validaciones = ref([])

const productos = ref([])

const seleccion = ref(null)

let modalCompletar = null

/*
|--------------------------------------------------------------------------
| FORM DATOS MEDICOS
|--------------------------------------------------------------------------
*/

const formDatos = ref({

    nombre_medico: '',

    cmp_medico: '',

    fecha_receta: '',

    indicaciones: '',

    observaciones: ''
})

/*
|--------------------------------------------------------------------------
| NUEVO PRODUCTO
|--------------------------------------------------------------------------
*/

const nuevoProducto = ref({

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
| PRODUCTOS (RESTRINGIDOS, PARA AGREGAR)
|--------------------------------------------------------------------------
*/

const obtenerProductos = async () => {

    try {

        const response = await api.get(

            '/productos'
        )

        const lista =

            Array.isArray(response.data.data)

            ?

            response.data.data

            :

            response.data.data.data

        productos.value =

            lista.filter(

                p => p.requiere_receta == 1
            )

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| ABRIR MODAL COMPLETAR
|--------------------------------------------------------------------------
*/

const abrirModalCompletar = (item) => {

    seleccion.value = item

    formDatos.value = {

        nombre_medico: item.nombre_medico || '',

        cmp_medico: item.cmp_medico || '',

        fecha_receta: item.fecha_receta || '',

        indicaciones: item.indicaciones || '',

        observaciones: item.observaciones || ''
    }

    nuevoProducto.value = {

        producto_id: null,

        cantidad: 1,

        tipo_venta: 'CAJA'
    }

    modalCompletar.show()
}

/*
|--------------------------------------------------------------------------
| GUARDAR DATOS MEDICOS
|--------------------------------------------------------------------------
*/

const guardarDatos = async () => {

    try {

        const response = await api.put(

            `/validaciones-receta/${seleccion.value.id}`,

            formDatos.value
        )

        seleccion.value = response.data.data

        await obtenerValidaciones()

        alert('Datos guardados')

    } catch (error) {

        console.log(error)

        alert(

            error.response?.data?.message

            ||

            'Error al guardar los datos'
        )
    }
}

/*
|--------------------------------------------------------------------------
| AGREGAR PRODUCTO
|--------------------------------------------------------------------------
*/

const agregarProducto = async () => {

    if (!nuevoProducto.value.producto_id) {

        alert('Seleccione producto')

        return
    }

    try {

        const response = await api.post(

            `/validaciones-receta/${seleccion.value.id}/productos`,

            nuevoProducto.value
        )

        seleccion.value.detalles.push(

            response.data.data
        )

        nuevoProducto.value = {

            producto_id: null,

            cantidad: 1,

            tipo_venta: 'CAJA'
        }

        await obtenerValidaciones()

    } catch (error) {

        console.log(error)

        alert(

            error.response?.data?.message

            ||

            'Error al agregar producto'
        )
    }
}

/*
|--------------------------------------------------------------------------
| QUITAR PRODUCTO
|--------------------------------------------------------------------------
*/

const quitarProducto = async (detalleId) => {

    try {

        await api.delete(

            `/validaciones-receta/${seleccion.value.id}/productos/${detalleId}`
        )

        seleccion.value.detalles =

            seleccion.value.detalles.filter(

                d => d.id !== detalleId
            )

        await obtenerValidaciones()

    } catch (error) {

        console.log(error)

        alert(

            error.response?.data?.message

            ||

            'Error al quitar producto'
        )
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

        modalCompletar.hide()

        obtenerValidaciones()

    } catch (error) {

        console.log(error)

        alert(

            error.response?.data?.message

            ||

            'Error al aprobar. Verifique que los datos del médico estén completos.'
        )
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

        modalCompletar.hide()

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

    modalCompletar = new Modal(

        document.getElementById(

            'modalCompletar'
        )
    )

    obtenerValidaciones()

    obtenerProductos()
})

</script>

<style scoped>

/* ===================================================== */
/* FARMAVIDA — Bootstrap overrides                        */
/* ===================================================== */

.container,
.container-fluid {
    color: var(--white);
}

h1, h2, h3, h4, h5 {
    color: var(--white);
}

.card {
    background: var(--navy-800);
    border: 1px solid var(--white-10);
    color: var(--white);
}

.card-body {
    background: var(--navy-800);
}

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

.form-control,
.form-select {
    background: var(--navy-700);
    color: var(--white);
    border: 1px solid var(--white-10);
}

.form-label {
    color: var(--white-80);
}

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

.list-group-item {
    background: var(--navy-700);
    color: var(--white);
    border-color: var(--white-10);
}

.btn-close {
    filter: invert(1) brightness(2);
}

</style>