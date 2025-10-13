import { Link, useNavigate } from "react-router-dom";
import { useAuthStore } from "@/store/auth";

export default function Navbar() {
  const { user, token, logout } = useAuthStore();
  const navigate = useNavigate();

  return (
    <header className="bg-white border-b">
      <div className="max-w-6xl mx-auto p-4 flex items-center justify-between">
        <Link to="/veiculos" className="font-bold">Autoconf — Veículos</Link>
        <nav className="flex items-center gap-4">
          {token ? (
            <>
              <Link to="/veiculos">Veículos</Link>
              <Link to="/veiculos/novo">Cadastrar</Link>
              <span className="text-sm text-slate-500">Olá, {user?.name}</span>
              <button
                onClick={async () => { await logout(); navigate("/login"); }}
                className="px-3 py-1 rounded border"
              >
                Sair
              </button>
            </>
          ) : (
            <>
              <Link to="/login">Entrar</Link>
              <Link to="/registrar">Registrar</Link>
            </>
          )}
        </nav>
      </div>
    </header>
  );
}
