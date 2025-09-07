<?php

namespace App\Http\Controllers;

use App\Services\SidebarMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SidebarMenuService $sidebarMenuService
    ) {}

    /**
     * Get the sidebar menu items for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $menuItems = $this->sidebarMenuService->getMenuItems();

            return response()->json([
                'success' => true,
                'data' => $menuItems,
                'message' => 'Sidebar menu loaded successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to load sidebar menu',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}