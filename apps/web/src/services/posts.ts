import type { Post, PaginationMeta } from '../lib/types';
import { http } from './http';

export const postsService = {
  async list(params?: Record<string, any>): Promise<{ data: Post[]; meta?: PaginationMeta }> {
    const res = await http.get<{ data: Post[]; meta?: PaginationMeta }>('/posts', { params });
    return res.data as any;
  },
  async show(slug: string): Promise<Post> {
    const res = await http.get<Post>(`/posts/${slug}`);
    return res.data;
  },
};
