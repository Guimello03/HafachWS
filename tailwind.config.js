import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php', // Já cobre tudo dentro de views/
  './resources/views/students/**/*.blade.php', // (Opcional, mas pode reforçar)
  './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  './vendor/laravel/jetstream/**/*.blade.php',
  './storage/framework/views/*.php',
  './node_modules/flowbite/**/*.js',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'system-ui', 'Avenir', 'Helvetica', 'Arial', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [
    forms,
    typography,
    aspectRatio,
    require('flowbite/plugin') // ✅ ESSA LINHA É A CHAVE
  ],
};