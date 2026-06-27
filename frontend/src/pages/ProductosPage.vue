<template>

<div class="container mt-4">

    <!-- ===================================================== -->
    <!-- HEADER -->
    <!-- ===================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2
         class="text-4xl font-bold mb-10 text-black">Productos
        </h2>

        <button

            class="btn btn-primary"

            data-bs-toggle="modal"

            data-bs-target="#modalProducto"
        >

            Nuevo Producto
        </button>
    </div>

    <!-- ===================================================== -->
    <!-- TABLA -->
    <!-- ===================================================== -->

    <div class="card shadow-sm">

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Nombre</th>


                        <th>Stock</th>

                        <th>Compra</th>

                        <th>Caja</th>

                        <th>Blister</th>

                        <th>Unidad</th>

                        <th>Receta</th>

                        <th width="160">

                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <tr

                        v-for="producto in productos"

                        :key="producto.id"
                    >

                        <td>

                            {{ producto.id }}
                        </td>

                        <td>

                            {{ producto.nombre }}
                        </td>


                        <td>

                            {{ producto.stock_unidades }}
                        </td>

                        <td>

                            S/. {{ producto.precio_compra }}
                        </td>

                        <td>

                            S/. {{ producto.precio_venta }}
                        </td>

                        <td>

                            S/. {{ producto.precio_blister }}
                        </td>

                        <td>

                            S/. {{ producto.precio_unidad }}
                        </td>

                        <td>

                            <span

                                v-if="producto.requiere_receta"

                                class="badge bg-danger"
                            >

                                Sí
                            </span>

                            <span

                                v-else

                                class="badge bg-success"
                            >

                                No
                            </span>
                        </td>

                        <td>

                            <button

                                class="btn btn-warning btn-sm me-2"

                                @click="editarProducto(producto)"
                            >

                                Editar
                            </button>

                            <button

                                class="btn btn-danger btn-sm"

                                @click="eliminarProducto(producto.id)"
                            >

                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===================================================== -->
    <!-- MODAL -->
    <!-- ===================================================== -->

    <div

        class="modal fade"

        id="modalProducto"

        tabindex="-1"
    >

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        {{ editando
                            ? 'Editar Producto'
                            : 'Nuevo Producto'
                        }}
                    </h5>

                    <button

                        type="button"

                        class="btn-close"

                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <!-- NOMBRE -->

                        <div class="col-md-6 mb-3">

                            <label>

                                Nombre
                            </label>

                            <input

                                type="text"

                                class="form-control"

                                v-model="form.nombre"
                            >
                        </div>


                    

                        <!-- STOCK -->

                        <div class="col-md-4 mb-3">

                            <label>

                                Stock Unidades
                            </label>

                            <input

                                type="number"

                                class="form-control"

                                v-model="form.stock_unidades"
                            >
                        </div>

                        <!-- PRECIO COMPRA -->

                        <div class="col-md-4 mb-3">

                            <label>

                                Precio Compra
                            </label>

                            <input

                                type="number"

                                step="0.01"

                                class="form-control"

                                v-model="form.precio_compra"
                            >
                        </div>

                        <!-- PRECIO CAJA -->

                        <div class="col-md-4 mb-3">

                            <label>

                                Precio Caja
                            </label>

                            <input

                                type="number"

                                step="0.01"

                                class="form-control"

                                v-model="form.precio_venta"
                            >
                        </div>

                        <!-- PRECIO BLISTER -->

                        <div class="col-md-4 mb-3">

                            <label>

                                Precio Blister
                            </label>

                            <input

                                type="number"

                                step="0.01"

                                class="form-control"

                                v-model="form.precio_blister"
                            >
                        </div>

                        <!-- PRECIO UNIDAD -->

                        <div class="col-md-4 mb-3">

                            <label>

                                Precio Unidad
                            </label>

                            <input

                                type="number"

                                step="0.01"

                                class="form-control"

                                v-model="form.precio_unidad"
                            >
                        </div>

                        <!-- BLISTER POR CAJA -->

                        <div class="col-md-4 mb-3">

                            <label>

                                Blister por Caja
                            </label>

                            <input

                                type="number"

                                class="form-control"

                                v-model="form.blisters_por_caja"
                            >
                        </div>

                        <!-- UNIDADES POR BLISTER -->

                        <div class="col-md-4 mb-3">

                            <label>

                                Unidades por Blister
                            </label>

                            <input

                                type="number"

                                class="form-control"

                                v-model="form.unidades_por_blister"
                            >
                        </div>

                        <!-- RECETA -->

                        <div class="col-md-4 mb-3">

                            <label>

                                Requiere Receta
                            </label>

                            <select

                                class="form-select"

                                v-model="form.requiere_receta"
                            >

                                <option :value="true">

                                    Sí
                                </option>

                                <option :value="false">

                                    No
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button

                        class="btn btn-secondary"

                        data-bs-dismiss="modal"
                    >

                        Cancelar
                    </button>

                    <button

                        class="btn btn-primary"

                        @click="guardarProducto"
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

const productos = ref([])

const editando = ref(false)

const productoId = ref(null)

const form = ref({

    nombre: '',


    stock_unidades: 0,

    precio_compra: 0,

    precio_venta: 0,

    precio_blister: 0,

    precio_unidad: 0,

    blisters_por_caja: 1,

    unidades_por_blister: 1,

    requiere_receta: false
})

/*
|--------------------------------------------------------------------------
| OBTENER
|--------------------------------------------------------------------------
*/

const obtenerProductos = async () => {

    try {

        const response = await api.get(

            '/productos'
        )

        productos.value =

            response.data.data
    }

    catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| GUARDAR
|--------------------------------------------------------------------------
*/

const guardarProducto = async () => {

    try {

        if (editando.value) {

            await api.put(

                `/productos/${productoId.value}`,

                form.value
            )
        }

        else {

            await api.post(

                '/productos',

                form.value
            )
        }

        alert(

            'Producto guardado correctamente'
        )

        obtenerProductos()

        limpiarFormulario()

        const modal = bootstrap.Modal.getInstance(

            document.getElementById(

                'modalProducto'
            )
        )

        modal.hide()
    }

    catch (error) {

        console.log(error)

        console.log(

            error.response?.data
        )

        alert(

            'Error al guardar producto'
        )
    }
}

/*
|--------------------------------------------------------------------------
| EDITAR
|--------------------------------------------------------------------------
*/

const editarProducto = (producto) => {

    editando.value = true

    productoId.value = producto.id

    form.value = {

        ...producto
    }

    const modal = new bootstrap.Modal(

        document.getElementById(

            'modalProducto'
        )
    )

    modal.show()
}

/*
|--------------------------------------------------------------------------
| ELIMINAR
|--------------------------------------------------------------------------
*/

const eliminarProducto = async (id) => {

    if (

        !confirm(
            '¿Eliminar producto?'
        )
    ) {

        return
    }

    try {

        await api.delete(

            `/productos/${id}`
        )

        obtenerProductos()
    }

    catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| LIMPIAR
|--------------------------------------------------------------------------
*/

const limpiarFormulario = () => {

    editando.value = false

    productoId.value = null

    form.value = {

        nombre: '',

        stock_unidades: 0,

        precio_compra: 0,

        precio_venta: 0,

        precio_blister: 0,

        precio_unidad: 0,

        blisters_por_caja: 1,

        unidades_por_blister: 1,

        requiere_receta: false
    }
}

onMounted(() => {

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