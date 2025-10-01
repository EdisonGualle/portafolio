import apiClient from './apiClient';
import mockLiquidaciones from '../features/liquidaciones/data/mockLiquidaciones';

export const fetchLiquidaciones = async ({ useMock = import.meta.env.DEV } = {}) => {
    if (useMock) {
        return mockLiquidaciones;
    }

    const { data } = await apiClient.get('/liquidaciones');
    return data;
};

export const createLiquidacion = async (payload, { useMock = import.meta.env.DEV } = {}) => {
    if (useMock) {
        return { ...payload, id: crypto.randomUUID(), estado: 'Pendiente' };
    }

    const { data } = await apiClient.post('/liquidaciones', payload);
    return data;
};
