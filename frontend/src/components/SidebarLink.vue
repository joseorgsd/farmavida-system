<template>

<router-link

    :to="to"

    class="
        btn
        btn-dark
        text-start
        mb-2
        w-100
    "
>

    {{ label }}

</router-link>

</template>

<script setup>

defineProps({

    to: String,

    label: String
})

</script>
