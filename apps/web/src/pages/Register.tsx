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
    <section className="max-w-xl mx-auto bg-white border rounded p-8 mt-16">
      <h2 className="text-2xl font-semibold mb-6">Registrar</h2>

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
        <input className="w-full border rounded p-3" placeholder="nome"
               {...register("name", { required: true })} />
        <input className="w-full border rounded p-3" placeholder="email"
               type="email" {...register("email", { required: true })} />
        <input className="w-full border rounded p-3" placeholder="senha"
               type="password" {...register("password", { required: true, minLength: 6 })} />
        <input className="w-full border rounded p-3" placeholder="confirme a senha"
               type="password" {...register("password_confirmation", { required: true })} />

        <button type="submit" disabled={loading}
                className="w-full p-3 rounded bg-black text-white">
          {loading ? "Criando conta..." : "Criar conta"}
        </button>
      </form>

      <p className="mt-4 text-sm">
        Já possui conta? <Link className="underline" to="/login">Entrar</Link>
      </p>
    </section>
  );
}
