# Portafolio

Monorepo con:
- apps/api (Laravel + Filament)
- apps/web (Next.js)

## Levantar todo con Docker

1) Arranca la API + DB + Redis + Nginx (y la web):

   docker compose up -d nginx web postgres redis mailhog app

   - API disponible en: http://127.0.0.1:8085
   - Web Next.js en: http://127.0.0.1:3000

La app Next corre dentro de `web` y hace fetch a `http://nginx` (red interna Docker) usando `NEXT_PUBLIC_API_BASE_URL`.

## Levantar solo el frontend localmente (sin Docker)

1) Entra en `apps/web` y crea env local:

   cp .env.example .env.local

2) Instala dependencias y arranca dev:

   pnpm i  # o npm i / yarn
   pnpm dev  # http://localhost:3000

Si usas el frontend local, asegúrate de que la API esté en `http://127.0.0.1:8085` o ajusta `NEXT_PUBLIC_API_BASE_URL`.
