import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: '#181d26',
                'primary-active': '#0d1218',
                ink: '#181d26',
                body: '#333840',
                muted: '#41454d',
                hairline: '#dddddd',
                'border-strong': '#9297a0',
                canvas: '#ffffff',
                surface: {
                    soft: '#f8fafc',
                    strong: '#e0e2e6',
                    dark: '#181d26',
                    'dark-elevated': '#1d1f25',
                },
                signature: {
                    coral: '#aa2d00',
                    forest: '#0a2e0e',
                    cream: '#f5e9d4',
                    peach: '#fcab79',
                    mint: '#a8d8c4',
                    yellow: '#f4d35e',
                    mustard: '#d9a441',
                },
                'on-primary': '#ffffff',
                'on-dark': '#ffffff',
                link: '#1b61c9',
                'link-active': '#1a3866',
                info: '#254fad',
                'info-border': '#458fff',
                success: '#006400',
                'success-border': '#39bf45',
                danger: '#aa2d00',
                'danger-border': '#d35a31',
                'pricing-ink': '#1d1f25',
            },
            fontFamily: {
                sans: ['Haas', 'Inter', ...defaultTheme.fontFamily.sans],
                display: ['Haas Groot Disp', 'Inter', ...defaultTheme.fontFamily.sans],
                pricing: ['Inter Display', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            spacing: {
                section: '96px',
            },
            borderRadius: {
                card: '10px',
                air: '12px',
                pill: '9999px',
            },
            boxShadow: {
                cta: '0 12px 24px -18px rgba(27, 97, 201, 0.6)',
                pop: '0 18px 36px -28px rgba(24, 29, 38, 0.55)',
            },
        },
    },

    plugins: [forms],
};
