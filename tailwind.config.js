module.exports = {
  purge: [
    './resources/views/**/*.blade.php',
    './resources/css/**/*.css',
    './node_modules/@themesberg/flowbite/**/*.js',
  ],
  theme: {
    extend: {}
  },
  variants: {},
  plugins: [
    require('@tailwindcss/ui'),
    require('@themesberg/flowbite/plugin'),
  ]
}
