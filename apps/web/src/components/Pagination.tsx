type Props = {
    page: number;
    lastPage: number;
    onChange: (next: number) => void;
  };
  
  export default function Pagination({ page, lastPage, onChange }: Props) {
    return (
      <div className="flex gap-2 items-center">
        <button disabled={page <= 1} onClick={() => onChange(page - 1)} className="px-2 py-1 border rounded disabled:opacity-50">Anterior</button>
        <span>Página {page} de {lastPage}</span>
        <button disabled={page >= lastPage} onClick={() => onChange(page + 1)} className="px-2 py-1 border rounded disabled:opacity-50">Próxima</button>
      </div>
    );
  }
  