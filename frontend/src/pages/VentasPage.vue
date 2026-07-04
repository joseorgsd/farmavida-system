<template>

<div class="container-fluid">

    <!--
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2
        class="text-3xl font-bold text-black">

            Ventas
        </h2>

        <button

            class="btn btn-success"

            @click="abrirModalCliente"
        >

            + Cliente
        </button>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | CLIENTES CON RECETA APROBADA PENDIENTE DE VENTA
    |--------------------------------------------------------------------------
    -->

    <div

        v-if="validacionesAprobadasPendientes.length > 0"

        class="card mb-4 shadow-sm border-success"
    >

        <div class="card-header bg-success bg-opacity-10">

            Clientes con receta aprobada, listos para vender
        </div>

        <div class="card-body table-responsive">

            <table class="table table-sm align-middle mb-0">

                <thead>

                    <tr>

                        <th>Cliente</th>

                        <th>Médico</th>

                        <th>Productos</th>

                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    <tr

                        v-for="validacion in validacionesAprobadasPendientes"

                        :key="validacion.id"
                    >

                        <td>

                            {{ validacion.cliente?.nombre }}
                        </td>

                        <td>

                            {{ validacion.nombre_medico }}
                        </td>

                        <td>

                            <ul class="mb-0 ps-3">

                                <li

                                    v-for="detalle in validacion.detalles"

                                    :key="detalle.id"
                                >

                                    {{ detalle.producto?.nombre }}

                                    ({{ detalle.cantidad }} - {{ detalle.tipo_venta }})
                                </li>
                            </ul>
                        </td>

                        <td>

                            <button

                                class="btn btn-success btn-sm"

                                @click="cargarValidacionAprobada(validacion)"
                            >

                                Cargar al carrito
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | CLIENTE
    |--------------------------------------------------------------------------
    -->

    <div class="card mb-4 shadow-sm">

        <div class="card-body">

            <label class="form-label">

                Cliente
            </label>

            <select

                class="form-select"

                v-model="venta.cliente_id"
            >

                <option value="">

                    Seleccione cliente
                </option>

                <option

                    v-for="cliente in clientes"

                    :key="cliente.id"

                    :value="cliente.id"
                >

                    {{ cliente.nombre }}

                    

                    {{ cliente.DNI }}
                </option>
            </select>
        </div>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | PRODUCTOS
    |--------------------------------------------------------------------------
    -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row">

                <!-- PRODUCTO -->

                <div class="col-md-5">

                    <label class="form-label">

                        Producto
                    </label>

                    <select

                        class="form-select"

                        v-model="productoSeleccionado"
                    >

                        <option value="">

                            Seleccione producto
                        </option>

                        <option

                            v-for="producto in productos"

                            :key="producto.id"

                            :value="producto"
                        >

                            {{ producto.nombre }}

                            -

                            Stock:

                            {{ producto.stock_unidades }}

                            <template v-if="producto.requiere_receta">

                                (requiere receta)
                            </template>
                        </option>
                    </select>
                </div>

                <!-- TIPO -->

                <div class="col-md-3">

                    <label class="form-label">

                        Tipo Venta
                    </label>

                    <select

                        class="form-select"

                        v-model="tipoVenta"
                    >

                        <option value="CAJA">

                            Caja
                        </option>

                        <option value="BLISTER">

                            Blister
                        </option>

                        <option value="UNIDAD">

                            Unidad
                        </option>
                    </select>
                </div>

                <!-- CANTIDAD -->

                <div class="col-md-2">

                    <label class="form-label">

                        Cantidad
                    </label>

                    <input

                        type="number"

                        min="1"

                        class="form-control"

                        v-model="cantidad"
                    >
                </div>

                <!-- BOTON -->

                <div class="col-md-2 d-flex align-items-end">

                    <button

                        class="btn btn-primary w-100"

                        @click="agregarProducto"
                    >

                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | DERIVAR AL QUIMICO
    |--------------------------------------------------------------------------
    -->

    <div

        v-if="itemsRestringidosSinDerivar.length > 0"

        class="alert alert-warning d-flex justify-content-between align-items-center"
    >

        <span>

            {{ itemsRestringidosSinDerivar.length }} producto(s) requieren receta y aún no se han derivado.
        </span>

        <button

            class="btn btn-warning btn-sm"

            @click="derivarCliente"
        >

            Derivar al Químico
        </button>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | DETALLE
    |--------------------------------------------------------------------------
    -->

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>Producto</th>

                        <th>Tipo</th>

                        <th>Cantidad</th>

                        <th>Precio</th>

                        <th>Subtotal</th>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <tr

                        v-for="(item,index) in detalleVenta"

                        :key="index"
                    >

                        <!-- PRODUCTO -->

                        <td>

                            {{ item.producto.nombre }}

                            <!--
                            |--------------------------------------------------------------------------
                            | RESTRINGIDO
                            |--------------------------------------------------------------------------
                            -->

                            <div
                                v-if="item.producto.requiere_receta"
                                class="mt-2"
                            >

                                <span class="badge bg-danger">

                                    Requiere receta
                                </span>

                                <!-- VALIDADO -->

                                <span

                                    v-if="item.validado"

                                    class="badge bg-success ms-2"
                                >

                                    Validado
                                </span>

                                <!-- DERIVADO, EN PROCESO -->

                                <template v-else-if="item.derivado">

                                    <span class="badge bg-warning ms-2">

                                        En proceso con el químico
                                    </span>
                                </template>

                                <!-- SIN DERIVAR -->

                                <span

                                    v-else

                                    class="badge bg-secondary ms-2"
                                >

                                    Sin derivar
                                </span>
                            </div>
                        </td>

                        <!-- TIPO -->

                        <td>

                            {{ item.tipo_venta }}
                        </td>

                        <!-- CANTIDAD -->

                        <td>

                            {{ item.cantidad }}
                        </td>

                        <!-- PRECIO -->

                        <td>

                            S/

                            {{ item.precio }}
                        </td>

                        <!-- SUBTOTAL -->

                        <td>

                            S/

                            {{ item.subtotal.toFixed(2) }}
                        </td>

                        <!-- ELIMINAR -->

                        <td>

                            <button

                                class="btn btn-danger btn-sm"

                                @click="eliminarProducto(index)"
                            >

                                X
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- TOTAL -->

            <div class="text-end mt-4">

                <h1>

                    TOTAL:

                    S/

                    {{ totalVenta.toFixed(2) }}
                </h1>
            </div>

            <!-- REGISTRAR -->

            <div class="text-end mt-4">

                <button

                    class="btn btn-primary btn-lg"

                    @click="registrarVenta"
                >

                    Registrar Venta
                </button>
            </div>
        </div>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | MODAL CLIENTE
    |--------------------------------------------------------------------------
    -->

    <div

        class="modal fade"

        id="modalCliente"

        tabindex="-1"
    >

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Nuevo Cliente
                    </h5>

                    <button

                        type="button"

                        class="btn-close"

                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <div class="modal-body">

                    <!-- NOMBRE -->

                    <div class="mb-3">

                        <label class="form-label">

                            Nombre
                        </label>

                        <input

                            type="text"

                            class="form-control"

                            v-model="cliente.nombre"
                        >
                    </div>

                    <!-- DNI -->

                    <div class="mb-3">

                        <label class="form-label">

                            DNI
                        </label>

                        <input

                            type="text"

                            class="form-control"

                            v-model="cliente.dni"
                        >
                    </div>

                    <!-- TELEFONO -->

                    <div class="mb-3">

                        <label class="form-label">

                            Teléfono
                        </label>

                        <input

                            type="text"

                            class="form-control"

                            v-model="cliente.telefono"
                        >
                    </div>

                    <!-- DIRECCION -->

                    <div class="mb-3">

                        <label class="form-label">

                            Dirección
                        </label>

                        <input

                            type="text"

                            class="form-control"

                            v-model="cliente.direccion"
                        >
                    </div>

                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label class="form-label">

                            Email
                        </label>

                        <input

                            type="email"

                            class="form-control"

                            v-model="cliente.email"
                        >
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

                        @click="guardarCliente"
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

    computed,

    onMounted,

    onUnmounted

} from 'vue'

