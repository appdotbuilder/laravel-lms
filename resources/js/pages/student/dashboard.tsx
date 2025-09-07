import React from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { SidebarDemo } from '@/components/sidebar-demo';

interface Props {
    stats: {
        activeCourses: number;
        completedCourses: number;
        totalHoursLearned: number;
        currentStreak: number;
        averageProgress: number;
    };
    activeCourses: Array<{
        id: number;
        title: string;
        instructor: string;
        progress: number;
        nextLesson: string;
        thumbnail: string;
        timeLeft: string;
    }>;
    recommendedCourses: Array<{
        id: number;
        title: string;
        instructor: string;
        rating: number;
        students: number;
        price: number;
        thumbnail: string;
    }>;
    recentAchievements: Array<{
        title: string;
        icon: string;
        date: string;
    }>;
    [key: string]: unknown;
}

export default function StudentDashboard({ stats, activeCourses, recommendedCourses, recentAchievements }: Props) {
    return (
        <AppShell>
            <Head title="My Learning Dashboard" />
            
            <div className="space-y-8">
                {/* Hero Section */}
                <div className="bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg p-6">
                    <h1 className="text-3xl font-bold mb-2">🎯 Welcome back to your learning journey!</h1>
                    <p className="text-purple-100 mb-4">You're on a {stats.currentStreak}-day learning streak! 🔥</p>
                    {activeCourses.length > 0 && (
                        <div className="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                            <p className="text-sm mb-2">Continue your journey in</p>
                            <h3 className="font-semibold text-lg">{activeCourses[0].title}</h3>
                            <p className="text-sm text-purple-100 mb-3">Next: {activeCourses[0].nextLesson}</p>
                            <button className="bg-white text-purple-600 px-6 py-2 rounded-lg font-medium hover:bg-purple-50 transition-colors">
                                Resume Learning
                            </button>
                        </div>
                    )}
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Active Courses</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.activeCourses}</p>
                            </div>
                            <div className="text-2xl">📚</div>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.completedCourses}</p>
                            </div>
                            <div className="text-2xl">✅</div>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Hours Learned</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.totalHoursLearned}</p>
                            </div>
                            <div className="text-2xl">⏱️</div>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Learning Streak</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.currentStreak}</p>
                            </div>
                            <div className="text-2xl">🔥</div>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Progress</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.averageProgress}%</p>
                            </div>
                            <div className="text-2xl">📊</div>
                        </div>
                    </div>
                </div>

                {/* Active Courses */}
                {activeCourses.length > 0 && (
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-6">📖 Continue Learning</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {activeCourses.map((course) => (
                                <div key={course.id} className="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div className="aspect-video bg-gray-200 dark:bg-gray-600 rounded-lg mb-4 flex items-center justify-center">
                                        <span className="text-4xl">🎥</span>
                                    </div>
                                    <h3 className="font-semibold text-gray-900 dark:text-white mb-2">{course.title}</h3>
                                    <p className="text-sm text-gray-600 dark:text-gray-400 mb-3">by {course.instructor}</p>
                                    
                                    {/* Progress Ring */}
                                    <div className="flex items-center justify-between mb-3">
                                        <div className="flex items-center space-x-2">
                                            <div className="relative w-8 h-8">
                                                <svg className="w-8 h-8 transform -rotate-90" viewBox="0 0 36 36">
                                                    <path
                                                        className="text-gray-300 dark:text-gray-600"
                                                        stroke="currentColor"
                                                        strokeWidth="3"
                                                        fill="none"
                                                        d="M18 2.0845 A 15.9155 15.9155 0 0 1 18 33.9155 A 15.9155 15.9155 0 0 1 18 2.0845"
                                                    />
                                                    <path
                                                        className="text-blue-600"
                                                        stroke="currentColor"
                                                        strokeWidth="3"
                                                        fill="none"
                                                        strokeDasharray={`${course.progress}, 100`}
                                                        d="M18 2.0845 A 15.9155 15.9155 0 0 1 18 33.9155 A 15.9155 15.9155 0 0 1 18 2.0845"
                                                    />
                                                </svg>
                                                <div className="absolute inset-0 flex items-center justify-center">
                                                    <span className="text-xs font-medium text-gray-900 dark:text-white">
                                                        {course.progress}%
                                                    </span>
                                                </div>
                                            </div>
                                            <span className="text-sm text-gray-600 dark:text-gray-400">{course.timeLeft} left</span>
                                        </div>
                                    </div>
                                    
                                    <p className="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        <strong>Next:</strong> {course.nextLesson}
                                    </p>
                                    
                                    <button className="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-colors">
                                        Continue Watching
                                    </button>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Recommended Courses */}
                    <div className="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-6">🎯 Recommended for You</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {recommendedCourses.map((course) => (
                                <div key={course.id} className="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div className="aspect-video bg-gray-200 dark:bg-gray-600 rounded-lg mb-4 flex items-center justify-center">
                                        <span className="text-4xl">🎓</span>
                                    </div>
                                    <h3 className="font-semibold text-gray-900 dark:text-white mb-2">{course.title}</h3>
                                    <p className="text-sm text-gray-600 dark:text-gray-400 mb-2">by {course.instructor}</p>
                                    
                                    <div className="flex items-center justify-between mb-3">
                                        <div className="flex items-center space-x-2">
                                            <span className="text-sm text-yellow-500">⭐ {course.rating}</span>
                                            <span className="text-sm text-gray-500 dark:text-gray-400">
                                                ({course.students.toLocaleString()})
                                            </span>
                                        </div>
                                        <span className="text-lg font-bold text-green-600 dark:text-green-400">
                                            ${course.price}
                                        </span>
                                    </div>
                                    
                                    <button className="w-full bg-gray-800 hover:bg-gray-900 text-white py-2 rounded-lg font-medium transition-colors dark:bg-gray-600 dark:hover:bg-gray-500">
                                        View Course
                                    </button>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Recent Achievements */}
                    <div className="space-y-6">
                        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">🏆 Recent Achievements</h2>
                            <div className="space-y-4">
                                {recentAchievements.map((achievement, index) => (
                                    <div key={index} className="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div className="text-2xl">{achievement.icon}</div>
                                        <div className="flex-1">
                                            <p className="text-sm font-medium text-gray-900 dark:text-white">
                                                {achievement.title}
                                            </p>
                                            <p className="text-xs text-gray-500 dark:text-gray-400">{achievement.date}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Learning Streak Info */}
                        <div className="bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg p-6 border border-orange-200 dark:border-orange-700">
                            <div className="text-center">
                                <div className="text-4xl mb-2">🔥</div>
                                <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {stats.currentStreak}-Day Streak!
                                </h3>
                                <p className="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    You're on fire! Keep learning to maintain your streak.
                                </p>
                                <div className="bg-white dark:bg-gray-800 rounded-lg p-3">
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mb-1">Daily Goal Progress</p>
                                    <div className="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div className="bg-orange-500 h-2 rounded-full" style={{ width: '75%' }}></div>
                                    </div>
                                    <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">15 min left today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Dynamic Sidebar Demo */}
                <div>
                    <SidebarDemo />
                </div>
            </div>
        </AppShell>
    );
}