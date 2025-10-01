import LiquidacionStatusBadge from './LiquidacionStatusBadge';

const formatter = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    minimumFractionDigits: 2,
});

const LiquidacionesTable = ({ liquidaciones }) => {
    if (!liquidaciones.length) {
        return (
            <div className="card bg-base-100 shadow-sm border border-base-200">
                <div className="card-body items-center text-center">
                    <h3 className="card-title text-base-content/80">No se encontraron liquidaciones</h3>
                    <p className="text-base-content/60 text-sm">
                        Ajusta los filtros o crea una nueva liquidación para comenzar.
                    </p>
                </div>
            </div>
        );
    }

    return (
        <div className="overflow-x-auto rounded-xl border border-base-200 shadow-sm bg-base-100">
            <table className="table">
                <thead>
                    <tr className="text-sm text-base-content/60">
                        <th>Asociación</th>
                        <th className="text-right">Monto asignado</th>
                        <th className="text-right">Monto gastado</th>
                        <th>Estado</th>
                        <th className="text-right">Fecha presentación</th>
                    </tr>
                </thead>
                <tbody>
                    {liquidaciones.map((liquidacion) => (
                        <tr key={liquidacion.id} className="hover:bg-base-200/50">
                            <td>
                                <div className="flex flex-col">
                                    <span className="font-semibold text-base-content">{liquidacion.asociacion}</span>
                                    <span className="text-xs text-base-content/60">ID: {liquidacion.id}</span>
                                </div>
                            </td>
                            <td className="text-right font-medium text-base-content">
                                {formatter.format(liquidacion.montoAsignado)}
                            </td>
                            <td className="text-right text-base-content">
                                {formatter.format(liquidacion.montoGastado)}
                            </td>
                            <td>
                                <LiquidacionStatusBadge estado={liquidacion.estado} />
                            </td>
                            <td className="text-right text-base-content/70">
                                {new Date(liquidacion.fechaPresentacion).toLocaleDateString('es-AR', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                })}
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default LiquidacionesTable;
