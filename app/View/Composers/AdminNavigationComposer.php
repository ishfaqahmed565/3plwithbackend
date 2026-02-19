<?php

namespace App\View\Composers;

use Illuminate\View\View;

class AdminNavigationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $currentRoute = request()->route()->getName();
        
        $navigation = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'route' => 'admin.dashboard'],
            ['label' => 'All Shipments', 'url' => route('admin.all-shipments'), 'route' => 'admin.all-shipments'],
            ['label' => 'No Rack Shipments', 'url' => route('admin.no-rack-shipments'), 'route' => 'admin.no-rack-shipments'],
            ['label' => 'Unknown Shipments', 'url' => route('admin.unknown-shipments'), 'route' => 'admin.unknown-shipments'],
            ['label' => 'Clients', 'url' => route('admin.clients.index'), 'route' => 'admin.clients.*'],
            ['label' => 'Agents', 'url' => route('admin.agents.index'), 'route' => 'admin.agents.*'],
            ['label' => 'Admins', 'url' => route('admin.admins.index'), 'route' => 'admin.admins.*'],
    
        ];
        
        // Set active state based on current route
        $navigation = array_map(function ($item) use ($currentRoute) {
            $item['active'] = $this->isRouteActive($currentRoute, $item['route']);
            return $item;
        }, $navigation);
        
        $view->with('navigation', $navigation);
    }
    
    /**
     * Check if the given route pattern matches the current route.
     */
    private function isRouteActive(string $currentRoute, string $routePattern): bool
    {
        // Handle wildcard routes (e.g., 'admin.clients.*')
        if (str_ends_with($routePattern, '.*')) {
            $prefix = substr($routePattern, 0, -2);
            return str_starts_with($currentRoute, $prefix);
        }
        
        // Exact match
        return $currentRoute === $routePattern;
    }
}
