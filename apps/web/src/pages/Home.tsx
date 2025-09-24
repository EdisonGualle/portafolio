import { useEffect, useMemo } from 'react';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import type { Profile, Social } from '../lib/types';
import { img } from '../lib/media';
import { Globe, Github, Linkedin, MapPin, Twitter } from 'lucide-react';
import { useProfileStore } from '../store/profile';

function getLabelFromUrl(url: string) {
  try { return new URL(url).hostname.replace('www.', ''); } catch { return 'link'; }
}

function normalizeSocials(socials: Profile['socials']): Social[] {
  if (!socials) return [];
  if (Array.isArray(socials)) {
    return socials.map((s: any) => ({
      label: s.label || s.platform || getLabelFromUrl(s.url),
      url: s.url,
      icon: s.icon,
    }));
  }
  return Object.entries(socials).map(([label, url]) => ({ label: label || getLabelFromUrl(String(url)), url: String(url) }));
}

function IconFor(url: string) {
  const u = url.toLowerCase();
  if (u.includes('github')) return <Github size={16} />;
  if (u.includes('linkedin')) return <Linkedin size={16} />;
  if (u.includes('x.com') || u.includes('twitter')) return <Twitter size={16} />;
  return <Globe size={16} />;
}

export default function Home() {
  const { profile, loading, error, fetch } = useProfileStore();
  const { t } = useTranslation();
  useEffect(() => { if (!profile && !loading) fetch(); }, []);

  const socials = useMemo(() => normalizeSocials(profile?.socials ?? null), [profile]);

  return (
    <main className="container">
      <motion.section initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: .5 }} className="grid grid-cols-1 md:grid-cols-[180px_1fr] gap-8 items-center my-16">
        <div className="flex justify-center">
          {profile?.photo_url ? (
            <img src={img(profile.photo_url)} alt={profile.name} width={180} height={180} className="avatar ring-gradient" />
          ) : (
            <div className="w-44 h-44 rounded-full bg-white/10 ring-gradient" />
          )}
        </div>
        <div>
          <h1 className="text-5xl md:text-6xl font-semibold tracking-tight mb-2 text-gradient">
            {profile?.name ?? 'Tu Nombre'}
          </h1>
          <p className="text-primary mb-2">{profile?.role ?? 'Tu Rol Principal'}</p>
          {profile?.location && <p className="muted mb-3 inline-flex items-center gap-1"><MapPin size={16} /> {profile.location}</p>}
          {profile?.bio ? (
            <p className="text-white/90">{profile.bio}</p>
          ) : (
            <p className="text-white/90">{t('welcomeFallback')}</p>
          )}

          {loading && <p className="muted mt-3">{t('loading')}</p>}
          {error && <p className="muted mt-3">{t('loadFail')}</p>}

          {!!socials.length && (
            <ul className="flex flex-wrap gap-2 mt-4">
              {socials.map((s) => (
                <motion.li key={s.label + s.url} whileHover={{ scale: 1.05 }} whileTap={{ scale: .98 }}>
                  <a className="btn" href={s.url} target="_blank" rel="noreferrer noopener" title={s.label}>
                    {IconFor(s.url)}
                    <span className="hidden sm:inline">{s.label}</span>
                  </a>
                </motion.li>
              ))}
            </ul>
          )}
        </div>
      </motion.section>

      <motion.section initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: .1, duration: .5 }} className="grid md:grid-cols-2 gap-6 mb-24">
        <div className="relative rounded-2xl p-[1px] bg-gradient-to-br from-cyan-400/20 via-fuchsia-400/10 to-transparent">
          <div className="rounded-2xl card p-6">
            <h2 className="text-lg font-semibold mb-1">{t('projects')}</h2>
            <p className="muted">{t('featuredSoon')}</p>
          </div>
        </div>
        <div className="relative rounded-2xl p-[1px] bg-gradient-to-br from-cyan-400/20 via-fuchsia-400/10 to-transparent">
          <div className="rounded-2xl card p-6">
            <h2 className="text-lg font-semibold mb-1">{t('blog')}</h2>
            <p className="muted">{t('postsSoon')}</p>
          </div>
        </div>
      </motion.section>
    </main>
  );
}
