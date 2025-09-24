import { useEffect } from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { usePostsStore } from '../store/posts';
import Pagination from '../components/Pagination';

export default function Blog() {
  const { items, loading, error, fetch, page, meta, setPage } = usePostsStore();
  const { t } = useTranslation();
  useEffect(() => { fetch({ page }); }, [page]);

  return (
    <main className="container">
      <h1 className="text-3xl font-semibold tracking-tight mt-10">{t('blog')}</h1>
      {loading && <p className="muted mt-2">{t('loading')}</p>}
      {error && <p className="muted mt-2">{t('loadFail')}</p>}
      <motion.div initial="hidden" animate="show" variants={{ hidden: { opacity: 0 }, show: { opacity: 1, transition: { staggerChildren: 0.05 } } }} className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 my-6">
        {(items.length ? items : demo).map((p) => (
          <motion.article key={p.slug} className="relative rounded-2xl p-[1px] bg-gradient-to-br from-cyan-400/20 via-fuchsia-400/10 to-transparent" variants={{ hidden: { opacity: 0, y: 8 }, show: { opacity: 1, y: 0 } }} whileHover={{ y: -2 }}>
            <Link to={`/blog/${p.slug}`} className="block no-underline text-inherit">
              <div className="rounded-2xl card p-4">
                <h3 className="font-semibold mb-1 hover:text-primary transition-colors">{p.title}</h3>
                <p className="muted text-sm">{p.excerpt ?? ''}</p>
              </div>
            </Link>
          </motion.article>
        ))}
      </motion.div>
      <Pagination page={page} lastPage={meta?.last_page ?? 1} onChange={setPage} />
    </main>
  );
}

const demo = [
  { id: 1, slug: 'introduccion', title: 'Introducción', excerpt: 'Primer post del blog', published_at: null },
  { id: 2, slug: 'tips', title: 'Tips rápidos', excerpt: 'Notas y atajos', published_at: null },
];
