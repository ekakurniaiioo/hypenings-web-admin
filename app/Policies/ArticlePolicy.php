<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Cek apakah user punya akses penuh (admin, superadmin, editor)
     */
    private function hasFullAccess(User $user)
    {
        return in_array($user->role, ['admin', 'superadmin', 'editor']);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['author', 'editor', 'admin', 'superadmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article)
    {
        if ($this->hasFullAccess($user)) {
            return true;
        }

        return $user->role === 'author' && $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article)
    {
        if ($this->hasFullAccess($user)) {
            return true;
        }

        return $user->role === 'author' && $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     * (Dipakai kalau pakai SoftDeletes)
     */
    public function restore(User $user, Article $article)
    {
        return $this->hasFullAccess($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article)
    {
        return $this->hasFullAccess($user);
    }
}
