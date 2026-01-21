<?php

// This file is kept for backward compatibility but is no longer actively used.
// All routes are now consolidated under admin.* with permission-based access control.

if (!function_exists('getUserDashboard')) {
    /**
     * Get dashboard route - now all users go to admin dashboard
     */
    function getUserDashboard(): string
    {
        return route('admin.dashboard');
    }
}