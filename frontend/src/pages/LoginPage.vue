<template>

<div class="fv-login-page">

    <div class="fv-login-shell shadow-lg">

        <!-- ===================================================== -->
        <!-- PANEL IZQUIERDO — INFORMATIVO -->
        <!-- ===================================================== -->

        <div class="fv-login-info d-none d-md-flex flex-column justify-content-between">

            <div>

                <div class="fv-login-brand">

                    <div class="fv-login-logo">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>

                    <div>

                        <h1 class="fv-login-brand-name">FARMAVIDA</h1>

                        <p class="fv-login-brand-sub">Sistema de Gestión Farmacéutica</p>
                    </div>
                </div>

                <h2 class="fv-login-headline">Confianza · Salud · Tecnología</h2>

                <p class="fv-login-desc">
                    Plataforma integral para la gestión segura de farmacias. Control
                    de inventario, ventas, recetas controladas y facturación
                    electrónica.
                </p>

                <div class="fv-login-features">

                    <div class="fv-login-feature">

                        <div class="fv-login-feature-icon">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>

                        <div>

                            <p class="fv-login-feature-title">Control de inventario en tiempo real</p>

                            <p class="fv-login-feature-desc">Stock actualizado con cada transacción</p>
                        </div>
                    </div>

                    <div class="fv-login-feature">

                        <div class="fv-login-feature-icon">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>

                        <div>

                            <p class="fv-login-feature-title">Validación de recetas controladas</p>

                            <p class="fv-login-feature-desc">Autorización del Químico Farmacéutico</p>
                        </div>
                    </div>

                    <div class="fv-login-feature">

                        <div class="fv-login-feature-icon">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>

                        <div>

                            <p class="fv-login-feature-title">Acceso diferenciado por rol</p>

                            <p class="fv-login-feature-desc">Administrador, Químico y Cajero</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fv-login-info-footer">

                <p>© 2026 FARMAVIDA — Grupo 3 · Desarrollo de Aplicaciones en Internet · Tecsup</p>
            </div>
        </div>

        <!-- ===================================================== -->
        <!-- PANEL DERECHO — FORMULARIO -->
        <!-- ===================================================== -->

        <div class="fv-login-form-panel">

            <div class="fv-login-form-header">

                <h2>Iniciar sesión</h2>

                <p>Ingresa tus credenciales para acceder al sistema</p>
            </div>

            <!-- ALERT -->

            <div
                v-if="error"
                class="fv-login-alert"
            >

                {{ error }}
            </div>

            <!-- FORM -->

            <form @submit.prevent="login" class="fv-login-form">

                <div class="fv-login-field">

                    <label>Correo electrónico</label>

                    <input
                        v-model="form.email"
                        type="email"
                        required
                        autofocus
                        placeholder="usuario@farmavida.com"
                    >
                </div>

                <div class="fv-login-field">

                    <label>Contraseña</label>

                    <input
                        v-model="form.password"
                        type="password"
                        required
                        placeholder="••••••••"
                    >
                </div>

                <button
                    class="fv-login-submit"
                    :disabled="loading"
                >

                    <span v-if="loading">Ingresando...</span>

                    <span v-else>Ingresar al sistema</span>
                </button>
            </form>

        </div>
    </div>
</div>
</template>

<script setup>

import {

    reactive,

    ref

} from 'vue'

import {

    useRouter

} from 'vue-router'

import api from '../services/api'

const router = useRouter()

/*
|--------------------------------------------------------------------------
| FORM
|--------------------------------------------------------------------------
*/

const form = reactive({

    email: '',

    password: ''
})

/*
|--------------------------------------------------------------------------
| STATES
|--------------------------------------------------------------------------
*/

const loading = ref(false)

const error = ref('')

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

