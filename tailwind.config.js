/** @type {import('tailwindcss').Config} */
module.exports = {
    mode: 'jit',
    content: ["./**/*.{php,js}"],
    theme: {
      extend: {
        keyframes: {
          'bounce-right': {
            '0%, 100%': {
              transform: 'translateX(-25%)',
              animationTimingFunction: 'cubic-bezier(0.8, 0, 1, 1)',
            },
            '50%:': {
              transform: 'translateX(0)',
              animationTimingFunction: 'cubic-bezier(0, 0, 0.2, 1)',
            }
          },
          'swing': {
            '0%,100%' : { transform: 'rotate(15deg)' },
            '50%' : { transform: 'rotate(-15deg)' },
          }
        },
        animation: {
          'bounce-right': 'bounce-right 1s ease-in-out infinite',
          'swing': 'swing 1s infinite'
        },
      },
    },
    plugins: [],
  }