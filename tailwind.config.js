/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['*.{php,html}'],
  theme: {
    screens: {
      sm_s: '320px',
      sm_m: '375px',
      sm_l: '425px',
      tablet: '768px',
      lg_s: '960px',
      lg: '1024px',
      xl: '1280px',
      '2xl': '1440px',
      '3xl': '1600px',
    },
  },
};
