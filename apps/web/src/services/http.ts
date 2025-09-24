import axios from 'axios';
import { env } from '../config/env';

function resolveBaseUrl(raw: string): string {
  const base = (raw || '').trim();
  if (!base) return '/api';
  // Si alguien configuró 'nginx' o 'http://nginx', forzamos proxy '/api'
  if (base === 'nginx' || base === 'http://nginx' || base === 'https://nginx') return '/api';
  // Si empieza con '/', ya es relativo y lo manejará Vite proxy
  if (base.startsWith('/')) return base;
  // Si tiene protocolo, úsalo tal cual
  if (/^https?:\/\//i.test(base)) return base;
  // Cualquier otro caso, cae al proxy
  return '/api';
}

export const http = axios.create({
  baseURL: resolveBaseUrl(env.apiBaseUrl),
  headers: {
    Accept: 'application/json',
  },
  withCredentials: false,
});

// Unwraper del backend estándar { status, message, data }
http.interceptors.response.use(
  (res) => {
    const payload = res.data;
    if (payload && typeof payload === 'object' && 'data' in payload) {
      if ('meta' in payload) {
        res.data = { data: payload.data ?? null, meta: payload.meta };
      } else {
        res.data = payload.data ?? null;
      }
    }
    return res;
  },
  (error) => Promise.reject(error)
);
