import { useEffect } from "react";
import { useForm } from "react-hook-form";
import api from "@/lib/api";
import { useNavigate, useParams } from "react-router-dom";
import type { Vehicle } from "@/types";

type Form = Omit<
  Vehicle,
  "id" | "user_id" | "created_at" | "updated_at" | "images" | "audit"
>;

const defaults: Partial<Form> = {
  versao: "",
  cor: "",
  cambio: "manual",
  combustivel: "gasolina",
  km: 0,
  valor_venda: 0,
};

export default function VehiclesForm() {
  const { id } = useParams();
  const isEdit = Boolean(id);
  const { register, handleSubmit, reset } = useForm<Form>({ defaultValues: defaults });
  const navigate = useNavigate();

  useEffect(() => {
    if (!isEdit) return;
    (async () => {
      const { data } = await api.get(`/veiculos/${id}`);
      const v = data as Vehicle;
      reset({
        placa: v.placa,
        chassi: v.chassi,
        marca: v.marca,
        modelo: v.modelo,
        versao: v.versao ?? "",
        valor_venda: Number(v.valor_venda),
        cor: v.cor ?? "",
        km: v.km,
        cambio: v.cambio,
        combustivel: v.combustivel,
      });
    })();
  }, [id, isEdit, reset]);

  const onSubmit = async (data: Form) => {
    if (data.placa) data.placa = data.placa.toUpperCase().replace(/[-\s]/g, "");
    if (isEdit) await api.put(`/veiculos/${id}`, data);
    else await api.post(`/veiculos`, data);
    navigate("/veiculos");
  };

  return (
    <section className="max-w-2xl">
      <h1 className="text-xl font-bold mb-4">{isEdit ? "Editar veículo" : "Cadastrar veículo"}</h1>

      <form onSubmit={handleSubmit(onSubmit)} className="grid gap-4 bg-white p-4 rounded border">
        <div className="grid md:grid-cols-3 gap-3">
          <div>
            <label htmlFor="placa" className="block text-sm font-medium text-gray-700 mb-1">Placa</label>
            <input id="placa" className="border rounded px-2 py-1 w-full" placeholder="ABC1D23" {...register("placa")} />
          </div>

          <div>
            <label htmlFor="chassi" className="block text-sm font-medium text-gray-700 mb-1">Chassi</label>
            <input id="chassi" className="border rounded px-2 py-1 w-full" placeholder="17 caracteres" {...register("chassi")} />
          </div>

          <div>
            <label htmlFor="marca" className="block text-sm font-medium text-gray-700 mb-1">Marca</label>
            <input id="marca" className="border rounded px-2 py-1 w-full" placeholder="Fiat, VW, etc." {...register("marca")} />
          </div>

          <div>
            <label htmlFor="modelo" className="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
            <input id="modelo" className="border rounded px-2 py-1 w-full" placeholder="Toro, Gol, etc." {...register("modelo")} />
          </div>

          <div>
            <label htmlFor="versao" className="block text-sm font-medium text-gray-700 mb-1">Versão</label>
            <input id="versao" className="border rounded px-2 py-1 w-full" placeholder="Ex.: Endurance" {...register("versao")} />
          </div>

          <div>
            <label htmlFor="cor" className="block text-sm font-medium text-gray-700 mb-1">Cor</label>
            <input id="cor" className="border rounded px-2 py-1 w-full" placeholder="Vermelha" {...register("cor")} />
          </div>
        </div>

        <div className="grid md:grid-cols-5 gap-3">
          <div>
            <label htmlFor="km" className="block text-sm font-medium text-gray-700 mb-1">KM</label>
            <input id="km" className="border rounded px-2 py-1 w-full" type="number" {...register("km", { valueAsNumber: true })} />
          </div>

          <div>
            <label htmlFor="valor_venda" className="block text-sm font-medium text-gray-700 mb-1">Valor de Venda</label>
            <input id="valor_venda" className="border rounded px-2 py-1 w-full" type="number" step="0.01" {...register("valor_venda", { valueAsNumber: true })} />
          </div>

          <div>
            <label htmlFor="cambio" className="block text-sm font-medium text-gray-700 mb-1">Câmbio</label>
            <select id="cambio" className="border rounded px-2 py-1 w-full" {...register("cambio")}>
              <option value="manual">Manual</option>
              <option value="automatico">Automático</option>
              <option value="cvt">CVT</option>
            </select>
          </div>

          <div>
            <label htmlFor="combustivel" className="block text-sm font-medium text-gray-700 mb-1">Combustível</label>
            <select id="combustivel" className="border rounded px-2 py-1 w-full" {...register("combustivel")}>
              <option value="gasolina">Gasolina</option>
              <option value="etanol">Etanol</option>
              <option value="flex">Flex</option>
              <option value="diesel">Diesel</option>
              <option value="hibrido">Híbrido</option>
              <option value="eletrico">Elétrico</option>
            </select>
          </div>
        </div>

        <div className="flex gap-2">
          <button className="bg-black text-white px-3 py-2 rounded" type="submit">
            {isEdit ? "Salvar alterações" : "Cadastrar"}
          </button>
          <button className="border px-3 py-2 rounded" type="button" onClick={() => navigate("/veiculos")}>
            Cancelar
          </button>
        </div>
      </form>
    </section>
  );
}