const login = async () => {

    loading.value = true

    error.value = ''

    try {

        const response = await api.post(

            '/login',

            form
        )

        /*
        |--------------------------------------------------------------------------
        | SAVE TOKEN
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(

            'token',

            response.data.token
        )

        localStorage.setItem(

            'user',

            JSON.stringify(
                response.data.user
            )
        )

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        router.push('/dashboard')
    }

    catch (err) {

        error.value =

            err.response?.data?.message

            ||

            'Error al iniciar sesión'
    }

    finally {

        loading.value = false
    }
}

</script>

<style scoped>

/* ===================================================== */
/* PALETA FARMAVIDA (igual al login.blade.php) */
/* ===================================================== */

.fv-login-page {

    --fv-primary: #0f4c75;

    --fv-dark: #0a3152;

    --fv-accent: #16a085;

    --teal-50: #f0fdfa;

    --teal-400: #2dd4bf;

    --teal-500-20: rgba(20, 184, 166, 0.2);

    --teal-600: #0d9488;

    --teal-700: #0f766e;

    --purple-50: #faf5ff;

    --purple-700: #7e22ce;

    --blue-50: #eff6ff;

    --blue-300: #93c5fd;

    --blue-500: #3b82f6;

    --blue-700: #1d4ed8;

    --gray-100: #f3f4f6;

    --gray-300: #d1d5db;

    --gray-400: #9ca3af;

    --gray-500: #6b7280;

    --gray-700: #374151;

    --gray-800: #1f2937;

    --red-50: #fef2f2;

    --red-200: #fecaca;

    --red-700: #b91c1c;

    font-family: -apple-system, 'Inter', sans-serif;
}

/* ===================================================== */
/* PAGE BACKGROUND */
/* ===================================================== */

.fv-login-page {

    min-height: 100vh;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 16px;

    background: linear-gradient(
        135deg,
        var(--fv-dark),
        var(--fv-primary),
        var(--teal-600)
    );
}

/* ===================================================== */
/* SHELL */
/* ===================================================== */

.fv-login-shell {

    width: 100%;

    max-width: 960px;

    display: flex;

    border-radius: 18px;

    overflow: hidden;
}

/* ===================================================== */
/* PANEL IZQUIERDO */
/* ===================================================== */

.fv-login-info {

    width: 50%;

    background: var(--fv-dark);

    padding: 40px;

    color: white;
}

.fv-login-brand {

    display: flex;

    align-items: center;

    gap: 12px;

    margin-bottom: 40px;
}

.fv-login-logo {

    width: 40px;

    height: 40px;

    flex-shrink: 0;

    background: white;

    border-radius: 12px;

    display: flex;

    align-items: center;

    justify-content: center;
}

.fv-login-logo svg {

    width: 24px;

    height: 24px;

    color: var(--fv-primary);
}

.fv-login-brand-name {

    font-size: 20px;

    font-weight: 700;

    margin: 0;
}

.fv-login-brand-sub {

    color: var(--blue-300);

    font-size: 12px;

    margin: 0;
}

.fv-login-headline {

    font-size: 24px;

    font-weight: 700;

    line-height: 1.25;

    margin-bottom: 12px;
}

.fv-login-desc {

    color: var(--blue-300);

    opacity: 0.95;

    font-size: 14px;

    line-height: 1.65;

    margin-bottom: 32px;
}

.fv-login-features {

    display: flex;

    flex-direction: column;

    gap: 16px;
}

.fv-login-feature {

    display: flex;

    align-items: flex-start;

    gap: 12px;
}

.fv-login-feature-icon {

    width: 28px;

    height: 28px;

    flex-shrink: 0;

    margin-top: 2px;

    border-radius: 8px;

    background: var(--teal-500-20);

    display: flex;

    align-items: center;

    justify-content: center;
}

.fv-login-feature-icon svg {

    width: 16px;

    height: 16px;

    color: var(--teal-400);
}

.fv-login-feature-title {

    font-size: 14px;

    font-weight: 600;

    margin: 0;
}

.fv-login-feature-desc {

    color: var(--blue-300);

    font-size: 12px;

    margin: 0;
}

.fv-login-info-footer {

    border-top: 1px solid rgba(255,255,255,0.1);

    padding-top: 24px;
}

.fv-login-info-footer p {

    color: var(--blue-300);

    font-size: 12px;

    margin: 0;
}

/* ===================================================== */
/* PANEL DERECHO — FORMULARIO */
/* ===================================================== */

.fv-login-form-panel {

    width: 100%;

    background: white;

    padding: 40px;

    display: flex;

    flex-direction: column;

    justify-content: center;
}

.fv-login-form-header h2 {

    font-size: 24px;

    font-weight: 700;

    color: var(--gray-800);

    margin-bottom: 4px;
}

.fv-login-form-header p {

    color: var(--gray-500);

    font-size: 14px;

    margin-bottom: 28px;
}

/* ===================================================== */
/* ALERT */
/* ===================================================== */

.fv-login-alert {

    background: var(--red-50);

    border: 1px solid var(--red-200);

    border-radius: 10px;

    padding: 12px 16px;

    font-size: 14px;

    color: var(--red-700);

    margin-bottom: 20px;
}

/* ===================================================== */
/* FORM */
/* ===================================================== */

.fv-login-form {

    display: flex;

    flex-direction: column;

    gap: 20px;
}

.fv-login-field label {

    display: block;

    font-size: 14px;

    font-weight: 500;

    color: var(--gray-700);

    margin-bottom: 6px;
}

.fv-login-field input {

    width: 100%;

    border: 1px solid var(--gray-300);

    border-radius: 10px;

    padding: 10px 16px;

    font-size: 14px;
    color: var(--gray-800);
    transition: 0.2s;
}

.fv-login-field input::placeholder {

    color: var(--gray-400);
}

.fv-login-field input:focus {

    outline: none;

    border-color: blue;

    box-shadow: 0 0 0 2px var(--blue-500);
}

.fv-login-submit {

    width: 100%;

    background: var(--fv-primary);

    color: white;

    font-weight: 600;

    font-size: 14px;

    padding: 10px;

    border: none;

    border-radius: 10px;

    margin-top: 4px;

    transition: background-color 0.2s;
}

.fv-login-submit:hover {

    background: var(--fv-dark);
}

.fv-login-submit:disabled {

    opacity: 0.7;
}

/* ===================================================== */
/* RESPONSIVE */
/* ===================================================== */

@media (max-width: 767px) {

    .fv-login-info {

        display: none;
    }
}

</style>