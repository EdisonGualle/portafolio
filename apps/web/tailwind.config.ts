import type { Config } from 'tailwindcss'

export default {
  darkMode: ['class'],
  content: [
    './index.html',
    './src/**/*.{ts,tsx,js,jsx}',
  ],
  theme: {
    container: {
      center: true,
      padding: '1rem',
      screens: { '2xl': '1280px' },
    },
    extend: {
      colors: {
        bg: '#0b0c10',
        card: '#121318',
        muted: '#a3adba',
        primary: '#4fc3f7',
      },
      backgroundImage: {
        'radial-sky': 'radial-gradient(1000px 600px at 10% -20%, rgba(79,195,247,0.12), transparent)',
      },
      borderRadius: {
        xl: '0.75rem',
      },
      boxShadow: {
        glass: '0 1px 0 0 rgba(255,255,255,0.06) inset, 0 8px 24px rgba(0,0,0,0.35)',
      },
    },
  },
  plugins: [require('tailwindcss-animate'), require('@tailwindcss/typography')],
} satisfies Config
