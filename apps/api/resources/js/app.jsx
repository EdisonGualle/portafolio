import React from 'react';
import { createRoot } from 'react-dom/client';
import './bootstrap';
import LiquidacionesPage from './features/liquidaciones/pages/LiquidacionesPage';

const container = document.getElementById('app-root');

if (container) {
    createRoot(container).render(
        <React.StrictMode>
            <LiquidacionesPage />
        </React.StrictMode>,
    );
}
