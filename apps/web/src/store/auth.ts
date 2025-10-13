import { create } from "zustand";
import api from "@/lib/api";
import type { User, LoginPayload, RegisterPayload } from "@/types";

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
      const { data } = await api.post("/auth/login", payload);
      // Assumindo retorno { token: "...", user: {...} }
      localStorage.setItem("token", data.token);
      set({ token: data.token, user: data.user });
    } finally {
      set({ loading: false });
    }
  },

  register: async (payload) => {
    set({ loading: true });
    try {
      await api.post("/auth/register", payload);
      // Após registro, efetua login automático (opcional)
      await get().login({ email: payload.email, password: payload.password });
    } finally {
      set({ loading: false });
    }
  },

  getMe: async () => {
    if (!get().token) return;
    set({ loading: true });
    try {
      const { data } = await api.get("/auth/me");
      set({ user: data });
    } finally {
      set({ loading: false });
    }
  },

  logout: async () => {
    try {
      await api.post("/auth/logout");
    } catch (_) {
      // ignorar
    } finally {
      localStorage.removeItem("token");
      set({ token: null, user: null });
    }
  }
}));
