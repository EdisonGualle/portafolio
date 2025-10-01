import { useEffect, useMemo } from 'react';
import useLiquidacionesStore from '../stores/liquidacionesStore';

const normalize = (value) => value.toLowerCase();

const matchesSearch = (liquidacion, search) => {
    if (!search) {
        return true;
    }

    const query = normalize(search);
    return normalize(liquidacion.asociacion).includes(query);
};

const matchesEstado = (liquidacion, estado) => {
    if (estado === 'todas') {
        return true;
    }

    return liquidacion.estado === estado;
};

const useLiquidaciones = () => {
    const liquidaciones = useLiquidacionesStore((state) => state.liquidaciones);
    const fetchStatus = useLiquidacionesStore((state) => state.fetchStatus);
    const hydrateMock = useLiquidacionesStore((state) => state.hydrateMock);
    const filters = useLiquidacionesStore((state) => state.filters);
    const setFilters = useLiquidacionesStore((state) => state.setFilters);
    const resetFilters = useLiquidacionesStore((state) => state.resetFilters);
    const isModalOpen = useLiquidacionesStore((state) => state.isModalOpen);
    const openModal = useLiquidacionesStore((state) => state.openModal);
    const closeModal = useLiquidacionesStore((state) => state.closeModal);

    useEffect(() => {
        if (fetchStatus === 'idle') {
            hydrateMock();
        }
    }, [fetchStatus, hydrateMock]);

    const filteredLiquidaciones = useMemo(() => {
        return liquidaciones.filter(
            (liquidacion) =>
                matchesSearch(liquidacion, filters.search) &&
                matchesEstado(liquidacion, filters.estado),
        );
    }, [liquidaciones, filters]);

    return {
        liquidaciones: filteredLiquidaciones,
        fetchStatus,
        filters,
        setFilters,
        resetFilters,
        isModalOpen,
        openModal,
        closeModal,
    };
};

export default useLiquidaciones;
