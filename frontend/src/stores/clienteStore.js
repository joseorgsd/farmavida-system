
import { defineStore } from 'pinia'

import api from '../services/api'

export const useClienteStore = defineStore(

    'clientes',

    {

        state: () => ({

            clientes: []
        }),

        actions: {

            /*
            |--------------------------------------------------------------------------
            | OBTENER
            |--------------------------------------------------------------------------
            */

            async obtenerClientes() {

                const response =

                    await api.get(

                        '/clientes'
                    )

                this.clientes =

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
