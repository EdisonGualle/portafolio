import { Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
import Projects from './pages/Projects';
import Blog from './pages/Blog';
import Layout from './components/Layout';
import ProjectDetail from './pages/ProjectDetail';
import PostDetail from './pages/PostDetail';

function normalizeSocials(socials: Profile['socials']): Social[] {
  if (!socials) return [];
  if (Array.isArray(socials)) return socials;
  return Object.entries(socials).map(([label, url]) => ({ label, url }));
}

export default function App() {
  return (
    <Routes>
      <Route element={<Layout />}> 
        <Route index element={<Home />} />
        <Route path="projects" element={<Projects />} />
        <Route path="projects/:slug" element={<ProjectDetail />} />
        <Route path="blog" element={<Blog />} />
        <Route path="blog/:slug" element={<PostDetail />} />
      </Route>
    </Routes>
  );
}
