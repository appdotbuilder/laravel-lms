import React from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface Props {
    stats: {
        totalRevenue: number;
        revenueGrowth: number;
        newStudents: number;
        studentGrowth: number;
        coursesPublished: number;
        coursesPending: number;
        totalCourses: number;
    };
    topCourses: Array<{
        title: string;
        revenue: number;
        students: number;
    }>;
    recentActivities: Array<{
        type: string;
        message: string;
        time: string;
    }>;
    [key: string]: unknown;
}

export default function AdminDashboard({ stats, topCourses, recentActivities }: Props) {
    return (
        <AppShell>
            <Head title="Admin Dashboard" />
            
            <div className="space-y-8">
                {/* Header */}
                <div className="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg p-6">
                    <h1 className="text-3xl font-bold mb-2">📊 Admin Dashboard</h1>
                    <p className="text-blue-100">Complete overview of your learning platform</p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                    ${stats.totalRevenue.toLocaleString()}
                                </p>
                            </div>
                            <div className="text-2xl">💰</div>
                        </div>
                        <div className="mt-2">
                            <span className="text-green-600 text-sm font-medium">
                                +{stats.revenueGrowth}%
                            </span>
                            <span className="text-gray-500 text-sm ml-1">vs last month</span>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">New Students</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.newStudents}</p>
                            </div>
                            <div className="text-2xl">👥</div>
                        </div>
                        <div className="mt-2">
                            <span className="text-green-600 text-sm font-medium">
                                +{stats.studentGrowth}%
                            </span>
                            <span className="text-gray-500 text-sm ml-1">this month</span>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Published Courses</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.coursesPublished}</p>
                            </div>
                            <div className="text-2xl">📚</div>
                        </div>
                        <div className="mt-2">
                            <span className="text-gray-500 text-sm">
                                {stats.totalCourses} total courses
                            </span>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Approval</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.coursesPending}</p>
                            </div>
                            <div className="text-2xl">⏳</div>
                        </div>
                        <div className="mt-2">
                            <span className="text-orange-600 text-sm font-medium">Requires review</span>
                        </div>
                    </div>
                </div>

                {/* Main Content Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {/* Top Courses */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            🏆 Top 5 Courses by Revenue
                        </h2>
                        <div className="space-y-4">
                            {topCourses.map((course, index) => (
                                <div key={index} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div className="flex items-center space-x-3">
                                        <div className="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                            <span className="text-sm font-bold text-blue-600 dark:text-blue-300">
                                                {index + 1}
                                            </span>
                                        </div>
                                        <div>
                                            <p className="font-medium text-gray-900 dark:text-white">{course.title}</p>
                                            <p className="text-sm text-gray-500 dark:text-gray-400">
                                                {course.students} students
                                            </p>
                                        </div>
                                    </div>
                                    <div className="text-right">
                                        <p className="font-semibold text-green-600 dark:text-green-400">
                                            ${course.revenue.toLocaleString()}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Recent Activities */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            📋 Recent Activities
                        </h2>
                        <div className="space-y-4">
                            {recentActivities.map((activity, index) => (
                                <div key={index} className="flex items-start space-x-3">
                                    <div className="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mt-0.5">
                                        <div className="w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full"></div>
                                    </div>
                                    <div className="flex-1">
                                        <p className="text-sm text-gray-900 dark:text-white">{activity.message}</p>
                                        <p className="text-xs text-gray-500 dark:text-gray-400">{activity.time}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">⚡ Quick Actions</h2>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button className="p-4 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 rounded-lg border border-blue-200 dark:border-blue-700 transition-colors">
                            <div className="text-2xl mb-2">✅</div>
                            <div className="text-sm font-medium text-gray-900 dark:text-white">Approve Courses</div>
                            <div className="text-xs text-gray-500 dark:text-gray-400">{stats.coursesPending} pending</div>
                        </button>
                        
                        <button className="p-4 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/40 rounded-lg border border-green-200 dark:border-green-700 transition-colors">
                            <div className="text-2xl mb-2">🎯</div>
                            <div className="text-sm font-medium text-gray-900 dark:text-white">Send Promo</div>
                            <div className="text-xs text-gray-500 dark:text-gray-400">Marketing campaign</div>
                        </button>
                        
                        <button className="p-4 bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-900/40 rounded-lg border border-purple-200 dark:border-purple-700 transition-colors">
                            <div className="text-2xl mb-2">📊</div>
                            <div className="text-sm font-medium text-gray-900 dark:text-white">View Reports</div>
                            <div className="text-xs text-gray-500 dark:text-gray-400">Analytics dashboard</div>
                        </button>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}