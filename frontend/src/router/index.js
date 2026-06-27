import {

    createRouter,

    createWebHistory

} from 'vue-router'

/*
|--------------------------------------------------------------------------
| LAYOUT
|--------------------------------------------------------------------------
*/

import AppLayout from '../layouts/AppLayout.vue'

/*
|--------------------------------------------------------------------------
| PAGES
|--------------------------------------------------------------------------
*/

import LoginPage from '../pages/LoginPage.vue'

import DashboardPage from '../pages/DashboardPage.vue'

import ProductosPage from '../pages/ProductosPage.vue'

import ClientesPage from '../pages/ClientesPage.vue'

import ProveedoresPage from '../pages/ProveedoresPage.vue'

import ComprasPage from '../pages/ComprasPage.vue'

import VentasPage from '../pages/VentasPage.vue'

import UsuariosPage from '../pages/UsuariosPage.vue'

import ValidacionesRecetaPage from '../pages/ValidacionesRecetaPage.vue'

import LandingPage from '../pages/LandingPage.vue'
/*
|--------------------------------------------------------------------------
| ROUTES
|--------------------------------------------------------------------------
*/

const routes = [
    
    {

        path: '/',

        component: LandingPage
    },
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    {

        path: '/login',

        component: LoginPage
    },

    /*
    |--------------------------------------------------------------------------
    | APP LAYOUT
    |--------------------------------------------------------------------------
    */

    {

        path: '/',

        component: AppLayout,

        meta: {

            requiresAuth: true
        },

        children: [

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            {

                path: 'dashboard',

                component: DashboardPage,

                meta: {

                    roles: [

                        'ADMIN',

                        'ALMACEN',

                        'CAJERO',

                        'QUIMICO'
                    ]
                }
            },

            /*
            |--------------------------------------------------------------------------
            | PRODUCTOS
            |--------------------------------------------------------------------------
            */

            {

                path: 'productos',

                component: ProductosPage,

                meta: {

                    roles: [

                        'ADMIN',

                        'ALMACEN'
                    ]
                }
            },

            /*
            |--------------------------------------------------------------------------
            | CLIENTES
            |--------------------------------------------------------------------------
            */

            {

                path: 'clientes',

                component: ClientesPage,

                meta: {

                    roles: [

                        'ADMIN',

                        'CAJERO'
                    ]
                }
            },

            /*
            |--------------------------------------------------------------------------
            | PROVEEDORES
            |--------------------------------------------------------------------------
            */

            {

                path: 'proveedores',

                component: ProveedoresPage,

                meta: {

                    roles: [

                        'ADMIN',

                        'ALMACEN'
                    ]
                }
            },

            /*
            |--------------------------------------------------------------------------
            | COMPRAS
            |--------------------------------------------------------------------------
            */

            {

                path: 'compras',

                component: ComprasPage,

                meta: {

                    roles: [

                        'ADMIN',

                        'ALMACEN'
                    ]
                }
            },

            /*
            |--------------------------------------------------------------------------
            | VENTAS
            |--------------------------------------------------------------------------
            */

            {

                path: 'ventas',

                component: VentasPage,

                meta: {

                    roles: [

                        'ADMIN',

                        'CAJERO'
                    ]
                }
            },

            /*
            |--------------------------------------------------------------------------
            | USUARIOS
            |--------------------------------------------------------------------------
            */

            {

                path: 'usuarios',

                component: UsuariosPage,

                meta: {

                    roles: [

                        'ADMIN'
                    ]
                }
            },

            /*
            |--------------------------------------------------------------------------
            | VALIDACIONES
            |--------------------------------------------------------------------------
            */

            {

                path: 'validaciones-receta',

                component: ValidacionesRecetaPage,

                meta: {

                    roles: [

                        'ADMIN',

                        'QUIMICO'
                    ]
                }
            }
        ]
    },

    /*
    |--------------------------------------------------------------------------
    | DEFAULT
    |--------------------------------------------------------------------------
    */

    {

        path: '/:pathMatch(.*)*',

        redirect: '/'
    }
]

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/

const router = createRouter({

    history: createWebHistory(),

    routes
})

/*
|--------------------------------------------------------------------------
| GUARD
|--------------------------------------------------------------------------
*/

router.beforeEach((to, from, next) => {

    const token = localStorage.getItem(

        'token'
    )

    const user = JSON.parse(

        localStorage.getItem('user')
    )

    const rol = user?.rol

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    if (

        to.meta.requiresAuth

        &&

        !token
    ) {

        return next('/login')
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    if (

        to.path === '/login'

        &&

        token
    ) {

        return next('/dashboard')
    }

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */

    if (

        to.meta.roles

        &&

        !to.meta.roles.includes(rol)
    ) {

        return next('/dashboard')
    }

    next()
})

export default router
