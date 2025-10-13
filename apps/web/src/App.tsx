import { useEffect } from "react";
import { Outlet } from "react-router-dom";
import Navbar from "@/components/Navbar";
import { useAuthStore } from "@/store/auth";

export default function App() {
  const { token, user, getMe } = useAuthStore();

  useEffect(() => {
    if (token && !user) getMe();
  }, [token]);

  return (
    <div className="min-h-screen bg-slate-50 text-slate-900">
      <Navbar />
      <main className="max-w-6xl mx-auto p-4">
        <Outlet />
      </main>
    </div>
  );
}
