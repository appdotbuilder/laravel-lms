import React from 'react';
import { usePage } from '@inertiajs/react';
import { type SharedData } from '@/types';
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
import { 
    LayoutDashboard,
    BookOpen,
    Users,
    GraduationCap,
    DollarSign,
    BarChart3,
    Settings,
    Bell,
    Award,
    MessageCircle,
    ShoppingCart,
    Star,
    TrendingUp,
    UserCheck,
    RefreshCw,
    Megaphone,
    Shield,
    Database,
    CreditCard,
    Search,
    Play,
    Heart,
    HelpCircle,
    Trophy
} from 'lucide-react';

interface NavigationItem {
    title: string;
    icon: React.ComponentType<{ className?: string }>;
    href: string;
    badge: string | null;
}



export function RoleBasedSidebar() {
    const { auth } = usePage<SharedData>().props;
    const user = auth.user;

    if (!user) {
        return null;
    }

    const getNavigationItems = () => {
        switch (user.role) {
            case 'admin':
                return {
                    main: [
                        {
                            title: 'Dashboard',
                            icon: LayoutDashboard,
                            href: '/dashboard',
                            badge: null,
                        },
                    ],
                    courses: [
                        {
                            title: 'All Courses',
                            icon: BookOpen,
                            href: '/admin/courses',
                            badge: null,
                        },
                        {
                            title: 'Add New Course',
                            icon: RefreshCw,
                            href: '/admin/courses/create',
                            badge: null,
                        },
                        {
                            title: 'Categories & Tags',
                            icon: Star,
                            href: '/admin/categories',
                            badge: null,
                        },
                        {
                            title: 'Approval Queue',
                            icon: UserCheck,
                            href: '/admin/courses/pending',
                            badge: '3',
                        },
                    ],
                    users: [
                        {
                            title: 'All Instructors',
                            icon: GraduationCap,
                            href: '/admin/instructors',
                            badge: null,
                        },
                        {
                            title: 'Pending Approval',
                            icon: Users,
                            href: '/admin/instructors/pending',
                            badge: '2',
                        },
                        {
                            title: 'All Students',
                            icon: Users,
                            href: '/admin/students',
                            badge: null,
                        },
                        {
                            title: 'Payout Management',
                            icon: DollarSign,
                            href: '/admin/payouts',
                            badge: null,
                        },
                    ],
                    analytics: [
                        {
                            title: 'Revenue Reports',
                            icon: TrendingUp,
                            href: '/admin/reports/revenue',
                            badge: null,
                        },
                        {
                            title: 'Student Growth',
                            icon: BarChart3,
                            href: '/admin/reports/students',
                            badge: null,
                        },
                        {
                            title: 'Marketing',
                            icon: Megaphone,
                            href: '/admin/marketing',
                            badge: null,
                        },
                    ],
                    system: [
                        {
                            title: 'Settings',
                            icon: Settings,
                            href: '/admin/settings',
                            badge: null,
                        },
                        {
                            title: 'Audit Logs',
                            icon: Shield,
                            href: '/admin/audit',
                            badge: null,
                        },
                        {
                            title: 'Backup & Restore',
                            icon: Database,
                            href: '/admin/backup',
                            badge: null,
                        },
                    ],
                };

            case 'instructor':
                return {
                    main: [
                        {
                            title: 'Dashboard',
                            icon: LayoutDashboard,
                            href: '/dashboard',
                            badge: null,
                        },
                    ],
                    courses: [
                        {
                            title: 'My Courses',
                            icon: BookOpen,
                            href: '/instructor/courses',
                            badge: null,
                        },
                        {
                            title: 'Create New Course',
                            icon: RefreshCw,
                            href: '/instructor/courses/create',
                            badge: null,
                        },
                        {
                            title: 'Course Builder',
                            icon: Settings,
                            href: '/instructor/course-builder',
                            badge: null,
                        },
                        {
                            title: 'Student Progress',
                            icon: BarChart3,
                            href: '/instructor/student-progress',
                            badge: null,
                        },
                        {
                            title: 'Reviews',
                            icon: Star,
                            href: '/instructor/reviews',
                            badge: '5',
                        },
                    ],
                    earnings: [
                        {
                            title: 'Earnings Summary',
                            icon: DollarSign,
                            href: '/instructor/earnings',
                            badge: null,
                        },
                        {
                            title: 'Payout History',
                            icon: CreditCard,
                            href: '/instructor/payouts',
                            badge: null,
                        },
                        {
                            title: 'Request Payout',
                            icon: TrendingUp,
                            href: '/instructor/payout-request',
                            badge: null,
                        },
                    ],
                    students: [
                        {
                            title: 'My Students',
                            icon: Users,
                            href: '/instructor/students',
                            badge: null,
                        },
                        {
                            title: 'Q&A Forum',
                            icon: MessageCircle,
                            href: '/instructor/forum',
                            badge: '12',
                        },
                        {
                            title: 'Send Message',
                            icon: Bell,
                            href: '/instructor/messages',
                            badge: null,
                        },
                    ],
                    profile: [
                        {
                            title: 'Profile & Bio',
                            icon: Settings,
                            href: '/instructor/profile',
                            badge: null,
                        },
                    ],
                };

            case 'student':
            default:
                return {
                    main: [
                        {
                            title: 'My Learning Path',
                            icon: LayoutDashboard,
                            href: '/dashboard',
                            badge: null,
                        },
                    ],
                    learning: [
                        {
                            title: 'Active Courses',
                            icon: Play,
                            href: '/student/courses/active',
                            badge: null,
                        },
                        {
                            title: 'Completed Courses',
                            icon: Award,
                            href: '/student/courses/completed',
                            badge: null,
                        },
                        {
                            title: 'Continue Learning',
                            icon: RefreshCw,
                            href: '/student/continue',
                            badge: null,
                        },
                        {
                            title: 'Certificates',
                            icon: Trophy,
                            href: '/student/certificates',
                            badge: null,
                        },
                    ],
                    discover: [
                        {
                            title: 'Browse All Courses',
                            icon: Search,
                            href: '/courses',
                            badge: null,
                        },
                        {
                            title: 'Recommended',
                            icon: Star,
                            href: '/student/recommended',
                            badge: '4',
                        },
                        {
                            title: 'Wishlist',
                            icon: Heart,
                            href: '/student/wishlist',
                            badge: null,
                        },
                    ],
                    community: [
                        {
                            title: 'My Questions',
                            icon: HelpCircle,
                            href: '/student/questions',
                            badge: null,
                        },
                        {
                            title: 'Discussion Forum',
                            icon: MessageCircle,
                            href: '/student/forum',
                            badge: '2',
                        },
                        {
                            title: 'Instructor Q&A',
                            icon: GraduationCap,
                            href: '/student/instructor-qa',
                            badge: null,
                        },
                    ],
                    account: [
                        {
                            title: 'Purchase History',
                            icon: ShoppingCart,
                            href: '/student/purchases',
                            badge: null,
                        },
                        {
                            title: 'Achievements',
                            icon: Trophy,
                            href: '/student/achievements',
                            badge: null,
                        },
                        {
                            title: 'Profile Settings',
                            icon: Settings,
                            href: '/student/profile',
                            badge: null,
                        },
                    ],
                };
        }
    };

    const navigationItems = getNavigationItems();

    const renderNavGroup = (title: string, items: NavigationItem[]) => (
        <SidebarGroup key={title}>
            <SidebarGroupLabel className="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                {title}
            </SidebarGroupLabel>
            <SidebarGroupContent>
                <SidebarMenu>
                    {items.map((item) => (
                        <SidebarMenuItem key={item.href}>
                            <SidebarMenuButton asChild>
                                <Link 
                                    href={item.href} 
                                    className="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-700"
                                >
                                    <item.icon className="w-4 h-4" />
                                    <span className="flex-1">{item.title}</span>
                                    {item.badge && (
                                        <span className="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full font-medium">
                                            {item.badge}
                                        </span>
                                    )}
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    ))}
                </SidebarMenu>
            </SidebarGroupContent>
        </SidebarGroup>
    );

    const getRoleEmoji = () => {
        switch (user.role) {
            case 'admin': return '🛡️';
            case 'instructor': return '👨‍🏫';
            case 'student': default: return '🎓';
        }
    };

    const getRoleTitle = () => {
        switch (user.role) {
            case 'admin': return 'Admin Panel';
            case 'instructor': return 'Instructor Hub';
            case 'student': default: return 'Student Portal';
        }
    };

    return (
        <Sidebar collapsible="icon" variant="inset" className="border-r border-gray-200 dark:border-gray-700">
            <SidebarHeader className="border-b border-gray-200 dark:border-gray-700">
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" className="flex items-center space-x-3 p-2">
                                <div className="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                    {getRoleEmoji()}
                                </div>
                                <div className="flex flex-col">
                                    <span className="font-bold text-gray-900 dark:text-white">EduPlatform</span>
                                    <span className="text-xs text-gray-500 dark:text-gray-400">{getRoleTitle()}</span>
                                </div>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent className="px-2 py-4">
                {Object.entries(navigationItems).map(([groupKey, items]) => 
                    renderNavGroup(
                        groupKey.charAt(0).toUpperCase() + groupKey.slice(1),
                        items as NavigationItem[]
                    )
                )}
            </SidebarContent>

            <SidebarFooter className="border-t border-gray-200 dark:border-gray-700 p-2">
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}