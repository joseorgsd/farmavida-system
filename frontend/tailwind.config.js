/** @type {import('tailwindcss').Config} */

export default {
    content: [
        "./index.html",
        "./src/**/*.{vue,js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {
            colors: {
                navy: {
                    950: '#060d1a',
                    900: '#0a1628',
                    800: '#0e1f3a',
                    700: '#142b50',
                    600: '#1a3566',
                },
                blue: {
                    500: '#1a5fb4',
                    400: '#2170d8',
                    300: '#4d8fe8',
                    200: '#8ab8f5',
                    100: '#c8dffb',
                },
            },
            fontFamily: {
                sans:    ['Inter', 'sans-serif'],
                display: ['Space Grotesk', 'sans-serif'],
            },
        },
    },
    plugins: [],
}