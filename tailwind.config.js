module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Helpers/*.php",
    "./app/Http/Controllers/*.php",
    "./app/Datatable/*.php",
  ],
  purge: [
    './resources/views/**/*.blade.php',
    './resources/css/**/*.css',
    './node_modules/@themesberg/flowbite/**/*.js',
    "./app/Helpers/*.php",
    "./app/Http/Controllers/*.php",
    "./app/Datatable/*.php",
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
