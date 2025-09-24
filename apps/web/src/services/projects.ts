import type { Project, PaginationMeta } from '../lib/types';
import { http } from './http';

export const projectsService = {
  async list(params?: Record<string, any>): Promise<{ data: Project[]; meta?: PaginationMeta }> {
    const res = await http.get<{ data: Project[]; meta?: PaginationMeta }>('/projects', { params });
    return res.data as any;
  },
  async show(slug: string): Promise<Project> {
    const res = await http.get<Project>(`/projects/${slug}`);
    return res.data;
  },
};
