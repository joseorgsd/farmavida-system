<template>

<div class="sidebar">

    <!-- ===================================================== -->
    <!-- LOGO -->
    <!-- ===================================================== -->

    <div class="sidebar-logo">

        <span class="logo-icon"></span>

        <h3 class="logo-text">

            FARMAVIDA
        </h3>
    </div>

    <!-- ===================================================== -->
    <!-- ADMIN -->
    <!-- ===================================================== -->

    <template v-if="rol === 'ADMIN'">

        <div class="sidebar-section">

            <span class="sidebar-section-label">General</span>

            <SidebarLink
                to="/dashboard"
                label="Dashboard"
            />

            <SidebarLink
                to="/productos"
                label="Productos"
            />

            <SidebarLink
                to="/clientes"
                label="Clientes"
            />
        </div>

        <div class="sidebar-section">

            <span class="sidebar-section-label">Operaciones</span>

            <SidebarLink
                to="/proveedores"
                label="Proveedores"
            />

            <SidebarLink
                to="/compras"
                label="Compras"
            />

            <SidebarLink
                to="/ventas"
                label="Ventas"
            />
        </div>

        <div class="sidebar-section">

            <span class="sidebar-section-label">Administración</span>

            <SidebarLink
                to="/usuarios"
                label="Usuarios"
            />

            <SidebarLink
                to="/validaciones-receta"
                label="Validación Receta"
            />
        </div>
    </template>

    <!-- ===================================================== -->
    <!-- ALMACEN -->
    <!-- ===================================================== -->

    <template v-else-if="rol === 'ALMACEN'">

        <div class="sidebar-section">

            <span class="sidebar-section-label">Almacén</span>

            <SidebarLink
                to="/dashboard"
                label="Dashboard"
            />

            <SidebarLink
                to="/productos"
                label="Productos"
            />

            <SidebarLink
                to="/proveedores"
                label="Proveedores"
            />

            <SidebarLink
                to="/compras"
                label="Compras"
            />
        </div>
    </template>

    <!-- ===================================================== -->
    <!-- CAJERO -->
    <!-- ===================================================== -->

    <template v-else-if="rol === 'CAJERO'">

        <div class="sidebar-section">

            <span class="sidebar-section-label">Caja</span>

            <SidebarLink
                to="/dashboard"
                label="Dashboard"
            />

            <SidebarLink
                to="/clientes"
                label="Clientes"
            />

            <SidebarLink
                to="/ventas"
                label="Ventas"
            />
        </div>
    </template>

    <!-- ===================================================== -->
    <!-- QUIMICO -->
    <!-- ===================================================== -->

    <template v-else-if="rol === 'QUIMICO'">

        <div class="sidebar-section">

            <span class="sidebar-section-label">Químico</span>

            <SidebarLink
                to="/dashboard"
                label="Dashboard"
            />

            <SidebarLink
                to="/validaciones-receta"
                label="Validación Receta"
            />
        </div>
    </template>

    <!-- ===================================================== -->
    <!-- LOGOUT -->
    <!-- ===================================================== -->

    <div class="sidebar-footer">

        <button

            class="sidebar-logout"

            @click="logout"
        >

            Cerrar Sesión
        </button>
    </div>
</div>
</template>

<script setup>

import {

    computed

} from 'vue'

import {

    useRouter

} from 'vue-router'

import SidebarLink from './SidebarLink.vue'

const router = useRouter()

/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/

const user = JSON.parse(

    localStorage.getItem('user')
)

/*
|--------------------------------------------------------------------------
| ROL
|--------------------------------------------------------------------------
*/

const rol = computed(() => {

    return user?.rol || ''
})

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

const logout = () => {

    localStorage.removeItem('token')

    localStorage.removeItem('user')

    router.push('/login')
}

</script>

<style scoped>

/* ===================================================== */
/* SIDEBAR — contenedor                                   */
/* ===================================================== */

.sidebar {

    width: 250px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;

    background:
        radial-gradient(ellipse 80% 40% at 50% 0%, rgba(26,95,180,0.22) 0%, transparent 70%),
        var(--navy-950);

    border-right: 1px solid var(--white-10);
    padding: 24px 16px;
    box-sizing: border-box;
}

/* ===================================================== */
/* LOGO                                                   */
/* ===================================================== */

.sidebar-logo {

    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 8px 24px;
    border-bottom: 1px solid var(--white-10);
    margin-bottom: 20px;
}

.logo-icon {

    font-size: 26px;
    line-height: 1;
}

.logo-text {

    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.2rem;
    font-weight: 700;
    letter-spacing: -0.4px;
    color: var(--white);
    margin: 0;
}

/* ===================================================== */
/* SECCIONES                                              */
/* ===================================================== */

.sidebar-section {

    margin-bottom: 8px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--white-05);
}

.sidebar-section:last-of-type {

    border-bottom: none;
}

.sidebar-section-label {

    display: block;
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--blue-300);
    padding: 8px 10px 4px;
    opacity: 0.8;
}

/* ===================================================== */
/* FOOTER / LOGOUT                                        */
/* ===================================================== */

.sidebar-footer {

    margin-top: auto;
    padding-top: 16px;
    border-top: 1px solid var(--white-10);
}

.sidebar-logout {

    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid rgba(220, 53, 69, 0.35);
    background: rgba(220, 53, 69, 0.10);
    color: #f5a3ab;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: 0.2s;
}

.sidebar-logout:hover {

    background: rgba(220, 53, 69, 0.22);
    color: #fff;
    border-color: rgba(220, 53, 69, 0.6);
}

</style>