<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all orders
        if ($user->hasRole('admin')) {
            return true;
        }

        // Seller can view orders for their kantin
        if ($user->hasRole('seller')) {
            return $user->kantin !== null;
        }

        // User can view their own orders
        if ($user->hasRole('user')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // Admin can view any order
        if ($user->hasRole('admin')) {
            return true;
        }

        // Seller can view orders for their kantin
        if ($user->hasRole('seller')) {
            return $order->orderItems->some(function ($item) use ($user) {
                return $item->menu->kantin_id === $user->kantin->id;
            });
        }

        // User can view their own orders
        if ($user->hasRole('user')) {
            return $order->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only users can create orders
        return $user->hasRole('user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Admin can update any order
        if ($user->hasRole('admin')) {
            return true;
        }

        // Seller can update orders for their kantin
        if ($user->hasRole('seller')) {
            return $order->orderItems->some(function ($item) use ($user) {
                return $item->menu->kantin_id === $user->kantin->id;
            });
        }

        // User can update their own orders (but only in certain statuses)
        if ($user->hasRole('user')) {
            return $order->user_id === $user->id && in_array($order->status, ['pending']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        // Admin can delete any order
        if ($user->hasRole('admin')) {
            return true;
        }

        // Seller can delete orders for their kantin (with more flexible status)
        if ($user->hasRole('seller')) {
            $isOwner = $order->orderItems->some(function ($item) use ($user) {
                return $item->menu && $item->menu->kantin && $item->menu->kantin_id === $user->kantin->id;
            });

            // Allow deletion for more statuses, but prevent deletion of completed orders
            $allowedStatuses = ['pending', 'processing', 'cancelled'];
            return $isOwner && in_array($order->status, $allowedStatuses);
        }

        // User can delete their own orders (but only in certain statuses)
        if ($user->hasRole('user')) {
            return $order->user_id === $user->id && in_array($order->status, ['pending']);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        // Only admin can restore orders
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        // Only admin can permanently delete orders
        return $user->hasRole('admin');
    }
}
