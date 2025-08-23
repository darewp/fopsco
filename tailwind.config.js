/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./**/*.php",
    "./assets/js/**/*.js",
    "./assets/css/**/*.css",
    "./inc/**/*.php",
    "./template-parts/**/*.php",
  ],
  safelist: [
    'ring-orange',
  ],
  theme: {
    extend: {
      colors: {
        'orange': '#F85E00',
        'test-pink': '#ff00ff',
      },
    },
  },
  plugins: [],
}