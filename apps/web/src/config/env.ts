export const env = {
  apiBaseUrl: import.meta.env.VITE_API_BASE_URL || '/api',
  assetsBaseUrl:
    import.meta.env.VITE_ASSETS_BASE_URL || import.meta.env.VITE_API_BASE_URL || '',
};

