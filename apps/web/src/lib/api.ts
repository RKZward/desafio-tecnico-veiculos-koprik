import axios from "axios";
import { useAuthStore } from "@/store/auth";

const API_BASE = import.meta.env.VITE_API_URL as string;

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL as string,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json"
  },
  // Cookies não são usados; autenticação via Bearer token
});

api.interceptors.request.use((config) => {
  const token = useAuthStore.getState().token;
  if (token) {
    config.headers = config.headers || {};
    (config.headers as any).Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (res) => res,
  (err) => {
    if (err?.response?.status === 401) {
      useAuthStore.getState().logout();
      // redireciona para login
      window.location.href = "/login";
    }
    return Promise.reject(err);
  }
);

export default api;
