<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event)
    {
        return $user->id === $event->organizer_id
                ? Response::allow("Check-In Berhasil",200)
                : Response::denyWithStatus(403, 'akses di tolak', 403);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event)
    {
        if($user->id !== $event->organizer_id){
            return Response::denyWithStatus(403, 'Akses di tolak', 403);
        }
        else if(!$event['available']){
            return Response::denyWithStatus(409, 'Tidak dapat mengubah saat Event berlangsung / selesai', 409);
        }
        else {
            return Response::allow("Telah diperbarui",200);
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event)
    {
        return $user->id === $event->organizer_id
                ? Response::allow("Event telah dihapus",200)
                : Response::denyWithStatus(403, 'akses di tolak', 403);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event)
    {
        //
    }
}
