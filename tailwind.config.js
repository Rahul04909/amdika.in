/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./includes/**/*.php",
    "./components/**/*.php",
    "./pages/**/*.php",
    "./user/**/*.php",
    "./admin/**/*.php"
  ],
  corePlugins: {
    preflight: false, // Prevent reset of Bootstrap 5 layout style templates
  },
  theme: {
    extend: {
      colors: {
        luxGold: '#C89B2C',
        darkLux: '#111827',
        accentGold: '#EAB308',
        borderLight: '#E5E7EB',
        textPrimary: '#1F2937',
      },
      fontFamily: {
        sans: ['Outfit', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
      },
      boxShadow: {
        luxury: '0 10px 30px -10px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.03)',
        luxuryHover: '0 20px 40px -15px rgba(200, 155, 44, 0.15), 0 1px 5px rgba(0, 0, 0, 0.05)',
      }
    }
  },
  plugins: [],
}
