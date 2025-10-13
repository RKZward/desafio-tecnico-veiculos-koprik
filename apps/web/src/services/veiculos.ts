
import { api } from "../lib/api";

export type VeiculoPayload = {
  marca: string;
  modelo: string;
  ano: number;
  placa: string;
  chassi: string;
  km: number;
  valor_venda: number;
  cambio: "manual" | "automatico" | "cvt";
  combustivel: "gasolina"|"etanol"|"flex"|"diesel"|"eletrico"|"hibrido";
  cor?: string | null;
};

export async function listarVeiculos(params?: Record<string, any>) {
  const { data } = await api.get("/veiculos", { params }); // <- /veiculos
  return data;
}

export async function criarVeiculo(payload: VeiculoPayload) {
  const { data } = await api.post("/veiculos", payload);   // <- /veiculos
  return data;
}

export async function obterVeiculo(id: number|string) {
  const { data } = await api.get(`/veiculos/${id}`);
  return data;
}

export async function atualizarVeiculo(id: number|string, payload: Partial<VeiculoPayload>) {
  const { data } = await api.put(`/veiculos/${id}`, payload);
  return data;
}

export async function excluirVeiculo(id: number|string) {
  await api.delete(`/veiculos/${id}`);
}
