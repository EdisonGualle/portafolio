# Web (React 19 + Vite)

Frontend del portafolio con React 19 + Vite.

## Desarrollo

1. Crear `.env.local` desde el ejemplo:
   
   cp .env.example .env.local

   Ajusta `NEXT_PUBLIC_API_BASE_URL` si tu API corre en otro host/puerto.

2. Instalar dependencias e iniciar:
   
   pnpm i  # o npm i / yarn
   pnpm dev  # http://localhost:5173

> La Home hace CSR (fetch en el cliente) a `GET /api/profile` del API Laravel.

## Notas de integración

- Como es CSR, si sirves la web fuera de Docker necesitarás CORS en Laravel o apuntar `VITE_API_BASE_URL` a un host accesible.

## Próximos pasos sugeridos

- Exponer endpoints públicos en Laravel para proyectos, posts y tags (e.g. `/api/projects`, `/api/posts`).
- Añadir páginas `/projects` y `/blog` consumiendo esos endpoints con paginación.
- Incluir metadatos SEO y OG por página, y sitemap/robots.
- Añadir un formulario de contacto que pegue a `/api/leads`.
