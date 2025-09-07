<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the role-based dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Route to appropriate dashboard based on user role
        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard($user);
            case 'instructor':
                return $this->instructorDashboard($user);
            case 'student':
                return $this->studentDashboard($user);
            default:
                return $this->studentDashboard($user);
        }
    }

    /**
     * Display the admin dashboard.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response
     */
    protected function adminDashboard($user)
    {
        return Inertia::render('admin/dashboard', [
            'stats' => [
                'totalRevenue' => 125000,
                'revenueGrowth' => 12.5,
                'newStudents' => 48,
                'studentGrowth' => 8.3,
                'coursesPublished' => 12,
                'coursesPending' => 3,
                'totalCourses' => 15,
            ],
            'topCourses' => [
                ['title' => 'React Masterclass', 'revenue' => 25000, 'students' => 250],
                ['title' => 'Laravel Advanced', 'revenue' => 18000, 'students' => 180],
                ['title' => 'Vue.js Complete', 'revenue' => 15000, 'students' => 200],
                ['title' => 'Node.js Backend', 'revenue' => 12000, 'students' => 120],
                ['title' => 'Python Basics', 'revenue' => 8000, 'students' => 160],
            ],
            'recentActivities' => [
                ['type' => 'course_published', 'message' => 'React Masterclass was published', 'time' => '2 hours ago'],
                ['type' => 'instructor_approved', 'message' => 'John Smith was approved as instructor', 'time' => '4 hours ago'],
                ['type' => 'payout_processed', 'message' => 'Monthly payout of $15,000 processed', 'time' => '6 hours ago'],
                ['type' => 'course_submitted', 'message' => 'Vue.js Advanced submitted for review', 'time' => '1 day ago'],
            ],
        ]);
    }

    /**
     * Display the instructor dashboard.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response
     */
    protected function instructorDashboard($user)
    {
        return Inertia::render('instructor/dashboard', [
            'stats' => [
                'totalEarnings' => 8500,
                'monthlyEarnings' => 1250,
                'earningsGrowth' => 15.2,
                'activeStudents' => 124,
                'studentsGrowth' => 5.8,
                'averageRating' => 4.7,
                'totalReviews' => 89,
            ],
            'courses' => [
                [
                    'id' => 1,
                    'title' => 'React Masterclass',
                    'status' => 'published',
                    'students' => 85,
                    'progress' => 100,
                    'revenue' => 4250,
                    'rating' => 4.8,
                ],
                [
                    'id' => 2,
                    'title' => 'Advanced JavaScript',
                    'status' => 'draft',
                    'students' => 0,
                    'progress' => 65,
                    'revenue' => 0,
                    'rating' => 0,
                ],
                [
                    'id' => 3,
                    'title' => 'Node.js Backend',
                    'status' => 'published',
                    'students' => 39,
                    'progress' => 100,
                    'revenue' => 1950,
                    'rating' => 4.6,
                ],
            ],
            'revenueData' => [
                ['month' => 'Jan', 'revenue' => 800],
                ['month' => 'Feb', 'revenue' => 1200],
                ['month' => 'Mar', 'revenue' => 950],
                ['month' => 'Apr', 'revenue' => 1400],
                ['month' => 'May', 'revenue' => 1100],
                ['month' => 'Jun', 'revenue' => 1250],
            ],
        ]);
    }

    /**
     * Display the student dashboard.
     *
     * @param  \App\Models\User  $user
     * @return \Inertia\Response
     */
    protected function studentDashboard($user)
    {
        return Inertia::render('student/dashboard', [
            'stats' => [
                'activeCourses' => 3,
                'completedCourses' => 7,
                'totalHoursLearned' => 142,
                'currentStreak' => 7,
                'averageProgress' => 68,
            ],
            'activeCourses' => [
                [
                    'id' => 1,
                    'title' => 'React Masterclass',
                    'instructor' => 'Sarah Johnson',
                    'progress' => 75,
                    'nextLesson' => 'State Management with Redux',
                    'thumbnail' => '/images/react-course.jpg',
                    'timeLeft' => '2.5 hours',
                ],
                [
                    'id' => 2,
                    'title' => 'Python for Beginners',
                    'instructor' => 'Mike Chen',
                    'progress' => 45,
                    'nextLesson' => 'Working with Lists',
                    'thumbnail' => '/images/python-course.jpg',
                    'timeLeft' => '4.2 hours',
                ],
                [
                    'id' => 3,
                    'title' => 'UI/UX Design Fundamentals',
                    'instructor' => 'Emma Davis',
                    'progress' => 20,
                    'nextLesson' => 'Color Theory',
                    'thumbnail' => '/images/design-course.jpg',
                    'timeLeft' => '8.1 hours',
                ],
            ],
            'recommendedCourses' => [
                [
                    'id' => 4,
                    'title' => 'Advanced React Patterns',
                    'instructor' => 'Sarah Johnson',
                    'rating' => 4.9,
                    'students' => 1250,
                    'price' => 79.99,
                    'thumbnail' => '/images/advanced-react.jpg',
                ],
                [
                    'id' => 5,
                    'title' => 'Machine Learning Basics',
                    'instructor' => 'Dr. Alex Kumar',
                    'rating' => 4.7,
                    'students' => 890,
                    'price' => 99.99,
                    'thumbnail' => '/images/ml-course.jpg',
                ],
                [
                    'id' => 6,
                    'title' => 'Digital Marketing Strategy',
                    'instructor' => 'Lisa Wong',
                    'rating' => 4.6,
                    'students' => 650,
                    'price' => 59.99,
                    'thumbnail' => '/images/marketing-course.jpg',
                ],
            ],
            'recentAchievements' => [
                ['title' => 'First Course Completed', 'icon' => '🎉', 'date' => '2 days ago'],
                ['title' => '7-Day Learning Streak', 'icon' => '🔥', 'date' => 'Today'],
                ['title' => 'React Expert', 'icon' => '⚛️', 'date' => '1 week ago'],
            ],
        ]);
    }
}