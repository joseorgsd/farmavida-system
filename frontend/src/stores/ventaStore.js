
import { defineStore } from 'pinia'

export const useVentaStore = defineStore(

    'ventas',

    {

        state: () => ({

            detalleVenta: []
        }),

        getters: {

            totalVenta: (state) => {

                return state.detalleVenta.reduce(

                    (total,item) =>

                        total + item.subtotal,

                    0
                )
            }
        },

        actions: {

            /*
            |--------------------------------------------------------------------------
            | AGREGAR
            |--------------------------------------------------------------------------
            */

            agregarProducto(producto) {

                this.detalleVenta.push(producto)
            },

            /*
            |--------------------------------------------------------------------------
            | ELIMINAR
            |--------------------------------------------------------------------------
            */

            eliminarProducto(index) {

                this.detalleVenta.splice(index,1)
            },

            /*
            |--------------------------------------------------------------------------
            | LIMPIAR
            |--------------------------------------------------------------------------
            */

            limpiarVenta() {

                this.detalleVenta = []
            }
        }
    }
)

