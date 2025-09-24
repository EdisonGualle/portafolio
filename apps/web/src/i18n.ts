import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

const resources = {
  es: {
    common: {
      nav: { home: 'Inicio', projects: 'Proyectos', blog: 'Blog' },
      backProjects: '← Volver a proyectos',
      backBlog: '← Volver al blog',
      loading: 'Cargando…',
      loadFail: 'No pude cargar los datos.',
      projects: 'Proyectos',
      blog: 'Blog',
      featuredSoon: 'Muy pronto aquí verás mis proyectos destacados.',
      postsSoon: 'Muy pronto aquí verás mis últimos artículos.',
      welcomeFallback: 'Bienvenido a mi portafolio. Próximamente más detalles.',
      repo: 'Repositorio',
      demo: 'Demo',
    },
  },
  en: {
    common: {
      nav: { home: 'Home', projects: 'Projects', blog: 'Blog' },
      backProjects: '← Back to projects',
      backBlog: '← Back to blog',
      loading: 'Loading…',
      loadFail: "Couldn't load data.",
      projects: 'Projects',
      blog: 'Blog',
      featuredSoon: 'Featured projects coming soon.',
      postsSoon: 'Latest articles coming soon.',
      welcomeFallback: 'Welcome to my portfolio. More details soon.',
      repo: 'Repository',
      demo: 'Demo',
    },
  },
};

const saved = (typeof window !== 'undefined' && localStorage.getItem('lang')) || undefined;
const fallback = (typeof navigator !== 'undefined' && navigator.language.startsWith('es')) ? 'es' : 'en';

i18n
  .use(initReactI18next)
  .init({
    resources,
    lng: saved || fallback,
    fallbackLng: 'en',
    interpolation: { escapeValue: false },
    defaultNS: 'common',
  });

export default i18n;

