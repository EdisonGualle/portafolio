const STATUS_STYLES = {
    Pendiente: 'badge-warning text-warning-content',
    'En Revisión': 'badge-info text-info-content',
    Aprobada: 'badge-success text-success-content',
    Rechazada: 'badge-error text-error-content',
};

const LiquidacionStatusBadge = ({ estado }) => {
    const style = STATUS_STYLES[estado] ?? 'badge-ghost';

    return <span className={`badge font-medium ${style}`}>{estado}</span>;
};

export default LiquidacionStatusBadge;
