<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class StorePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Store $store
     * @return Response|bool
     */
    public function view(User $user, Store $store)
    {
        return $store->id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->stores()->count() < 10; # 10 dan fazla eklemesin kimse
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Store $store
     * @return Response|bool
     */
    public function update(User $user, Store $store)
    {
        return $user->id == $store->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Store $store
     * @return Response|bool
     */
    public function delete(User $user, Store $store)
    {
        return $user->id == $store->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Store $store
     * @return Response|bool
     */
    public function restore(User $user, Store $store)
    {
        return $user->id == $store->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Store $store
     * @return Response|bool
     */
    public function forceDelete(User $user, Store $store)
    {
        return $user->id == $store->id;
    }
}
