export type User = {
  id: number;
  name: string;
  email: string;
  is_admin?: boolean;
};

export type Vehicle = {
  id: number;
  placa: string;
  chassi: string;
  marca: string;
  modelo: string;
  versao?: string | null;
  valor_venda: string | number;
  cor?: string | null;
  km: number;
  cambio: "manual" | "automatico";
  combustivel: "gasolina" | "alcool" | "flex" | "diesel" | "hibrido" | "eletrico";
  user_id: number;
  created_at?: string;
  updated_at?: string;
  images?: VehicleImage[];
  audit?: {
    created_by?: string;
    updated_by?: string;
  };
};

export type VehicleImage = {
  id: number;
  vehicle_id: number;
  path: string;      // relativo ao storage
  is_cover: boolean;
  created_at?: string;
  updated_at?: string;
};

export type PaginationMeta = {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
};

export type VehiclesResponse = {
  data: Vehicle[];
  meta?: PaginationMeta;
};

export type LoginPayload = { email: string; password: string };
export type RegisterPayload = { name: string; email: string; password: string; password_confirmation?: string };
