import { useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { usePostsStore } from '../store/posts';
import Markdown from '../components/Markdown';

export default function PostDetail() {
  const { slug } = useParams();
  const { current: post, loadingDetail: loading, errorDetail: error, show } = usePostsStore();
  useEffect(() => { if (slug) show(slug); }, [slug]);
  const { t } = useTranslation();

  return (
    <main className="container">
      <p className="muted mt-6"><Link to="/blog">{t('backBlog')}</Link></p>
      {loading && <p className="muted">{t('loading')}</p>}
      {error && <p className="muted">{t('loadFail')}</p>}
      {post && (
        <motion.article initial={{ opacity: 0, y: 8 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: .4 }} className="card p-6 mt-4">
          <h1 className="text-3xl font-semibold mt-0">{post.title}</h1>
          {post.excerpt && <p className="muted">{post.excerpt}</p>}
          <div className="mt-3">
            <Markdown content={post.body} />
          </div>
        </motion.article>
      )}
    </main>
  );
}
