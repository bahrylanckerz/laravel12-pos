<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ReturnPurchase;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnPurchasePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ReturnPurchase');
    }

    public function view(AuthUser $authUser, ReturnPurchase $returnPurchase): bool
    {
        return $authUser->can('View:ReturnPurchase');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ReturnPurchase');
    }

    public function update(AuthUser $authUser, ReturnPurchase $returnPurchase): bool
    {
        return $authUser->can('Update:ReturnPurchase');
    }

    public function delete(AuthUser $authUser, ReturnPurchase $returnPurchase): bool
    {
        return $authUser->can('Delete:ReturnPurchase');
    }

    public function restore(AuthUser $authUser, ReturnPurchase $returnPurchase): bool
    {
        return $authUser->can('Restore:ReturnPurchase');
    }

    public function forceDelete(AuthUser $authUser, ReturnPurchase $returnPurchase): bool
    {
        return $authUser->can('ForceDelete:ReturnPurchase');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ReturnPurchase');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ReturnPurchase');
    }

    public function replicate(AuthUser $authUser, ReturnPurchase $returnPurchase): bool
    {
        return $authUser->can('Replicate:ReturnPurchase');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ReturnPurchase');
    }

}