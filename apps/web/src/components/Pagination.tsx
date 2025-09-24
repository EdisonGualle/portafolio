type Props = {
  page: number;
  lastPage: number;
  onChange: (page: number) => void;
};

export default function Pagination({ page, lastPage, onChange }: Props) {
  if (lastPage <= 1) return null;
  const prev = Math.max(1, page - 1);
  const next = Math.min(lastPage, page + 1);
  return (
    <div className="flex gap-2 justify-center my-6">
      <button disabled={page === 1} onClick={() => onChange(prev)} className="btn disabled:opacity-50">‹</button>
      <span className="muted px-2 py-2">{page} / {lastPage}</span>
      <button disabled={page === lastPage} onClick={() => onChange(next)} className="btn disabled:opacity-50">›</button>
    </div>
  );
}
