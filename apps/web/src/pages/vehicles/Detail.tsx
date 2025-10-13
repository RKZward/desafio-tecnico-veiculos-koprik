import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import api from "@/lib/api";
import { Vehicle, VehicleImage } from "@/types";
import ImageUploader from "@/components/ImageUploader";

export default function VehiclesDetail() {
  const { id } = useParams();
  const [v, setV] = useState<Vehicle | null>(null);
  const [loading, setLoading] = useState(false);

  const load = async () => {
    setLoading(true);
    try {
      const { data } = await api.get(`/vehicles/${id}`);
      setV(data);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => { load(); }, [id]);

  const upload = async (files: FileList) => {
    const form = new FormData();
    Array.from(files).forEach((f) => form.append("files[]", f));
    await api.post(`/vehicles/${id}/images`, form, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    await load();
  };

  const setCover = async (imageId: number) => {
    await api.patch(`/vehicles/${id}/images/${imageId}/cover`);
    await load();
  };

  const removeImage = async (imageId: number) => {
    if (!confirm("Remover esta imagem?")) return;
    await api.delete(`/vehicles/${id}/images/${imageId}`);
    await load();
  };

  const removeVehicle = async () => {
    if (!confirm("Excluir veículo e todas as imagens?")) return;
    await api.delete(`/vehicles/${id}`);
    history.back();
  };

  if (loading && !v) return <p>Carregando…</p>;
  if (!v) return <p>Veículo não encontrado.</p>;

  return (
    <section className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-xl font-bold">Detalhe do Veículo — {v.placa}</h1>
        <div className="flex gap-2">
          <Link to={`/veiculos/${v.id}/editar`} className="border px-3 py-2 rounded">Editar</Link>
          <button onClick={removeVehicle} className="border px-3 py-2 rounded">Excluir</button>
        </div>
      </div>

      <div className="bg-white p-4 rounded border grid md:grid-cols-2 gap-4">
        <div className="space-y-1">
          <p><b>Marca/Modelo:</b> {v.marca} / {v.modelo} {v.versao ? `(${v.versao})` : ""}</p>
          <p><b>Cor:</b> {v.cor ?? "-"}</p>
          <p><b>KM:</b> {v.km}</p>
          <p><b>Câmbio:</b> {v.cambio}</p>
          <p><b>Combustível:</b> {v.combustivel}</p>
          <p><b>Valor:</b> R$ {Number(v.valor_venda).toLocaleString("pt-BR", { minimumFractionDigits: 2 })}</p>
          {v.audit && (
            <>
              <p className="text-sm text-slate-500"><b>Criado por:</b> {v.audit.created_by ?? "-"}</p>
              <p className="text-sm text-slate-500"><b>Atualizado por:</b> {v.audit.updated_by ?? "-"}</p>
            </>
          )}
        </div>

        <div>
          <ImageUploader onFiles={upload} multiple />
          <div className="mt-3 grid grid-cols-2 md:grid-cols-3 gap-3">
            {(v.images ?? []).map((img: VehicleImage) => (
              <div key={img.id} className="border rounded overflow-hidden bg-white">
                <img
                  src={resolveImageUrl(img.path)}
                  alt=""
                  className="w-full h-32 object-cover"
                />
                <div className="p-2 flex items-center justify-between">
                  <span className="text-xs">{img.is_cover ? "Capa" : "Imagem"}</span>
                  <div className="flex gap-2">
                    {!img.is_cover && (
                      <button className="text-xs underline" onClick={() => setCover(img.id)}>Definir capa</button>
                    )}
                    <button className="text-xs underline" onClick={() => removeImage(img.id)}>Remover</button>
                  </div>
                </div>
              </div>
            ))}
            {(!v.images || v.images.length === 0) && <p className="text-sm text-slate-500">Nenhuma imagem enviada.</p>}
          </div>
        </div>
      </div>
    </section>
  );
}

function resolveImageUrl(relPath: string) {
  // A API deve expor via storage público (php artisan storage:link).
  // Exemplo típico: http://localhost:8000/storage/<relPath>
  const base = (import.meta.env.VITE_API_URL as string).replace(/\/api\/?$/, "");
  return `${base}/storage/${relPath}`;
}
