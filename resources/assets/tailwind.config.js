module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        customBlue: '#OC71C3',
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
