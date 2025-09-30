<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ReturnSale;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnSalePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ReturnSale');
    }

    public function view(AuthUser $authUser, ReturnSale $returnSale): bool
    {
        return $authUser->can('View:ReturnSale');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ReturnSale');
    }

    public function update(AuthUser $authUser, ReturnSale $returnSale): bool
    {
        return $authUser->can('Update:ReturnSale');
    }

    public function delete(AuthUser $authUser, ReturnSale $returnSale): bool
    {
        return $authUser->can('Delete:ReturnSale');
    }

    public function restore(AuthUser $authUser, ReturnSale $returnSale): bool
    {
        return $authUser->can('Restore:ReturnSale');
    }

    public function forceDelete(AuthUser $authUser, ReturnSale $returnSale): bool
    {
        return $authUser->can('ForceDelete:ReturnSale');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ReturnSale');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ReturnSale');
    }

    public function replicate(AuthUser $authUser, ReturnSale $returnSale): bool
    {
        return $authUser->can('Replicate:ReturnSale');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ReturnSale');
    }

}