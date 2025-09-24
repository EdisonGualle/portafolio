const ASSETS_BASE = import.meta.env.VITE_ASSETS_BASE_URL || import.meta.env.VITE_API_BASE_URL || '';

export function img(path?: string | null): string {
  if (!path) return '';
  if (/^https?:\/\//i.test(path)) return path;
  const clean = path.replace(/^\/+/, '');
  // Si el backend expone archivos en /storage, intenta construir la URL completa.
  // Si VITE_ASSETS_BASE_URL se define como http://127.0.0.1:8085, devolverá http://127.0.0.1:8085/storage/<path>
  const base = ASSETS_BASE.replace(/\/$/, '');
  const maybeStorage = base.includes('://') ? `${base}/storage/${clean}` : `${base}/storage/${clean}`;
  return maybeStorage;
}

