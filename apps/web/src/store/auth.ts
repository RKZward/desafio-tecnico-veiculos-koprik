import { create } from "zustand";
import api from "@/lib/api";

export type User = { id: number; name: string; email: string };
export type LoginPayload = { email: string; password: string };
export type RegisterPayload = {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
};

type AuthState = {
  token: string | null;
  user: User | null;
  loading: boolean;

  login: (payload: LoginPayload) => Promise<void>;
  register: (payload: RegisterPayload) => Promise<void>;
  getMe: () => Promise<void>;
  logout: () => Promise<void>;
};

export const useAuthStore = create<AuthState>((set, get) => ({
  token: localStorage.getItem("token"),
  user: null,
  loading: false,

  login: async (payload) => {
    set({ loading: true });
    try {
      const { data } = await api.post<{ token: string }>("/auth/entrar", payload);
      localStorage.setItem("token", data.token);
      set({ token: data.token });
      await get().getMe();
    } finally {
      set({ loading: false });
    }
  },

  register: async (payload) => {
    set({ loading: true });
    try {
      const { data } = await api.post<{ token: string }>("/auth/registrar", payload);
      localStorage.setItem("token", data.token);
      set({ token: data.token });
      await get().getMe();
    } finally {
      set({ loading: false });
    }
  },

  getMe: async () => {
    if (!get().token) return;
    const { data } = await api.get<User>("/auth/me");
    set({ user: data });
  },

  logout: async () => {
    try {
      await api.post("/auth/sair");
    } catch {
      // ignora erro de rede
    } finally {
      localStorage.removeItem("token");
      set({ token: null, user: null });
    }
  },
}));