import api from '../services/api'

import {

    Modal

} from 'bootstrap'

/*
|--------------------------------------------------------------------------
| DATA
|--------------------------------------------------------------------------
*/

const productos = ref([])

const clientes = ref([])

const detalleVenta = ref([])

const productoSeleccionado = ref(null)

const cantidad = ref(1)

const tipoVenta = ref('CAJA')

const validacionesAprobadasPendientes = ref([])

const idsValidacionesCargadas = ref(new Set())

let modalCliente = null

let intervaloValidaciones = null

/*
|--------------------------------------------------------------------------
| VENTA
|--------------------------------------------------------------------------
*/

const venta = ref({

    cliente_id: ''
})

/*
|--------------------------------------------------------------------------
| CLIENTE
|--------------------------------------------------------------------------
*/

const cliente = ref({

    nombre: '',

    dni: '',

    telefono: '',

    direccion: '',

    email: ''
})

/*
|--------------------------------------------------------------------------
| PRODUCTOS
|--------------------------------------------------------------------------
*/

const obtenerProductos = async () => {

    try {

        const response =

            await api.get('/productos')

        productos.value =

            Array.isArray(response.data.data)

            ?

            response.data.data

            :

            response.data.data.data

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

        const response =

            await api.get('/clientes')

        clientes.value =

            Array.isArray(response.data.data)

            ?

            response.data.data

            :

            response.data.data.data

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| VALIDACIONES APROBADAS PENDIENTES DE VENTA
|--------------------------------------------------------------------------
*/

const obtenerValidacionesAprobadasPendientes = async () => {

    try {

        const response =

            await api.get(

                '/validaciones-receta/pendientes-venta'
            )

        validacionesAprobadasPendientes.value =

            response.data.data.filter(

                v => !idsValidacionesCargadas.value.has(v.id)
            )

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| PRECIO SEGÚN TIPO DE VENTA
|--------------------------------------------------------------------------
*/

const precioSegunTipo = (producto, tipoVentaItem) => {

    if (tipoVentaItem === 'CAJA') {

        return parseFloat(producto.precio_venta)
    }

    if (tipoVentaItem === 'BLISTER') {

        return parseFloat(producto.precio_blister)
    }

    return parseFloat(producto.precio_unidad)
}

/*
|--------------------------------------------------------------------------
| CARGAR VALIDACIÓN APROBADA AL CARRITO
|--------------------------------------------------------------------------
*/

const cargarValidacionAprobada = (validacion) => {

    venta.value.cliente_id =

        validacion.cliente_id

    validacion.detalles.forEach(detalle => {

        const producto =

            detalle.producto

        const precio =

            precioSegunTipo(

                producto,

                detalle.tipo_venta
            )

        const subtotal =

            precio * detalle.cantidad

        detalleVenta.value.push({

            producto,

            producto_id: producto.id,

            tipo_venta: detalle.tipo_venta,

            cantidad: detalle.cantidad,

            precio,

            subtotal,

            derivado: true,

            validado: true
        })
    })

    idsValidacionesCargadas.value.add(

        validacion.id
    )

    validacionesAprobadasPendientes.value =

        validacionesAprobadasPendientes.value.filter(

            v => v.id !== validacion.id
        )

    alert(

        'Productos cargados al carrito. Puede continuar con la venta.'
    )
}

/*
|--------------------------------------------------------------------------
| AGREGAR PRODUCTO
|--------------------------------------------------------------------------
*/

const agregarProducto = () => {

    if (!productoSeleccionado.value) {

        alert('Seleccione producto')

        return
    }

    const precio =

        precioSegunTipo(

            productoSeleccionado.value,

            tipoVenta.value
        )

    const subtotal =

        precio * cantidad.value

    detalleVenta.value.push({

        producto:

            productoSeleccionado.value,

        producto_id:

            productoSeleccionado.value.id,

        tipo_venta:

            tipoVenta.value,

        cantidad:

            cantidad.value,

        precio,

        subtotal,

        derivado: false,

        validado: false
    })

    productoSeleccionado.value = null

    cantidad.value = 1

    tipoVenta.value = 'CAJA'
}

/*
|--------------------------------------------------------------------------
| ELIMINAR
|--------------------------------------------------------------------------
*/

const eliminarProducto = (index) => {

    detalleVenta.value.splice(index,1)
}

/*
|--------------------------------------------------------------------------
| TOTAL
|--------------------------------------------------------------------------
*/

const totalVenta = computed(() => {

    return detalleVenta.value.reduce(

        (total,item) =>

            total + item.subtotal,

        0
    )
})

/*
|--------------------------------------------------------------------------
| RESTRINGIDOS SIN DERIVAR
|--------------------------------------------------------------------------
*/

const itemsRestringidosSinDerivar = computed(() => {

    return detalleVenta.value.filter(

        item =>

            item.producto.requiere_receta

            &&

            !item.derivado

            &&

            !item.validado
    )
})

/*
|--------------------------------------------------------------------------
| DERIVAR CLIENTE (SOLO CLIENTE + PRODUCTOS, SIN DATOS MEDICOS)
|--------------------------------------------------------------------------
*/

const derivarCliente = async () => {

    if (!venta.value.cliente_id) {

        alert('Seleccione cliente')

        return
    }

    try {

        const productosDerivar =

            itemsRestringidosSinDerivar.value.map(item => ({

                producto_id: item.producto.id,

                cantidad: item.cantidad,

                tipo_venta: item.tipo_venta
            }))

        await api.post(

            '/validaciones-receta',

            {

                cliente_id:

                    venta.value.cliente_id,

                productos: productosDerivar
            }
        )

        itemsRestringidosSinDerivar.value.forEach(item => {

            item.derivado = true
        })

        alert('Cliente derivado al químico farmacéutico')

    } catch (error) {

        console.log(error)

        alert(

            error.response?.data?.message

            ||

            'Error al derivar cliente'
        )
    }
}

/*
|--------------------------------------------------------------------------
| POLLING DE VALIDACIONES (ITEMS EN CARRITO YA DERIVADOS)
|--------------------------------------------------------------------------
*/

const chequearValidacionesPendientes = async () => {

    const pendientes =

        detalleVenta.value.filter(

            item =>

                item.producto.requiere_receta

                &&

                item.derivado

                &&

                !item.validado
        )

    if (pendientes.length === 0) {

        return
    }

    try {

        const response =

            await api.get('/validaciones-receta')

        const validaciones =

            response.data.data

        pendientes.forEach(item => {

            const encontrado =

                validaciones.some(v => {

                    if (

                        v.cliente_id != venta.value.cliente_id

                        ||

                        v.estado !== 'APROBADO'

                        ||

                        v.usado
                    ) {

                        return false
                    }

                    return v.detalles?.some(d =>

                        d.producto_id === item.producto.id

                        &&

                        d.tipo_venta === item.tipo_venta

                        &&

                        Number(d.cantidad) >= Number(item.cantidad)
                    )
                })

            if (encontrado && !item.validado) {

                item.validado = true

                alert(

                    `El químico validó: ${item.producto.nombre}`
                )
            }
        })

    } catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| REGISTRAR
|--------------------------------------------------------------------------
*/

const registrarVenta = async () => {

    try {

        const response =

            await api.post(

                '/ventas',

                {

                    cliente_id:

                        venta.value.cliente_id,

                    tipo_comprobante:

                        'BOLETA',

                    productos:

                        detalleVenta.value.map(item => ({

                            producto_id: item.producto.id,

                            cantidad: item.cantidad,

                            tipo_venta: item.tipo_venta
                        }))
                }
            )

        alert(

            'Venta registrada correctamente'
        )

        console.log(response.data)

        detalleVenta.value = []

        await obtenerValidacionesAprobadasPendientes()

    } catch (error) {

        console.log(error)

        alert(

            error.response?.data?.message

            ||

            'Error al registrar venta'
        )
    }
}

/*
|--------------------------------------------------------------------------
| MODAL
|--------------------------------------------------------------------------
*/

const abrirModalCliente = () => {

    cliente.value = {

        nombre: '',

        dni: '',

        telefono: '',

        direccion: '',

        email: ''
    }

    modalCliente.show()
}

/*
|--------------------------------------------------------------------------
| GUARDAR CLIENTE
|--------------------------------------------------------------------------
*/

const guardarCliente = async () => {

    try {

        const response =

            await api.post(

                '/clientes',

                cliente.value
            )

        modalCliente.hide()

        await obtenerClientes()

        venta.value.cliente_id =

            response.data.data.id

        alert(

            'Cliente registrado'
        )

    } catch (error) {

        console.log(error)

        alert(

            error.response?.data?.message

            ||

            'Error al registrar cliente'
        )
    }
}

/*
|--------------------------------------------------------------------------
| MOUNTED
|--------------------------------------------------------------------------
*/

onMounted(async () => {

    modalCliente = new Modal(

        document.getElementById(

            'modalCliente'
        )
    )

    await obtenerProductos()

    await obtenerClientes()

    await obtenerValidacionesAprobadasPendientes()

    intervaloValidaciones = setInterval(() => {

        chequearValidacionesPendientes()

        obtenerValidacionesAprobadasPendientes()

    }, 8000)
})

onUnmounted(() => {

    if (intervaloValidaciones) {

        clearInterval(intervaloValidaciones)
    }
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

.card-header {
    background: var(--navy-700);
    color: var(--white);
    border-bottom: 1px solid var(--white-10);
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

select option {
    background: var(--navy-800);
    color: var(--white);
}

</style>