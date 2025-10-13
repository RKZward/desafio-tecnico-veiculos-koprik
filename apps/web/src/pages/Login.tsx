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
    <section className="max-w-md mx-auto mt-8 bg-white p-6 rounded border">
      <h1 className="text-xl font-bold mb-4">Entrar</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="grid gap-3">
        <input {...register("email")} placeholder="E-mail" className="border rounded px-3 py-2" />
        <input {...register("password")} placeholder="Senha" type="password" className="border rounded px-3 py-2" />
        <button disabled={loading} className="bg-black text-white px-3 py-2 rounded">Entrar</button>
      </form>
      <p className="text-sm mt-3">Não tem conta? <Link to="/registrar" className="underline">Registrar</Link></p>
    </section>
  );
}
