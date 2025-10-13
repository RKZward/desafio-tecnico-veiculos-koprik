import { useForm } from "react-hook-form";
import { useAuthStore } from "@/store/auth";
import { useNavigate, Link } from "react-router-dom";

type Form = { name: string; email: string; password: string; password_confirmation: string };

export default function Register() {
  const { register, handleSubmit } = useForm<Form>();
  const { register: registerUser, loading } = useAuthStore();
  const navigate = useNavigate();

  const onSubmit = async (data: Form) => {
    await registerUser(data);
    navigate("/veiculos");
  };

  return (
    <section className="max-w-md mx-auto mt-8 bg-white p-6 rounded border">
      <h1 className="text-xl font-bold mb-4">Registrar</h1>
      <form onSubmit={handleSubmit(onSubmit)} className="grid gap-3">
        <input {...register("name")} placeholder="Nome" className="border rounded px-3 py-2" />
        <input {...register("email")} placeholder="E-mail" className="border rounded px-3 py-2" />
        <input {...register("password")} placeholder="Senha" type="password" className="border rounded px-3 py-2" />
        <input {...register("password_confirmation")} placeholder="Confirmar senha" type="password" className="border rounded px-3 py-2" />
        <button disabled={loading} className="bg-black text-white px-3 py-2 rounded">Criar conta</button>
      </form>
      <p className="text-sm mt-3">Já possui conta? <Link to="/login" className="underline">Entrar</Link></p>
    </section>
  );
}
