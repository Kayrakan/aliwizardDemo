<?php

namespace App\Policies;

use App\Models\OrderTag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderTagsPolicy
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

    public function index()
    {
        return true;
    }
    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param OrderTag $orderTag
     * @return Response|bool
     */
    public function view(User $user, OrderTag $orderTag)
    {
        return $user->id == $orderTag->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    public function store(User $user)
    {
        return true;
    }
    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param OrderTag $orderTag
     * @return Response|bool
     */
    public function update(User $user, OrderTag $orderTag)
    {
//        dump([$user->id,$orderTag->user_id]);
        return true;
        return $user->id == $orderTag->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param OrderTag $orderTag
     * @return Response|bool
     */
    public function delete(User $user, OrderTag $orderTag)
    {
        return $user->id == $orderTag->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param OrderTag $orderTag
     * @return Response|bool
     */
    public function restore(User $user, OrderTag $orderTag)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param OrderTag $orderTag
     * @return Response|bool
     */
    public function forceDelete(User $user, OrderTag $orderTag)
    {
        //
    }
}
