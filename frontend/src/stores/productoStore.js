
import { defineStore } from 'pinia'

import api from '../services/api'

export const useProductoStore = defineStore(

    'productos',

    {

        state: () => ({

            productos: []
        }),

        actions: {

            /*
            |--------------------------------------------------------------------------
            | OBTENER
            |--------------------------------------------------------------------------
            */

            async obtenerProductos() {

                const response =

                    await api.get(

                        '/productos'
                    )

                this.productos =

                    Array.isArray(

                        response.data.data
                    )

                    ?

                    response.data.data

                    :

                    response.data.data.data
            }
        }
    }
)
