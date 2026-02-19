<?php

namespace App\View\Composers;

use Illuminate\View\View;

class AgentNavigationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $currentRoute = request()->route()->getName();
        
        $navigation = [
            ['label' => 'Dashboard', 'url' => route('agent.dashboard'), 'route' => 'agent.dashboard'],
            ['label' => 'Scan-1 (Shipment)', 'url' => route('agent.scan.shipment'), 'route' => 'agent.scan.shipment'],
            ['label' => 'Pending Shipments', 'url' => route('agent.pending-shipments'), 'route' => 'agent.pending-shipments'],
            ['label' => 'No Rack Shipments', 'url' => route('agent.no-rack-shipments'), 'route' => 'agent.no-rack-shipments'],
            ['label' => 'Unknown Shipments', 'url' => route('agent.unknown-shipments'), 'route' => 'agent.unknown-shipments'],
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
        // Handle wildcard routes (e.g., 'agent.shipments.*')
        if (str_ends_with($routePattern, '.*')) {
            $prefix = substr($routePattern, 0, -2);
            return str_starts_with($currentRoute, $prefix);
        }
        
        // Exact match
        return $currentRoute === $routePattern;
    }
}
