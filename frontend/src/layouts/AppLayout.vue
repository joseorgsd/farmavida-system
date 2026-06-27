<template>

<div class="d-flex">

    <!-- ===================================================== -->
    <!-- SIDEBAR -->
    <!-- ===================================================== -->

    <Sidebar />

    <!-- ===================================================== -->
    <!-- CONTENT -->
    <!-- ===================================================== -->

    <div
        class="flex-grow-1 p-4"
        style="
            background: #f5f6fa;
            min-height: 100vh;
        "
    >

        <router-view />
    </div>
</div>
</template>

<script setup>

import Sidebar from '../components/Sidebar.vue'

</script>
