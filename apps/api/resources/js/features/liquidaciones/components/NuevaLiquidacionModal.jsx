const NuevaLiquidacionModal = ({ isOpen, onClose }) => {
    if (!isOpen) {
        return null;
    }

    return (
        <dialog className="modal modal-open">
            <div className="modal-box max-w-2xl">
                <div className="flex items-start justify-between gap-4">
                    <div>
                        <h3 className="font-bold text-lg text-base-content">Nueva liquidación</h3>
                        <p className="text-sm text-base-content/70 mt-1">
                            Completa la información para registrar la rendición de gastos de la asociación.
                        </p>
                    </div>
                    <button className="btn btn-sm btn-circle btn-ghost" onClick={onClose} type="button">
                        ✕
                    </button>
                </div>
                <div className="divider my-4" />
                <form className="space-y-4">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label className="form-control">
                            <div className="label">
                                <span className="label-text">Asociación</span>
                                <span className="label-text-alt text-error">*</span>
                            </div>
                            <input type="text" className="input input-bordered" placeholder="Selecciona una asociación" />
                        </label>
                        <label className="form-control">
                            <div className="label">
                                <span className="label-text">Fecha de presentación</span>
                                <span className="label-text-alt text-error">*</span>
                            </div>
                            <input type="date" className="input input-bordered" />
                        </label>
                        <label className="form-control">
                            <div className="label">
                                <span className="label-text">Monto asignado</span>
                                <span className="label-text-alt text-error">*</span>
                            </div>
                            <input type="number" min="0" className="input input-bordered" placeholder="0,00" />
                        </label>
                        <label className="form-control">
                            <div className="label">
                                <span className="label-text">Monto gastado</span>
                                <span className="label-text-alt text-error">*</span>
                            </div>
                            <input type="number" min="0" className="input input-bordered" placeholder="0,00" />
                        </label>
                    </div>
                    <label className="form-control">
                        <div className="label">
                            <span className="label-text">Observaciones</span>
                            <span className="label-text-alt text-base-content/50">Opcional</span>
                        </div>
                        <textarea className="textarea textarea-bordered min-h-28" placeholder="Describe el detalle de la rendición" />
                    </label>
                    <div className="alert alert-info text-sm">
                        <span>
                            En esta fase la información es de referencia. En la etapa de integración se conectará con la API
                            oficial.
                        </span>
                    </div>
                </form>
                <div className="modal-action mt-6">
                    <button className="btn btn-ghost" type="button" onClick={onClose}>
                        Cancelar
                    </button>
                    <button className="btn btn-primary" type="button">
                        Guardar borrador
                    </button>
                </div>
            </div>
            <form method="dialog" className="modal-backdrop">
                <button onClick={onClose}>Cerrar</button>
            </form>
        </dialog>
    );
};

export default NuevaLiquidacionModal;
