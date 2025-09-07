<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseReview;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class SidebarMenuService
{
    /**
     * Cache duration in seconds (5 minutes).
     */
    private const CACHE_DURATION = 300;

    /**
     * Get menu items for the authenticated user.
     *
     * @return array
     */
    public function getMenuItems(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        $cacheKey = "sidebar_menu_user_{$user->id}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($user) {
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
     * @param User $user
     * @return array
     */
    protected function buildAdminMenu(User $user): array
    {
        return [
            [
                'type' => 'group',
                'label' => 'Administration',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Dashboard',
                        'href' => '/admin/dashboard',
                        'icon' => 'LayoutDashboard',
                        'isActive' => request()->is('admin/dashboard*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Approval Queue',
                        'href' => '/admin/approvals',
                        'icon' => 'CheckCircle',
                        'badge' => $this->getPendingCourseCount(),
                        'isActive' => request()->is('admin/approvals*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Instructors',
                        'href' => '/admin/instructors',
                        'icon' => 'Users',
                        'badge' => $this->getPendingInstructorCount(),
                        'isActive' => request()->is('admin/instructors*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Payouts',
                        'href' => '/admin/payouts',
                        'icon' => 'DollarSign',
                        'badge' => $this->getPendingPayoutCount(),
                        'isActive' => request()->is('admin/payouts*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'label' => 'System',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Users',
                        'href' => '/admin/users',
                        'icon' => 'UserCheck',
                        'isActive' => request()->is('admin/users*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Courses',
                        'href' => '/admin/courses',
                        'icon' => 'BookOpen',
                        'isActive' => request()->is('admin/courses*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Categories',
                        'href' => '/admin/categories',
                        'icon' => 'Tags',
                        'isActive' => request()->is('admin/categories*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Analytics',
                        'href' => '/admin/analytics',
                        'icon' => 'BarChart3',
                        'isActive' => request()->is('admin/analytics*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'label' => 'Personal',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Notifications',
                        'href' => '/notifications',
                        'icon' => 'Bell',
                        'badge' => $this->getUnreadNotificationCount($user),
                        'isActive' => request()->is('notifications*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Settings',
                        'href' => '/settings',
                        'icon' => 'Settings',
                        'isActive' => request()->is('settings*')
                    ]
                ]
            ]
        ];
    }

    /**
     * Build instructor menu structure.
     *
     * @param User $user
     * @return array
     */
    protected function buildInstructorMenu(User $user): array
    {
        return [
            [
                'type' => 'group',
                'label' => 'Teaching',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Dashboard',
                        'href' => '/instructor/dashboard',
                        'icon' => 'LayoutDashboard',
                        'isActive' => request()->is('instructor/dashboard*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'My Courses',
                        'href' => '/instructor/courses',
                        'icon' => 'BookOpen',
                        'isActive' => request()->is('instructor/courses*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Create Course',
                        'href' => '/instructor/courses/create',
                        'icon' => 'Plus',
                        'isActive' => request()->is('instructor/courses/create*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Students',
                        'href' => '/instructor/students',
                        'icon' => 'GraduationCap',
                        'isActive' => request()->is('instructor/students*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'label' => 'Engagement',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Reviews',
                        'href' => '/instructor/reviews',
                        'icon' => 'Star',
                        'badge' => $this->getUnreadReviewCount($user),
                        'isActive' => request()->is('instructor/reviews*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Messages',
                        'href' => '/instructor/messages',
                        'icon' => 'MessageCircle',
                        'badge' => $this->getUnreadMessageCount($user),
                        'isActive' => request()->is('instructor/messages*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Analytics',
                        'href' => '/instructor/analytics',
                        'icon' => 'TrendingUp',
                        'isActive' => request()->is('instructor/analytics*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'label' => 'Personal',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Earnings',
                        'href' => '/instructor/earnings',
                        'icon' => 'DollarSign',
                        'isActive' => request()->is('instructor/earnings*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Notifications',
                        'href' => '/notifications',
                        'icon' => 'Bell',
                        'badge' => $this->getUnreadNotificationCount($user),
                        'isActive' => request()->is('notifications*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Settings',
                        'href' => '/settings',
                        'icon' => 'Settings',
                        'isActive' => request()->is('settings*')
                    ]
                ]
            ]
        ];
    }

    /**
     * Build student menu structure.
     *
     * @param User $user
     * @return array
     */
    protected function buildStudentMenu(User $user): array
    {
        return [
            [
                'type' => 'group',
                'label' => 'Learning',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Dashboard',
                        'href' => '/student/dashboard',
                        'icon' => 'LayoutDashboard',
                        'isActive' => request()->is('student/dashboard*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Continue Learning',
                        'href' => '/student/courses',
                        'icon' => 'Play',
                        'badge' => $this->getIncompleteCourseCount($user),
                        'isActive' => request()->is('student/courses*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Browse Courses',
                        'href' => '/courses',
                        'icon' => 'Search',
                        'isActive' => request()->is('courses*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'My Certificates',
                        'href' => '/student/certificates',
                        'icon' => 'Award',
                        'isActive' => request()->is('student/certificates*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'label' => 'Community',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Discussion Forum',
                        'href' => '/forum',
                        'icon' => 'MessageSquare',
                        'badge' => $this->getUnreadForumReplies($user),
                        'isActive' => request()->is('forum*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Study Groups',
                        'href' => '/student/groups',
                        'icon' => 'Users',
                        'isActive' => request()->is('student/groups*')
                    ]
                ]
            ],
            [
                'type' => 'group',
                'label' => 'Personal',
                'items' => [
                    [
                        'type' => 'item',
                        'label' => 'Wishlist',
                        'href' => '/student/wishlist',
                        'icon' => 'Heart',
                        'badge' => $this->getWishlistCount($user),
                        'isActive' => request()->is('student/wishlist*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Purchase History',
                        'href' => '/student/purchases',
                        'icon' => 'ShoppingBag',
                        'isActive' => request()->is('student/purchases*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Notifications',
                        'href' => '/notifications',
                        'icon' => 'Bell',
                        'badge' => $this->getUnreadNotificationCount($user),
                        'isActive' => request()->is('notifications*')
                    ],
                    [
                        'type' => 'item',
                        'label' => 'Settings',
                        'href' => '/settings',
                        'icon' => 'Settings',
                        'isActive' => request()->is('settings*')
                    ]
                ]
            ]
        ];
    }

    /**
     * Get count of pending course approvals.
     *
     * @return int
     */
    protected function getPendingCourseCount(): int
    {
        return Course::where('status', 'pending_approval')->count();
    }

    /**
     * Get count of pending instructor approvals.
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
     * Get count of unread reviews for an instructor.
     *
     * @param User $user
     * @return int
     */
    protected function getUnreadReviewCount(User $user): int
    {
        return CourseReview::whereHas('course', function ($query) use ($user) {
            $query->where('instructor_id', $user->id);
        })->where('approved', false)->count();
    }

    /**
     * Get count of incomplete courses for a student.
     *
     * @param User $user
     * @return int
     */
    protected function getIncompleteCourseCount(User $user): int
    {
        return Enrollment::where('student_id', $user->id)
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get count of unread forum replies for a user.
     *
     * @param User $user
     * @return int
     */
    protected function getUnreadForumReplies(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('type', 'forum_reply')
            ->where('read', false)
            ->count();
    }

    /**
     * Get count of pending payouts (placeholder).
     *
     * @return int
     */
    protected function getPendingPayoutCount(): int
    {
        // Placeholder - implement when payout system is ready
        return 0;
    }

    /**
     * Get count of unread messages for an instructor (placeholder).
     *
     * @param User $user
     * @return int
     */
    protected function getUnreadMessageCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('type', 'direct_message')
            ->where('read', false)
            ->count();
    }

    /**
     * Get count of wishlist items for a student (placeholder).
     *
     * @param User $user
     * @return int
     */
    protected function getWishlistCount(User $user): int
    {
        // Placeholder - implement when wishlist system is ready
        return 0;
    }

    /**
     * Get count of unread notifications for a user.
     *
     * @param User $user
     * @return int
     */
    protected function getUnreadNotificationCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('read', false)
            ->count();
    }

    /**
     * Clear the sidebar menu cache for a specific user.
     *
     * @param User $user
     * @return void
     */
    public function clearCache(User $user): void
    {
        $cacheKey = "sidebar_menu_user_{$user->id}";
        Cache::forget($cacheKey);
    }
}