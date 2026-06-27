<template>
<div class="flex bg-gray-100">

    <!-- SIDEBAR: Solo se muestra si NO estamos en el login -->
    <Sidebar v-if="$route.path !== '/'" />

    <!-- CONTENT -->
    <div class="flex-1">

        <!-- NAVBAR: Solo se muestra si NO estamos en el login -->
        <Navbar v-if="$route.path !== '/'" />

        <!-- PAGE -->
        <div :class=" $route.path === '/' ? '' : 'p-8' ">
            <RouterView />
        </div>

    </div>

</div>
</template>

<script setup>
import Sidebar from '../components/Sidebar.vue'
import Navbar from '../components/Navbar.vue'
</script>
