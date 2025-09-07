<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseReview;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class SidebarMenuService
{
    /**
     * Get menu items for the authenticated user based on their role.
     *
     * @return array
     */
    public function getMenuItems(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        $cacheKey = "sidebar_menu_{$user->id}_{$user->role}";
        
        return Cache::remember($cacheKey, 300, function () use ($user) { // 5 minutes cache
            return match ($user->role) {
                'admin' => $this->buildAdminMenu($user),
                'instructor' => $this->buildInstructorMenu($user),
                'student' => $this->buildStudentMenu($user),
                default => []
            };
        });
    }

    /**
     * Build admin menu structure.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    protected function buildAdminMenu(User $user): array
    {
        return [
            [
                'type' => 'group',
                'title' => 'Admin Dashboard',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Overview',
                        'url' => '/admin/dashboard',
                        'icon' => 'LayoutDashboard',
                        'isActive' => request()->is('admin/dashboard')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Analytics',
                        'url' => '/admin/analytics',
                        'icon' => 'BarChart3',
                        'isActive' => request()->is('admin/analytics')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'Content Management',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Courses',
                        'url' => '/admin/courses',
                        'icon' => 'BookOpen',
                        'badge' => $this->getPendingCourseCount(),
                        'isActive' => request()->is('admin/courses*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Categories',
                        'url' => '/admin/categories',
                        'icon' => 'Folder',
                        'isActive' => request()->is('admin/categories*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Reviews',
                        'url' => '/admin/reviews',
                        'icon' => 'Star',
                        'isActive' => request()->is('admin/reviews*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'User Management',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'All Users',
                        'url' => '/admin/users',
                        'icon' => 'Users',
                        'isActive' => request()->is('admin/users*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Instructors',
                        'url' => '/admin/instructors',
                        'icon' => 'GraduationCap',
                        'badge' => $this->getPendingInstructorCount(),
                        'isActive' => request()->is('admin/instructors*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Students',
                        'url' => '/admin/students',
                        'icon' => 'UserCheck',
                        'isActive' => request()->is('admin/students*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'Finance',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Transactions',
                        'url' => '/admin/transactions',
                        'icon' => 'CreditCard',
                        'isActive' => request()->is('admin/transactions*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Payouts',
                        'url' => '/admin/payouts',
                        'icon' => 'DollarSign',
                        'badge' => $this->getPendingPayoutCount(),
                        'isActive' => request()->is('admin/payouts*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Revenue Reports',
                        'url' => '/admin/revenue',
                        'icon' => 'TrendingUp',
                        'isActive' => request()->is('admin/revenue*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'System',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Settings',
                        'url' => '/admin/settings',
                        'icon' => 'Settings',
                        'isActive' => request()->is('admin/settings*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Notifications',
                        'url' => '/admin/notifications',
                        'icon' => 'Bell',
                        'badge' => $this->getUnreadNotificationCount($user),
                        'isActive' => request()->is('admin/notifications*')
                    ]
                ]
            ]
        ];
    }

    /**
     * Build instructor menu structure.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    protected function buildInstructorMenu(User $user): array
    {
        return [
            [
                'type' => 'group',
                'title' => 'Instructor Dashboard',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Overview',
                        'url' => '/instructor/dashboard',
                        'icon' => 'LayoutDashboard',
                        'isActive' => request()->is('instructor/dashboard')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Analytics',
                        'url' => '/instructor/analytics',
                        'icon' => 'BarChart3',
                        'isActive' => request()->is('instructor/analytics')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'My Courses',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'All Courses',
                        'url' => '/instructor/courses',
                        'icon' => 'BookOpen',
                        'isActive' => request()->is('instructor/courses*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Create Course',
                        'url' => '/instructor/courses/create',
                        'icon' => 'Plus',
                        'isActive' => request()->is('instructor/courses/create')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Reviews',
                        'url' => '/instructor/reviews',
                        'icon' => 'Star',
                        'badge' => $this->getUnreadReviewCount($user),
                        'isActive' => request()->is('instructor/reviews*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'Students',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'My Students',
                        'url' => '/instructor/students',
                        'icon' => 'Users',
                        'isActive' => request()->is('instructor/students*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Messages',
                        'url' => '/instructor/messages',
                        'icon' => 'MessageCircle',
                        'badge' => $this->getUnreadMessageCount($user),
                        'isActive' => request()->is('instructor/messages*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'Earnings',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Revenue',
                        'url' => '/instructor/revenue',
                        'icon' => 'DollarSign',
                        'isActive' => request()->is('instructor/revenue*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Withdraw',
                        'url' => '/instructor/withdraw',
                        'icon' => 'CreditCard',
                        'isActive' => request()->is('instructor/withdraw*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'Account',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Profile',
                        'url' => '/instructor/profile',
                        'icon' => 'User',
                        'isActive' => request()->is('instructor/profile*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Notifications',
                        'url' => '/instructor/notifications',
                        'icon' => 'Bell',
                        'badge' => $this->getUnreadNotificationCount($user),
                        'isActive' => request()->is('instructor/notifications*')
                    ]
                ]
            ]
        ];
    }

    /**
     * Build student menu structure.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    protected function buildStudentMenu(User $user): array
    {
        return [
            [
                'type' => 'group',
                'title' => 'Student Dashboard',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Overview',
                        'url' => '/student/dashboard',
                        'icon' => 'LayoutDashboard',
                        'isActive' => request()->is('student/dashboard')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'My Progress',
                        'url' => '/student/progress',
                        'icon' => 'TrendingUp',
                        'isActive' => request()->is('student/progress*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'Learning',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'My Courses',
                        'url' => '/student/courses',
                        'icon' => 'BookOpen',
                        'isActive' => request()->is('student/courses*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Browse Courses',
                        'url' => '/courses',
                        'icon' => 'Search',
                        'isActive' => request()->is('courses*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Wishlist',
                        'url' => '/student/wishlist',
                        'icon' => 'Heart',
                        'badge' => $this->getWishlistCount($user),
                        'isActive' => request()->is('student/wishlist*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Certificates',
                        'url' => '/student/certificates',
                        'icon' => 'Award',
                        'isActive' => request()->is('student/certificates*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'Community',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Forums',
                        'url' => '/forums',
                        'icon' => 'MessageSquare',
                        'badge' => $this->getForumNotificationCount($user),
                        'isActive' => request()->is('forums*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Study Groups',
                        'url' => '/study-groups',
                        'icon' => 'Users',
                        'isActive' => request()->is('study-groups*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'title' => 'Account',
                'items' => [
                    [
                        'type' => 'item',
                        'title' => 'Profile',
                        'url' => '/student/profile',
                        'icon' => 'User',
                        'isActive' => request()->is('student/profile*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Purchase History',
                        'url' => '/student/purchases',
                        'icon' => 'CreditCard',
                        'isActive' => request()->is('student/purchases*')
                    ],
                    [
                        'type' => 'item',
                        'title' => 'Notifications',
                        'url' => '/student/notifications',
                        'icon' => 'Bell',
                        'badge' => $this->getUnreadNotificationCount($user),
                        'isActive' => request()->is('student/notifications*')
                    ]
                ]
            ]
        ];
    }

    /**
     * Get count of pending courses for approval.
     *
     * @return int
     */
    protected function getPendingCourseCount(): int
    {
        return Course::where('status', 'pending_approval')->count();
    }

    /**
     * Get count of pending instructors for approval.
     *
     * @return int
     */
    protected function getPendingInstructorCount(): int
    {
        return User::where('role', 'instructor')
                  ->where('is_approved', false)
                  ->count();
    }

    /**
     * Get count of pending payouts.
     *
     * @return int
     */
    protected function getPendingPayoutCount(): int
    {
        // This would be implemented based on your payout system
        // For now, returning 0 as placeholder
        return 0;
    }

    /**
     * Get count of unread notifications for a user.
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    protected function getUnreadNotificationCount(User $user): int
    {
        return $user->notifications()->where('read', false)->count();
    }

    /**
     * Get count of unread reviews for an instructor.
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    protected function getUnreadReviewCount(User $user): int
    {
        return CourseReview::whereHas('course', function ($query) use ($user) {
            $query->where('instructor_id', $user->id);
        })->where('approved', false)->count();
    }

    /**
     * Get count of unread messages for a user.
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    protected function getUnreadMessageCount(User $user): int
    {
        // This would be implemented based on your messaging system
        // For now, returning 0 as placeholder
        return 0;
    }

    /**
     * Get count of items in wishlist for a student.
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    protected function getWishlistCount(User $user): int
    {
        // This would be implemented based on your wishlist system
        // For now, returning 0 as placeholder
        return 0;
    }

    /**
     * Get count of forum notifications for a student.
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    protected function getForumNotificationCount(User $user): int
    {
        return $user->notifications()
                   ->where('type', 'forum_reply')
                   ->where('read', false)
                   ->count();
    }

    /**
     * Clear cached menu for a user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function clearCache(User $user): void
    {
        $cacheKey = "sidebar_menu_{$user->id}_{$user->role}";
        Cache::forget($cacheKey);
    }
}