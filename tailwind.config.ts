
import type { Config } from "tailwindcss";

export default {
  darkMode: "class",
  content: [
    "./pages/**/*.{ts,tsx}",
    "./components/**/*.{ts,tsx}",
    "./app/**/*.{ts,tsx}",
    "./src/**/*.{ts,tsx}"
  ],
  theme: {
    container: {
      center: true,
      padding: '2rem',
      screens: {
        '2xl': '1400px'
      }
    },
    extend: {
      colors: {
        primary: "#FFD600",
        background: "#111111",
        surface: "#181825",
        card: "#1e1e2f",
        border: "#333333",
        muted: "#666666",
        "brand-yellow": "#FFD600",
        "dark-bg": "#111111",
        "dark-surface": "#181825",
        "dark-card": "#1e1e2f"
      },
      fontFamily: {
        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
        'serif': ['Lora', 'serif'],
      },
      borderRadius: {
        lg: '1rem',
        md: '0.75rem',
        sm: '0.5rem'
      },
    }
  },
  plugins: [require("tailwindcss-animate")],
} satisfies Config;
