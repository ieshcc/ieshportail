const colors = require('tailwindcss/colors')
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {

    },
  variants: {
    extend: {},
  },
  plugins: [],
}
