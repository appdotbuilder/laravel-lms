<?php

namespace App\Http\Controllers;

use App\Services\SidebarMenuService;
use Illuminate\Http\JsonResponse;

class SidebarController extends Controller
{
    /**
     * The sidebar menu service instance.
     */
    protected SidebarMenuService $sidebarMenuService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\SidebarMenuService  $sidebarMenuService
     */
    public function __construct(SidebarMenuService $sidebarMenuService)
    {
        $this->sidebarMenuService = $sidebarMenuService;
    }

    /**
     * Display the sidebar menu for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $menuItems = $this->sidebarMenuService->getMenuItems();

            return response()->json([
                'success' => true,
                'data' => $menuItems
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load menu items',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}