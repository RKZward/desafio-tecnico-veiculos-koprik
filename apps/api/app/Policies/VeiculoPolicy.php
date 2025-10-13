<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Veiculo;

class VeiculoPolicy
{
    // Quem pode ver listas (index)
    public function viewAny(User $user): bool
    {
        return true; // todos autenticados podem ver a listagem
    }

    // Quem pode ver um veículo específico (show)
    public function view(User $user, Veiculo $veiculo): bool
    {
        return true; // público autenticado; ajuste se necessário
    }

    // Quem pode criar (store)
    public function create(User $user): bool
    {
        return true; // qualquer usuário autenticado pode criar
    }

    // Quem pode atualizar (update)
    public function update(User $user, Veiculo $veiculo): bool
    {
        // dono pode; admin é tratado pelo Gate::before
        return (int)$veiculo->user_id === (int)$user->id;
    }

    // Quem pode deletar (destroy)
    public function delete(User $user, Veiculo $veiculo): bool
    {
        return (int)$veiculo->user_id === (int)$user->id;
    }

    // (opcionais)
    public function restore(User $user, Veiculo $veiculo): bool { return false; }
    public function forceDelete(User $user, Veiculo $veiculo): bool { return false; }
}
