import { create } from 'zustand';
import type { Project, PaginationMeta } from '../lib/types';
import { projectsService } from '../services/projects';

type ListState = {
  items: Project[];
  loading: boolean;
  error: string | null;
  page: number;
  meta?: PaginationMeta;
};

type DetailState = {
  current: Project | null;
  loadingDetail: boolean;
  errorDetail: string | null;
};

type Actions = {
  fetch: (params?: Record<string, any>) => Promise<void>;
  setPage: (page: number) => void;
  show: (slug: string) => Promise<void>;
  reset: () => void;
};

export const useProjectsStore = create<ListState & DetailState & Actions>((set) => ({
  items: [],
  loading: false,
  error: null,
  page: 1,
  current: null,
  loadingDetail: false,
  errorDetail: null,
  async fetch(params) {
    set((s) => ({ loading: true, error: null, page: params?.page ?? s.page }));
    try {
      const { data, meta } = await projectsService.list({ per_page: 6, page: params?.page });
      set({ items: data, meta, loading: false });
    } catch (e: any) {
      set({ error: e?.message ?? 'Error', loading: false });
    }
  },
  setPage(page) { set({ page }); },
  async show(slug: string) {
    set({ loadingDetail: true, errorDetail: null });
    try {
      const data = await projectsService.show(slug);
      set({ current: data, loadingDetail: false });
    } catch (e: any) {
      set({ errorDetail: e?.message ?? 'Error', loadingDetail: false });
    }
  },
  reset() {
    set({ items: [], loading: false, error: null, current: null, loadingDetail: false, errorDetail: null });
  },
}));
