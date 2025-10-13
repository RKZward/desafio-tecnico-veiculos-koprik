import { useForm } from "react-hook-form";
import { useAuthStore } from "@/store/auth";
import { useNavigate, Link } from "react-router-dom";

type Form = { email: string; password: string };

export default function Login() {
  const { register, handleSubmit } = useForm<Form>();
  const { login, loading } = useAuthStore();
  const navigate = useNavigate();

  const onSubmit = async (data: Form) => {
    await login(data);
    navigate("/veiculos");
  };

  return (
    <section className="max-w-xl mx-auto bg-white border rounded p-8 mt-16">
      <h2 className="text-2xl font-semibold mb-6">Entrar</h2>

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <input className="w-full border rounded p-3" placeholder="email"
               type="email" {...register("email", { required: true })} />
        <input className="w-full border rounded p-3" placeholder="senha"
               type="password" {...register("password", { required: true })} />

        <button type="submit" disabled={loading}
                className="w-full p-3 rounded bg-black text-white">
          {loading ? "Entrando..." : "Entrar"}
        </button>
      </form>

      <p className="mt-4 text-sm">
        Não tem conta? <Link className="underline" to="/registrar">Registrar</Link>
      </p>
    </section>
  );
}
