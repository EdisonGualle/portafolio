import { useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { img } from '../lib/media';
import { useProjectsStore } from '../store/projects';
import Markdown from '../components/Markdown';

export default function ProjectDetail() {
  const { slug } = useParams();
  const { current: project, loadingDetail: loading, errorDetail: error, show } = useProjectsStore();
  useEffect(() => { if (slug) show(slug); }, [slug]);
  const { t } = useTranslation();

  return (
    <main className="container">
      <p className="muted mt-6"><Link to="/projects">{t('backProjects')}</Link></p>
      {loading && <p className="muted">{t('loading')}</p>}
      {error && <p className="muted">{t('loadFail')}</p>}
      {project && (
        <motion.article initial={{ opacity: 0, y: 8 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: .4 }} className="card p-6 mt-4">
          {project.cover && (
            <img className="w-full rounded-xl mb-3 border border-white/10" src={img(project.cover)} alt={project.title} />
          )}
          <h1 className="text-3xl font-semibold mt-0">{project.title}</h1>
          {project.excerpt && <p className="muted">{project.excerpt}</p>}
          <div className="mt-3">
            <Markdown content={project.body} />
          </div>
          <div className="flex gap-3 mt-4">
            {project.repo_url && (
              <a className="btn" href={project.repo_url} target="_blank" rel="noreferrer noopener">{t('repo')}</a>
            )}
            {project.demo_url && (
              <a className="btn" href={project.demo_url} target="_blank" rel="noreferrer noopener">{t('demo')}</a>
            )}
          </div>
        </motion.article>
      )}
    </main>
  );
}
