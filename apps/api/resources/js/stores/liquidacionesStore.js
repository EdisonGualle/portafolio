import { create } from 'zustand';
import mockLiquidaciones from '../features/liquidaciones/data/mockLiquidaciones';

const initialFilters = {
    search: '',
    estado: 'todas',
};

const initialState = {
    liquidaciones: [],
    fetchStatus: 'idle',
    filters: { ...initialFilters },
    isModalOpen: false,
};

const useLiquidacionesStore = create((set) => ({
    ...initialState,
    hydrateMock: () =>
        set(() => ({
            liquidaciones: mockLiquidaciones,
            fetchStatus: 'success',
        })),
    openModal: () => set({ isModalOpen: true }),
    closeModal: () => set({ isModalOpen: false }),
    setFilters: (filters) =>
        set((state) => ({
            filters: {
                ...state.filters,
                ...filters,
            },
        })),
    resetFilters: () => set({ filters: { ...initialFilters } }),
}));

export default useLiquidacionesStore;
