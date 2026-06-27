<template>

<div>

    <!-- HEADER -->

    <div
    class="flex justify-between items-center mb-8">

        <h1 class="text-3xl font-bold text-white">

            Compras

        </h1>

    </div>

    <!-- FORM -->

    <div
    class="bg-navy-800 border border-white/10 rounded-xl shadow p-6 mb-8">

        <div class="grid grid-cols-3 gap-4 mb-6">

            <!-- PROVEEDOR -->

            <select
                v-model="proveedor_id"
                class="fm-input">

                <option value="">
                    Seleccione proveedor
                </option>

                <option
                    v-for="proveedor in proveedores"
                    :key="proveedor.id"
                    :value="proveedor.id">

                    {{ proveedor.razon_social }}

                </option>

            </select>

            <!-- PRODUCTO -->

            <select
                v-model="productoSeleccionado"
                class="fm-input">

                <option value="">
                    Seleccione producto
                </option>

                <option
                    v-for="producto in productos"
                    :key="producto.id"
                    :value="producto">

                    {{ producto.nombre }}

                </option>

            </select>

            <!-- CANTIDAD -->

            <input
                v-model="cantidad"
                type="number"
                placeholder="Cantidad"
                class="fm-input"
            >

            <!-- PRECIO -->

            <input
                v-model="precio_compra"
                type="number"
                step="0.01"
                placeholder="Precio compra"
                class="fm-input"
            >

        </div>

        <!-- BUTTON -->

        <button

            @click="agregarProducto"

            class="bg-blue-500 hover:bg-blue-400 text-white px-5 py-3 rounded-lg transition-colors">

            Agregar Producto

        </button>

    </div>

    <!-- DETALLE -->

    <div
    class="bg-navy-800 border border-white/10 rounded-xl shadow overflow-hidden mb-8">

        <table class="w-full">

            <thead class="bg-navy-700">

                <tr>

                    <th class="p-4 text-left text-blue-200 font-semibold">

                        Producto

                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">

                        Cantidad

                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">

                        Precio

                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">

                        Subtotal

                    </th>

                    <th class="p-4 text-left text-blue-200 font-semibold">

                        Acción

                    </th>

                </tr>

            </thead>

            <tbody>

                <tr
                v-for="(item, index) in carrito"
                :key="index"
                class="border-t border-white/10 hover:bg-navy-700/50 transition-colors">

                    <td class="p-4 text-white">

                        {{ item.nombre }}

                    </td>

                    <td class="p-4 text-white">

                        {{ item.cantidad }}

                    </td>

                    <td class="p-4 text-white">

                        S/ {{ item.precio_compra }}

                    </td>

                    <td class="p-4 text-white">

                        S/ {{ item.subtotal }}

                    </td>

                    <td class="p-4">

                        <button

                            @click="eliminarItem(index)"

                            class="bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded transition-colors">

                            Eliminar

                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <!-- TOTALES -->

    <div
    class="bg-navy-800 border border-white/10 rounded-xl shadow p-6">

        <div class="space-y-3 text-lg">

            <div class="flex justify-between text-white/80">

                <span>Subtotal</span>

                <span>S/ {{ subtotal.toFixed(2) }}</span>

            </div>

            <div class="flex justify-between text-white/80">

                <span>IGV 18%</span>

                <span>S/ {{ igv.toFixed(2) }}</span>

            </div>

            <div
            class="flex justify-between text-2xl font-bold text-white border-t border-white/10 pt-3">

                <span>Total</span>

                <span>S/ {{ total.toFixed(2) }}</span>

            </div>

        </div>

        <button

            @click="registrarCompra"

            class="mt-6 w-full bg-green-600 hover:bg-green-500 text-white py-4 rounded-lg text-lg font-semibold transition-colors">

            Registrar Compra

        </button>

    </div>

</div>

</template>

<script setup>

import Swal from 'sweetalert2'

import {

    ref,

    computed,

    onMounted

} from 'vue'

import api from '../api/axios'

/*
|--------------------------------------------------------------------------
| VARIABLES
|--------------------------------------------------------------------------
*/

const proveedores = ref([])

const productos = ref([])

const carrito = ref([])

const proveedor_id = ref('')

const productoSeleccionado = ref(null)

const cantidad = ref(1)

const precio_compra = ref(0)

/*
|--------------------------------------------------------------------------
| LOAD DATA
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

const cargarProductos = async () => {

    try {

        const response = await api.get(
            '/productos'
        )

        productos.value = response.data.data
    }

    catch (error) {

        console.log(error)
    }
}

/*
|--------------------------------------------------------------------------
| AGREGAR
|--------------------------------------------------------------------------
*/

const agregarProducto = () => {

    if (

        !productoSeleccionado.value ||

        !cantidad.value ||

        !precio_compra.value

    ) {

        Swal.fire(

            'Error',

            'Complete los datos',

            'error'
        )

        return
    }

    const subtotal =
        cantidad.value *
        precio_compra.value

    carrito.value.push({

        producto_id:
            productoSeleccionado.value.id,

        nombre:
            productoSeleccionado.value.nombre,

        cantidad:
            cantidad.value,

        precio_compra:
            precio_compra.value,

        subtotal
    })

    productoSeleccionado.value = null

    cantidad.value = 1

    precio_compra.value = 0
}

/*
|--------------------------------------------------------------------------
| DELETE ITEM
|--------------------------------------------------------------------------
*/

const eliminarItem = (index) => {

    carrito.value.splice(index, 1)
}

/*
|--------------------------------------------------------------------------
| TOTALS
|--------------------------------------------------------------------------
*/

const subtotal = computed(() => {

    return carrito.value.reduce(

        (acc, item) =>

            acc + item.subtotal,

        0
    )
})

const igv = computed(() => {

    return subtotal.value * 0.18
})

const total = computed(() => {

    return subtotal.value + igv.value
})

/*
|--------------------------------------------------------------------------
| REGISTRAR
|--------------------------------------------------------------------------
*/

const registrarCompra = async () => {

    if (!proveedor_id.value) {

        Swal.fire(

            'Error',

            'Seleccione proveedor',

            'error'
        )

        return
    }

    if (!carrito.value.length) {

        Swal.fire(

            'Error',

            'Agregue productos',

            'error'
        )

        return
    }

    try {

        await api.post(

            '/compras',

            {

                proveedor_id:
                    proveedor_id.value,

                productos:
                    carrito.value
            }
        )

        Swal.fire(

            'Correcto',

            'Compra registrada',

            'success'
        )

        carrito.value = []

        proveedor_id.value = ''
    }

    catch (error) {

        console.log(error)

        Swal.fire(

            'Error',

            'No se pudo registrar',

            'error'
        )
    }
}

onMounted(() => {

    cargarProveedores()

    cargarProductos()
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