import React, { useState } from 'react';
import { usePage } from '@inertiajs/react';
import { type SharedData } from '@/types';
import { useBroadcast } from '@/hooks/use-echo';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Bell, RefreshCw, Users, BookOpen, MessageCircle, DollarSign } from 'lucide-react';

/**
 * Demo component to showcase the dynamic sidebar functionality
 * This component demonstrates how to trigger real-time sidebar updates
 */
export function SidebarDemo() {
    const { auth } = usePage<SharedData>().props;
    const user = auth.user;
    const { broadcastNotification, triggerSidebarUpdate } = useBroadcast();
    const [isUpdating, setIsUpdating] = useState(false);

    if (!user) {
        return null;
    }

    const handleTriggerUpdate = async () => {
        setIsUpdating(true);
        
        // Simulate a notification event
        broadcastNotification('demo-notification', {
            type: 'badge-update',
            message: 'Sidebar badges updated via demo',
            timestamp: new Date().toISOString(),
        });
        
        // Trigger sidebar refresh
        triggerSidebarUpdate();
        
        // Reset loading state
        setTimeout(() => setIsUpdating(false), 1000);
    };

    const simulateNotifications = {
        admin: [
            { type: 'course_approval', icon: BookOpen, label: 'Course Approval', count: '3' },
            { type: 'instructor_approval', icon: Users, label: 'Instructor Approval', count: '2' },
            { type: 'payout_request', icon: DollarSign, label: 'Payout Requests', count: '1' },
        ],
        instructor: [
            { type: 'course_review', icon: Bell, label: 'New Reviews', count: '5' },
            { type: 'forum_reply', icon: MessageCircle, label: 'Q&A Messages', count: '12' },
            { type: 'enrollment', icon: Users, label: 'New Enrollments', count: '8' },
        ],
        student: [
            { type: 'course_recommendation', icon: BookOpen, label: 'Recommended Courses', count: '4' },
            { type: 'forum_reply', icon: MessageCircle, label: 'Forum Replies', count: '2' },
            { type: 'achievement', icon: Bell, label: 'New Achievements', count: '1' },
        ]
    };

    const currentNotifications = simulateNotifications[user.role as keyof typeof simulateNotifications] || [];

    return (
        <Card className="w-full max-w-2xl mx-auto">
            <CardHeader>
                <CardTitle className="flex items-center space-x-2">
                    <RefreshCw className="w-5 h-5" />
                    <span>Dynamic Sidebar Demo</span>
                </CardTitle>
                <p className="text-sm text-muted-foreground">
                    This demo shows how the sidebar updates in real-time with badge notifications.
                    Your current role: <Badge variant="outline" className="ml-1">{user.role}</Badge>
                </p>
            </CardHeader>
            
            <CardContent className="space-y-6">
                {/* Current Notification Types */}
                <div>
                    <h4 className="text-sm font-medium mb-3">Active Badge Types for {user.role}:</h4>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        {currentNotifications.map((notification, index) => (
                            <div 
                                key={index} 
                                className="flex items-center space-x-2 p-3 bg-muted/50 rounded-lg"
                            >
                                <notification.icon className="w-4 h-4 text-muted-foreground" />
                                <span className="text-sm flex-1">{notification.label}</span>
                                <Badge variant="secondary" className="text-xs">
                                    {notification.count}
                                </Badge>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Demo Instructions */}
                <div className="bg-blue-50 dark:bg-blue-950/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                    <h4 className="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">
                        How the Dynamic Sidebar Works:
                    </h4>
                    <ul className="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li>• Sidebar fetches menu data from <code className="bg-blue-100 dark:bg-blue-900/50 px-1 rounded">/api/sidebar-menu</code></li>
                        <li>• Badge counts are calculated server-side based on real database data</li>
                        <li>• Redis caching improves performance (5-minute cache)</li>
                        <li>• Laravel Echo enables real-time updates when notifications arrive</li>
                        <li>• Custom events can trigger manual sidebar refreshes</li>
                    </ul>
                </div>

                {/* Demo Controls */}
                <div className="pt-4 border-t">
                    <div className="flex items-center justify-between">
                        <div>
                            <h4 className="text-sm font-medium">Test Real-Time Updates</h4>
                            <p className="text-xs text-muted-foreground">
                                Click to simulate a notification and trigger sidebar refresh
                            </p>
                        </div>
                        <Button 
                            onClick={handleTriggerUpdate} 
                            disabled={isUpdating}
                            className="ml-4"
                        >
                            {isUpdating ? (
                                <>
                                    <RefreshCw className="w-4 h-4 mr-2 animate-spin" />
                                    Updating...
                                </>
                            ) : (
                                <>
                                    <Bell className="w-4 h-4 mr-2" />
                                    Trigger Update
                                </>
                            )}
                        </Button>
                    </div>
                </div>

                {/* Implementation Notes */}
                <div className="bg-muted/50 p-4 rounded-lg">
                    <h4 className="text-sm font-medium mb-2">Implementation Features:</h4>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-muted-foreground">
                        <div>
                            <strong className="text-foreground">Backend:</strong>
                            <ul className="mt-1 space-y-0.5">
                                <li>• Laravel service-based architecture</li>
                                <li>• Database-driven badge calculations</li>
                                <li>• Polymorphic notification system</li>
                                <li>• Redis caching for performance</li>
                            </ul>
                        </div>
                        <div>
                            <strong className="text-foreground">Frontend:</strong>
                            <ul className="mt-1 space-y-0.5">
                                <li>• Vue 3 Composition API pattern</li>
                                <li>• Real-time Laravel Echo integration</li>
                                <li>• Dynamic icon resolution</li>
                                <li>• Responsive badge system</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}