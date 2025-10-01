import useLiquidaciones from '@/hooks/useLiquidaciones';
import LiquidacionesTable from '../components/LiquidacionesTable';
import NuevaLiquidacionModal from '../components/NuevaLiquidacionModal';

const estadoOptions = ['todas', 'Pendiente', 'En Revisión', 'Aprobada', 'Rechazada'];

const LiquidacionesPage = () => {
    const { liquidaciones, filters, setFilters, resetFilters, openModal, closeModal, isModalOpen } = useLiquidaciones();

    return (
        <div className="min-h-screen bg-base-200">
            <header className="border-b border-base-300 bg-base-100">
                <div className="max-w-6xl mx-auto px-4 py-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p className="text-sm uppercase tracking-widest text-primary font-semibold">Gestión de asociaciones</p>
                        <h1 className="text-3xl font-bold text-base-content">Liquidación de asignaciones</h1>
                        <p className="text-base-content/70 text-sm mt-1">
                            Visualiza el estado de las rendiciones pendientes, aprueba gastos y registra nuevas liquidaciones.
                        </p>
                    </div>
                    <button className="btn btn-primary btn-wide md:btn-md md:w-auto" onClick={openModal} type="button">
                        Nueva liquidación
                    </button>
                </div>
            </header>
            <main className="max-w-6xl mx-auto px-4 py-8 space-y-6">
                <section className="card bg-base-100 border border-base-200 shadow-sm">
                    <div className="card-body gap-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <label className="form-control">
                                <div className="label">
                                    <span className="label-text font-semibold text-base-content">Buscar asociación</span>
                                </div>
                                <input
                                    className="input input-bordered"
                                    placeholder="Ej. Fundación Manos Solidarias"
                                    type="search"
                                    value={filters.search}
                                    onChange={(event) => setFilters({ search: event.target.value })}
                                />
                            </label>
                            <label className="form-control">
                                <div className="label">
                                    <span className="label-text font-semibold text-base-content">Estado</span>
                                </div>
                                <select
                                    className="select select-bordered"
                                    value={filters.estado}
                                    onChange={(event) => setFilters({ estado: event.target.value })}
                                >
                                    {estadoOptions.map((option) => (
                                        <option key={option} value={option}>
                                            {option === 'todas' ? 'Todas' : option}
                                        </option>
                                    ))}
                                </select>
                            </label>
                            <div className="flex items-end">
                                <button className="btn btn-outline w-full md:w-auto" type="button" onClick={resetFilters}>
                                    Limpiar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <LiquidacionesTable liquidaciones={liquidaciones} />
                </section>
            </main>
            <NuevaLiquidacionModal isOpen={isModalOpen} onClose={closeModal} />
        </div>
    );
};

export default LiquidacionesPage;
