import React, { useEffect, useState, useCallback, useMemo } from 'react';
import { usePage } from '@inertiajs/react';
import { type SharedData } from '@/types';
import { useEcho } from '@/hooks/use-echo';
import { 
    Sidebar, 
    SidebarContent, 
    SidebarFooter, 
    SidebarHeader, 
    SidebarMenu, 
    SidebarMenuButton, 
    SidebarMenuItem,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel
} from '@/components/ui/sidebar';
import { NavUser } from '@/components/nav-user';
import { Link } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import * as LucideIcons from 'lucide-react';
import { setupUserEchoListeners, cleanupEchoListeners } from '@/lib/echo';
import axios from 'axios';

// Define types for our menu structure
interface MenuItem {
    type: 'item';
    label: string;
    href: string;
    icon: string;
    badge?: number;
    isActive?: boolean;
}

interface MenuGroup {
    type: 'group';
    label: string;
    items: MenuItem[];
}

type MenuStructure = MenuGroup[];

// Vue-like Composition API pattern using React hooks
function useSidebarMenu() {
    // Reactive state (equivalent to Vue's ref)
    const [menuItems, setMenuItems] = useState<MenuStructure>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    // Computed properties (equivalent to Vue's computed) - using useMemo for better performance
    const hasMenuItems = useMemo(() => menuItems.length > 0, [menuItems]);
    const isEmpty = useMemo(() => !loading && menuItems.length === 0, [loading, menuItems]);

    // Methods (equivalent to Vue's methods)
    const fetchMenuData = useCallback(async () => {
        try {
            setLoading(true);
            setError(null);
            
            const response = await axios.get('/api/sidebar-menu', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                withCredentials: true,
            });

            if (response.data.success) {
                setMenuItems(response.data.data || []);
            } else {
                throw new Error(response.data.message || 'Failed to load menu items');
            }
        } catch (err) {
            console.error('Error fetching sidebar menu:', err);
            setError(err instanceof Error ? err.message : 'Unknown error occurred');
            setMenuItems([]);
        } finally {
            setLoading(false);
        }
    }, []);

    const handleNotificationUpdate = useCallback(() => {
        // Re-fetch menu when notifications are received
        fetchMenuData();
    }, [fetchMenuData]);

    // Lifecycle hooks (equivalent to Vue's onMounted)
    useEffect(() => {
        fetchMenuData();
    }, [fetchMenuData]);

    // Real-time updates listener (equivalent to Vue's event listeners)
    useEffect(() => {
        const handleNotificationReceived = () => {
            handleNotificationUpdate();
        };

        // Listen for custom notification events
        window.addEventListener('notification-received', handleNotificationReceived);
        
        return () => {
            window.removeEventListener('notification-received', handleNotificationReceived);
        };
    }, [handleNotificationUpdate]);

    return {
        // State
        menuItems,
        loading,
        error,
        // Computed
        hasMenuItems,
        isEmpty,
        // Methods
        fetchMenuData,
        handleNotificationUpdate,
    };
}

// Icon resolver (equivalent to Vue's methods)
function resolveIcon(iconName: string): React.ComponentType<{ className?: string }> {
    // Map string icon names to Lucide React components
    const icons = LucideIcons as unknown as Record<string, React.ComponentType<{ className?: string }>>;
    const IconComponent = icons[iconName];
    
    if (!IconComponent) {
        console.warn(`Icon "${iconName}" not found, using default icon`);
        return LucideIcons.HelpCircle;
    }
    
    return IconComponent;
}

