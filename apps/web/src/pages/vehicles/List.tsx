import { useEffect, useMemo, useState } from "react";
import api from "@/lib/api";
import { Vehicle, VehiclesResponse } from "@/types";
import { Link, useSearchParams } from "react-router-dom";
import Pagination from "@/components/Pagination";
import clsx from "clsx";

type Sort = string; // ex: "km,-valor_venda"

export default function VehiclesList() {
  const [items, setItems] = useState<Vehicle[]>([]);
  const [meta, setMeta] = useState({ current_page: 1, last_page: 1, total: 0, per_page: 10 });
  const [loading, setLoading] = useState(false);

  const [sp, setSp] = useSearchParams();
  const q = sp.get("q") ?? "";
  const marca = sp.get("marca") ?? "";
  const modelo = sp.get("modelo") ?? "";
  const placa = sp.get("placa") ?? "";
  const page = Number(sp.get("page") ?? 1);
  const per_page = Number(sp.get("per_page") ?? 10);
  const sort = sp.get("sort") ?? "km,-valor_venda";

  const params = useMemo(() => ({ q, marca, modelo, placa, page, per_page, sort }), [q, marca, modelo, placa, page, per_page, sort]);

  useEffect(() => {
    const load = async () => {
      setLoading(true);
      try {
        const { data } = await api.get<VehiclesResponse>("/vehicles", { params });
        setItems(data.data);
        // Aceita meta ou headers padrão
        const metaRes = (data as any).meta ?? {};
        setMeta({
          current_page: metaRes.current_page ?? page,
          last_page: metaRes.last_page ?? 1,
          total: metaRes.total ?? data.data.length,
          per_page: metaRes.per_page ?? per_page
        });
      } finally {
        setLoading(false);
      }
    };
    load();
  }, [params.page, params.per_page, params.q, params.marca, params.modelo, params.placa, params.sort]);

  const updateSp = (next: Record<string, string | number>) => {
    const n = new URLSearchParams(sp);
    Object.entries(next).forEach(([k, v]) => n.set(k, String(v)));
    setSp(n, { replace: true });
  };

  return (
    <section className="space-y-4">
      <h1 className="text-xl font-bold">Veículos</h1>

      <div className="bg-white p-3 rounded border grid gap-2 md:grid-cols-5">
        <input placeholder="Busca global (q)" defaultValue={q} onBlur={(e) => updateSp({ q: e.target.value, page: 1 })} className="border rounded px-2 py-1" />
        <input placeholder="Marca" defaultValue={marca} onBlur={(e) => updateSp({ marca: e.target.value, page: 1 })} className="border rounded px-2 py-1" />
        <input placeholder="Modelo" defaultValue={modelo} onBlur={(e) => updateSp({ modelo: e.target.value, page: 1 })} className="border rounded px-2 py-1" />
        <input placeholder="Placa" defaultValue={placa} onBlur={(e) => updateSp({ placa: e.target.value, page: 1 })} className="border rounded px-2 py-1" />
        <select defaultValue={sort} onChange={(e) => updateSp({ sort: e.target.value })} className="border rounded px-2 py-1">
          <option value="km">km ↑</option>
          <option value="-km">km ↓</option>
          <option value="valor_venda">valor_venda ↑</option>
          <option value="-valor_venda">valor_venda ↓</option>
          <option value="km,-valor_venda">km ↑, valor ↓</option>
        </select>
      </div>

      <div className="bg-white border rounded">
        <table className="w-full">
          <thead>
            <tr className="text-left bg-slate-50">
              <th className="p-2">Placa</th>
              <th className="p-2">Marca</th>
              <th className="p-2">Modelo</th>
              <th className="p-2">KM</th>
              <th className="p-2">Valor</th>
              <th className="p-2">Ações</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td className="p-3" colSpan={6}>Carregando…</td></tr>
            ) : items.length === 0 ? (
              <tr><td className="p-3" colSpan={6}>Nenhum veículo encontrado.</td></tr>
            ) : (
              items.map(v => (
                <tr key={v.id} className={clsx("border-t")}>
                  <td className="p-2">{v.placa}</td>
                  <td className="p-2">{v.marca}</td>
                  <td className="p-2">{v.modelo}</td>
                  <td className="p-2">{v.km}</td>
                  <td className="p-2">R$ {Number(v.valor_venda).toLocaleString("pt-BR", { minimumFractionDigits: 2 })}</td>
                  <td className="p-2">
                    <div className="flex gap-2">
                      <Link className="underline" to={`/veiculos/${v.id}`}>Ver</Link>
                      <Link className="underline" to={`/veiculos/${v.id}/editar`}>Editar</Link>
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      <div className="flex items-center justify-between">
        <Pagination
          page={meta.current_page}
          lastPage={meta.last_page}
          onChange={(p) => updateSp({ page: p })}
        />
        <div className="text-sm text-slate-500">Total: {meta.total}</div>
      </div>
    </section>
  );
}
