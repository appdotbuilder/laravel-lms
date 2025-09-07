import React from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { SidebarDemo } from '@/components/sidebar-demo';

interface Props {
    stats: {
        totalEarnings: number;
        monthlyEarnings: number;
        earningsGrowth: number;
        activeStudents: number;
        studentsGrowth: number;
        averageRating: number;
        totalReviews: number;
    };
    courses: Array<{
        id: number;
        title: string;
        status: string;
        students: number;
        progress: number;
        revenue: number;
        rating: number;
    }>;
    revenueData: Array<{
        month: string;
        revenue: number;
    }>;
    [key: string]: unknown;
}

export default function InstructorDashboard({ stats, courses, revenueData }: Props) {
    const getStatusColor = (status: string) => {
        switch (status) {
            case 'published':
                return 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
            case 'draft':
                return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400';
            case 'pending':
                return 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400';
            default:
                return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
        }
    };

    return (
        <AppShell>
            <Head title="Instructor Dashboard" />
            
            <div className="space-y-8">
                {/* Header */}
                <div className="bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg p-6">
                    <h1 className="text-3xl font-bold mb-2">🎓 Teaching Dashboard</h1>
                    <p className="text-green-100">Track your courses and student progress</p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Earnings</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                    ${stats.totalEarnings.toLocaleString()}
                                </p>
                            </div>
                            <div className="text-2xl">💰</div>
                        </div>
                        <div className="mt-2">
                            <span className="text-green-600 text-sm font-medium">
                                +{stats.earningsGrowth}%
                            </span>
                            <span className="text-gray-500 text-sm ml-1">growth</span>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Active Students</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">{stats.activeStudents}</p>
                            </div>
                            <div className="text-2xl">👨‍🎓</div>
                        </div>
                        <div className="mt-2">
                            <span className="text-green-600 text-sm font-medium">
                                +{stats.studentsGrowth}%
                            </span>
                            <span className="text-gray-500 text-sm ml-1">this month</span>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Average Rating</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                    {stats.averageRating} ⭐
                                </p>
                            </div>
                            <div className="text-2xl">📈</div>
                        </div>
                        <div className="mt-2">
                            <span className="text-gray-500 text-sm">
                                {stats.totalReviews} reviews
                            </span>
                        </div>
                    </div>

                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">This Month</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                    ${stats.monthlyEarnings}
                                </p>
                            </div>
                            <div className="text-2xl">📅</div>
                        </div>
                        <div className="mt-2">
                            <span className="text-blue-600 text-sm font-medium">Monthly earnings</span>
                        </div>
                    </div>
                </div>

                {/* Main Content Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* My Courses */}
                    <div className="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="flex items-center justify-between mb-6">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">📚 My Courses</h2>
                            <button className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                Create New Course
                            </button>
                        </div>
                        <div className="space-y-4">
                            {courses.map((course) => (
                                <div key={course.id} className="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div className="flex items-start justify-between mb-3">
                                        <div className="flex-1">
                                            <h3 className="font-medium text-gray-900 dark:text-white">{course.title}</h3>
                                            <div className="flex items-center space-x-4 mt-1">
                                                <span className={`px-2 py-1 rounded-full text-xs font-medium ${getStatusColor(course.status)}`}>
                                                    {course.status}
                                                </span>
                                                <span className="text-sm text-gray-500 dark:text-gray-400">
                                                    {course.students} students
                                                </span>
                                                {course.rating > 0 && (
                                                    <span className="text-sm text-gray-500 dark:text-gray-400">
                                                        ⭐ {course.rating}
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <p className="font-semibold text-green-600 dark:text-green-400">
                                                ${course.revenue.toLocaleString()}
                                            </p>
                                            <p className="text-sm text-gray-500 dark:text-gray-400">Revenue</p>
                                        </div>
                                    </div>
                                    
                                    {/* Progress Bar */}
                                    <div className="mb-3">
                                        <div className="flex items-center justify-between text-sm mb-1">
                                            <span className="text-gray-600 dark:text-gray-400">Course Progress</span>
                                            <span className="text-gray-900 dark:text-white font-medium">{course.progress}%</span>
                                        </div>
                                        <div className="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div 
                                                className="bg-blue-600 h-2 rounded-full transition-all"
                                                style={{ width: `${course.progress}%` }}
                                            ></div>
                                        </div>
                                    </div>

                                    <div className="flex space-x-2">
                                        <button className="px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded text-sm font-medium transition-colors dark:bg-blue-900/20 dark:hover:bg-blue-900/40 dark:text-blue-400">
                                            Edit Course
                                        </button>
                                        <button className="px-3 py-1 bg-green-50 hover:bg-green-100 text-green-600 rounded text-sm font-medium transition-colors dark:bg-green-900/20 dark:hover:bg-green-900/40 dark:text-green-400">
                                            View Analytics
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Revenue Chart & Quick Actions */}
                    <div className="space-y-6">
                        {/* 6-Month Revenue */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">📈 6-Month Revenue</h2>
                            <div className="space-y-3">
                                {revenueData.map((data, index) => (
                                    <div key={index} className="flex items-center justify-between">
                                        <span className="text-sm text-gray-600 dark:text-gray-400">{data.month}</span>
                                        <div className="flex items-center space-x-2">
                                            <div className="w-20 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div 
                                                    className="bg-green-500 h-2 rounded-full"
                                                    style={{ width: `${(data.revenue / Math.max(...revenueData.map(d => d.revenue))) * 100}%` }}
                                                ></div>
                                            </div>
                                            <span className="text-sm font-medium text-gray-900 dark:text-white w-12 text-right">
                                                ${data.revenue}
                                            </span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Quick Stats */}
                        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                            <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">🎯 Quick Stats</h2>
                            <div className="space-y-4">
                                <div className="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div className="text-2xl font-bold text-blue-600 dark:text-blue-400">78%</div>
                                    <div className="text-sm text-gray-600 dark:text-gray-400">Course Completion Rate</div>
                                </div>
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <div className="text-lg font-bold text-green-600 dark:text-green-400">4.7</div>
                                        <div className="text-xs text-gray-600 dark:text-gray-400">Avg Rating</div>
                                    </div>
                                    <div className="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                        <div className="text-lg font-bold text-purple-600 dark:text-purple-400">92%</div>
                                        <div className="text-xs text-gray-600 dark:text-gray-400">Satisfaction</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Action Buttons */}
                <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h2 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">⚡ Quick Actions</h2>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button className="p-4 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 rounded-lg border border-blue-200 dark:border-blue-700 transition-colors">
                            <div className="text-2xl mb-2">📝</div>
                            <div className="text-sm font-medium text-gray-900 dark:text-white">Create New Course</div>
                            <div className="text-xs text-gray-500 dark:text-gray-400">Start building</div>
                        </button>
                        
                        <button className="p-4 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/40 rounded-lg border border-green-200 dark:border-green-700 transition-colors">
                            <div className="text-2xl mb-2">💬</div>
                            <div className="text-sm font-medium text-gray-900 dark:text-white">Student Feedback</div>
                            <div className="text-xs text-gray-500 dark:text-gray-400">View reviews</div>
                        </button>
                        
                        <button className="p-4 bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-900/40 rounded-lg border border-purple-200 dark:border-purple-700 transition-colors">
                            <div className="text-2xl mb-2">💳</div>
                            <div className="text-sm font-medium text-gray-900 dark:text-white">Request Payout</div>
                            <div className="text-xs text-gray-500 dark:text-gray-400">Get paid</div>
                        </button>
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