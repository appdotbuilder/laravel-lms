import { useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { SharedData } from '@/types';
import { initializeEcho, setupUserEchoListeners, cleanupEchoListeners } from '@/lib/echo';

/**
 * Custom hook for Laravel Echo integration
 * This hook initializes Echo and sets up user-specific listeners
 */
export function useEcho() {
    const { auth } = usePage<SharedData>().props;
    const user = auth.user;

    useEffect(() => {
        // Initialize Echo when component mounts
        initializeEcho();

        // Setup user listeners if user is authenticated
        if (user?.id) {
            setupUserEchoListeners(user.id);
        }

        // Cleanup on unmount
        return () => {
            if (user?.id) {
                cleanupEchoListeners(user.id);
            }
        };
    }, [user?.id]);

    return {
        echo: window.Echo,
        userId: user?.id,
    };
}

/**
 * Hook for broadcasting custom events
 * Useful for triggering sidebar updates from components
 */
export function useBroadcast() {
    const broadcastNotification = (type: string, data?: unknown) => {
        window.dispatchEvent(new CustomEvent('notification-received', {
            detail: {
                type,
                data,
                timestamp: new Date().toISOString(),
            }
        }));
    };

    const triggerSidebarUpdate = () => {
        broadcastNotification('sidebar-update');
    };

    return {
        broadcastNotification,
        triggerSidebarUpdate,
    };
}