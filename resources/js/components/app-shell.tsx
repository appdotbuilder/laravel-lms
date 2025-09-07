import { SidebarProvider, SidebarInset } from '@/components/ui/sidebar';
import { DynamicSidebar } from '@/components/dynamic-sidebar';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

interface AppShellProps {
    children: React.ReactNode;
    variant?: 'header' | 'sidebar';
}

export function AppShell({ children, variant = 'sidebar' }: AppShellProps) {
    const isOpen = usePage<SharedData>().props.sidebarOpen;
    const { auth } = usePage<SharedData>().props;

    if (variant === 'header' || !auth.user) {
        return <div className="flex min-h-screen w-full flex-col">{children}</div>;
    }

    return (
        <SidebarProvider defaultOpen={isOpen}>
            <DynamicSidebar />
            <SidebarInset>
                <div className="flex flex-1 flex-col gap-4 p-4 pt-6">
                    {children}
                </div>
            </SidebarInset>
        </SidebarProvider>
    );
}
