import { useEffect } from "react";
import { useForm } from "react-hook-form";
import api from "@/lib/api";
import { useNavigate, useParams } from "react-router-dom";
import type { Vehicle } from "@/types";

type Form = Omit<Vehicle,
  "id" | "user_id" | "created_at" | "updated_at" | "images" | "audit"
>;

const defaults: Partial<Form> = {
  versao: "",
  cor: "",
  cambio: "manual",
  combustivel: "gasolina",
  km: 0,
  valor_venda: 0
};

export default function VehiclesForm() {
  const { id } = useParams();
  const isEdit = Boolean(id);
  const { register, handleSubmit, setValue, reset } = useForm<Form>({ defaultValues: defaults });
  const navigate = useNavigate();

  useEffect(() => {
    if (!isEdit) return;
    (async () => {
      const { data } = await api.get(`/vehicles/${id}`);
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
        combustivel: v.combustivel
      });
    })();
  }, [id]);

  const onSubmit = async (data: Form) => {
    if (isEdit) {
      await api.put(`/vehicles/${id}`, data);
    } else {
      await api.post(`/vehicles`, data);
    }
    navigate("/veiculos");
  };

  return (
    <section className="max-w-2xl">
      <h1 className="text-xl font-bold mb-4">{isEdit ? "Editar veículo" : "Cadastrar veículo"}</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="grid gap-3 bg-white p-4 rounded border">
        <div className="grid md:grid-cols-3 gap-3">
          <input className="border rounded px-2 py-1" placeholder="Placa (ABC1D23)" {...register("placa")} />
          <input className="border rounded px-2 py-1" placeholder="Chassi (17 chars)" {...register("chassi")} />
          <input className="border rounded px-2 py-1" placeholder="Marca" {...register("marca")} />
          <input className="border rounded px-2 py-1" placeholder="Modelo" {...register("modelo")} />
          <input className="border rounded px-2 py-1" placeholder="Versão" {...register("versao")} />
          <input className="border rounded px-2 py-1" placeholder="Cor" {...register("cor")} />
        </div>

        <div className="grid md:grid-cols-4 gap-3">
          <input className="border rounded px-2 py-1" placeholder="KM" type="number" {...register("km", { valueAsNumber: true })} />
          <input className="border rounded px-2 py-1" placeholder="Valor de venda" type="number" step="0.01" {...register("valor_venda", { valueAsNumber: true })} />
          <select className="border rounded px-2 py-1" {...register("cambio")}>
            <option value="manual">Manual</option>
            <option value="automatico">Automático</option>
          </select>
          <select className="border rounded px-2 py-1" {...register("combustivel")}>
            <option value="gasolina">Gasolina</option>
            <option value="alcool">Álcool</option>
            <option value="flex">Flex</option>
            <option value="diesel">Diesel</option>
            <option value="hibrido">Híbrido</option>
            <option value="eletrico">Elétrico</option>
          </select>
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