// Main component using Composition API pattern
export function DynamicSidebar() {
    const { auth } = usePage<SharedData>().props;
    const user = auth.user;

    // Initialize Echo for real-time updates
    useEcho();

    // Use our custom hook (equivalent to Vue's composition API)
    const {
        menuItems,
        loading,
        error,
        hasMenuItems,
        isEmpty,
        fetchMenuData
    } = useSidebarMenu();

    // Role-based computed values (equivalent to Vue's computed)
    const roleEmoji = useMemo(() => {
        if (!user) return '🎓';
        switch (user.role) {
            case 'admin': return '🛡️';
            case 'instructor': return '👨‍🏫';
            case 'student': default: return '🎓';
        }
    }, [user]);

    const roleTitle = useMemo(() => {
        if (!user) return 'Student Portal';
        switch (user.role) {
            case 'admin': return 'Admin Panel';
            case 'instructor': return 'Instructor Hub';
            case 'student': default: return 'Student Portal';
        }
    }, [user]);

    // Setup Echo listeners for real-time updates (equivalent to Vue's onMounted)
    useEffect(() => {
        if (user) {
            setupUserEchoListeners(user.id);
            
            // Cleanup on unmount (equivalent to Vue's onUnmounted)
            return () => {
                cleanupEchoListeners(user.id);
            };
        }
    }, [user]);

    if (!user) {
        return null;
    }

    // Render methods - using regular functions to avoid conditional hook issues
    const renderMenuItem = (item: MenuItem) => {
        const IconComponent = resolveIcon(item.icon);
        
        return (
            <SidebarMenuItem key={`${item.href}-${item.label}`}>
                <SidebarMenuButton asChild>
                    <Link 
                        href={item.href}
                        className={`flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-700 ${
                            item.isActive ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : ''
                        }`}
                    >
                        <IconComponent className="w-4 h-4 flex-shrink-0" />
                        <span className="flex-1 truncate">{item.label}</span>
                        {item.badge && item.badge > 0 && (
                            <Badge 
                                variant="default" 
                                className="bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-0.5 rounded-full font-medium min-w-[1.25rem] h-5 flex items-center justify-center"
                            >
                                {item.badge > 99 ? '99+' : item.badge}
                            </Badge>
                        )}
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        );
    };

    const renderMenuGroup = (group: MenuGroup) => (
        <SidebarGroup key={group.label}>
            <SidebarGroupLabel className="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide px-3">
                {group.label}
            </SidebarGroupLabel>
            <SidebarGroupContent>
                <SidebarMenu>
                    {group.items.map(renderMenuItem)}
                </SidebarMenu>
            </SidebarGroupContent>
        </SidebarGroup>
    );

    return (
        <Sidebar collapsible="icon" variant="inset" className="border-r border-gray-200 dark:border-gray-700">
            <SidebarHeader className="border-b border-gray-200 dark:border-gray-700">
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" className="flex items-center space-x-3 p-2">
                                <div className="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                    {roleEmoji}
                                </div>
                                <div className="flex flex-col">
                                    <span className="font-bold text-gray-900 dark:text-white">EduPlatform</span>
                                    <span className="text-xs text-gray-500 dark:text-gray-400">{roleTitle}</span>
                                </div>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent className="px-2 py-4 space-y-2">
                {loading && (
                    <div className="flex items-center justify-center py-8">
                        <div className="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <span className="ml-2 text-sm text-gray-500 dark:text-gray-400">Loading menu...</span>
                    </div>
                )}

                {error && (
                    <div className="px-3 py-2 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <p className="font-medium">Failed to load menu</p>
                        <p className="text-xs opacity-75">{error}</p>
                        <button 
                            onClick={fetchMenuData}
                            className="mt-1 text-xs underline hover:no-underline"
                        >
                            Retry
                        </button>
                    </div>
                )}

                {isEmpty && !error && (
                    <div className="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        <LucideIcons.Menu className="w-8 h-8 mx-auto mb-2 opacity-50" />
                        <p>No menu items available</p>
                    </div>
                )}

                {hasMenuItems && menuItems.map(renderMenuGroup)}
            </SidebarContent>

            <SidebarFooter className="border-t border-gray-200 dark:border-gray-700 p-2">
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}

// Export for global notification events
export function triggerSidebarUpdate() {
    window.dispatchEvent(new CustomEvent('notification-received'));
}