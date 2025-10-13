import { Outlet, Navigate } from "react-router-dom";
import { useAuthStore } from "@/store/auth";

export default function Protected() {
  const { token } = useAuthStore();
  if (!token) return <Navigate to="/login" replace />;
  return <Outlet />;
}
