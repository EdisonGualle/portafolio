import { Link, NavLink, Outlet } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

export default function Layout() {
  const { t, i18n } = useTranslation();
  return (
    <div className="relative min-h-full grid grid-rows-[auto_1fr_auto]">
      <div className="pointer-events-none absolute inset-0 -z-10 bg-grid opacity-[.08]" />
      <div className="pointer-events-none absolute -z-10 inset-0 [mask-image:radial-gradient(600px_300px_at_20%_0%,black,transparent)] bg-gradient-to-b from-cyan-400/20 to-transparent" />
      <header className="sticky top-0 z-40 backdrop-blur bg-bg/70 border-b border-white/10">
        <div className="container flex items-center justify-between py-3">
          <Link to="/" className="text-white/90 font-bold tracking-widest">EGB</Link>
          <nav className="flex gap-1">
            <Nav to="/" end>{t('nav.home')}</Nav>
            <Nav to="/projects">{t('nav.projects')}</Nav>
            <Nav to="/blog">{t('nav.blog')}</Nav>
          </nav>
          <div className="flex items-center gap-2">
            <LangBtn label="ES" active={i18n.language.startsWith('es')} onClick={() => { i18n.changeLanguage('es'); localStorage.setItem('lang','es'); }} />
            <LangBtn label="EN" active={i18n.language.startsWith('en')} onClick={() => { i18n.changeLanguage('en'); localStorage.setItem('lang','en'); }} />
          </div>
        </div>
      </header>

      <Outlet />

      <footer className="border-t border-white/10 mt-10">
        <div className="container py-6">
          <p className="text-center text-muted">© {new Date().getFullYear()} Edison Gualle</p>
        </div>
      </footer>
    </div>
  );
}

function Nav({ to, end, children }: { to: string; end?: boolean; children: React.ReactNode }) {
  return (
    <NavLink
      to={to}
      end={end}
      className={({ isActive }) =>
        `px-3 py-2 rounded-lg border border-white/10 text-white/90 hover:bg-white/10 transition ${isActive ? 'bg-white/10' : ''}`
      }
    >
      {children}
    </NavLink>
  );
}

function LangBtn({ label, active, onClick }: { label: string; active?: boolean; onClick: () => void }) {
  return (
    <button onClick={onClick} className={`px-2.5 py-1 rounded-md text-sm border transition ${active ? 'bg-white/15 border-white/20 text-white' : 'bg-white/5 border-white/10 text-white/80 hover:bg-white/10'}`}>
      {label}
    </button>
  );
}
