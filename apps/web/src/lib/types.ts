export type Social = {
  label: string;
  url: string;
  icon?: string;
};

export type Profile = {
  name: string;
  role: string;
  bio: string;
  photo_url?: string | null;
  email?: string | null;
  phone?: string | null;
  location?: string | null;
  socials?: Social[] | Record<string, string> | null;
};

export type Project = {
  id: number;
  slug: string;
  title: string;
  excerpt?: string | null;
  cover?: string | null;
  body?: string | null;
  repo_url?: string | null;
  demo_url?: string | null;
};

export type Post = {
  id: number;
  slug: string;
  title: string;
  excerpt?: string | null;
  body?: string | null;
  published_at?: string | null;
};

export type PaginationMeta = {
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
};
