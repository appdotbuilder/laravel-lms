import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    const features = [
        {
            icon: '🎓',
            title: 'Expert-Led Courses',
            description: 'Learn from industry professionals with real-world experience'
        },
        {
            icon: '📊',
            title: 'Progress Tracking',
            description: 'Monitor your learning journey with detailed analytics and achievements'
        },
        {
            icon: '💰',
            title: 'Earn While Teaching',
            description: 'Instructors earn competitive revenue share from their courses'
        },
        {
            icon: '🏆',
            title: 'Certificates & Badges',
            description: 'Earn verified certificates and showcase your accomplishments'
        },
        {
            icon: '👥',
            title: 'Interactive Community',
            description: 'Connect with fellow learners and get help from instructors'
        },
        {
            icon: '📱',
            title: 'Learn Anywhere',
            description: 'Full mobile optimization for learning on-the-go'
        }
    ];

    const testimonials = [
        {
            name: 'Sarah Johnson',
            role: 'Full-Stack Developer',
            content: 'This platform transformed my career! The React course helped me land my dream job.',
            avatar: '👩‍💻'
        },
        {
            name: 'Mike Chen',
            role: 'Course Instructor',
            content: 'I\'ve earned over $50K teaching Python here. The platform makes it so easy to create and sell courses.',
            avatar: '👨‍🏫'
        },
        {
            name: 'Emma Davis',
            role: 'UX Designer',
            content: 'The UI/UX courses are incredible. I went from beginner to getting hired at a top agency.',
            avatar: '🎨'
        }
    ];

    return (
        <>
            <Head title="EduPlatform - Online Learning Made Simple">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
                {/* Navigation */}
                <nav className="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center h-16">
                            <div className="flex items-center space-x-2">
                                <span className="text-2xl">🎓</span>
                                <h1 className="text-xl font-bold text-gray-900 dark:text-white">EduPlatform</h1>
                            </div>
                            <div className="flex items-center space-x-4">
                                {auth.user ? (
                                    <Link
                                        href={route('dashboard')}
                                        className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                                    >
                                        Go to Dashboard
                                    </Link>
                                ) : (
                                    <div className="flex space-x-3">
                                        <Link
                                            href={route('login')}
                                            className="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                        >
                                            Sign In
                                        </Link>
                                        <Link
                                            href={route('register')}
                                            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors"
                                        >
                                            Get Started Free
                                        </Link>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </nav>

                {/* Hero Section */}
                <section className="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white py-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h1 className="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                            🚀 Master New Skills
                            <br />
                            <span className="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                                Transform Your Career
                            </span>
                        </h1>
                        <p className="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                            Join thousands of learners on our comprehensive online education platform. 
                            Learn from experts, track your progress, and achieve your goals.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            {!auth.user && (
                                <>
                                    <Link
                                        href={route('register')}
                                        className="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-colors shadow-lg"
                                    >
                                        🎯 Start Learning Today
                                    </Link>
                                    <Link
                                        href={route('login')}
                                        className="bg-transparent border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 transition-colors"
                                    >
                                        👨‍🏫 Teach a Course
                                    </Link>
                                </>
                            )}
                        </div>
                        <div className="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                            <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                                <div className="text-3xl font-bold">10K+</div>
                                <div className="text-blue-100">Active Students</div>
                            </div>
                            <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                                <div className="text-3xl font-bold">500+</div>
                                <div className="text-blue-100">Expert Courses</div>
                            </div>
                            <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                                <div className="text-3xl font-bold">98%</div>
                                <div className="text-blue-100">Success Rate</div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Features Section */}
                <section className="py-20 bg-white dark:bg-gray-800">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                                🌟 Why Choose Our Platform?
                            </h2>
                            <p className="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                                We've built the most comprehensive learning platform with features designed for students, instructors, and administrators.
                            </p>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {features.map((feature, index) => (
                                <div key={index} className="bg-gray-50 dark:bg-gray-700 rounded-xl p-6 hover:shadow-lg transition-shadow">
                                    <div className="text-4xl mb-4">{feature.icon}</div>
                                    <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                                        {feature.title}
                                    </h3>
                                    <p className="text-gray-600 dark:text-gray-300">
                                        {feature.description}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Role-Based Dashboards Preview */}
                <section className="py-20 bg-gray-50 dark:bg-gray-900">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                                🎯 Tailored Experiences for Everyone
                            </h2>
                            <p className="text-xl text-gray-600 dark:text-gray-300">
                                Each role gets a personalized dashboard with relevant tools and insights
                            </p>
                        </div>
                        
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            {/* Student Dashboard Preview */}
                            <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <div className="text-center mb-6">
                                    <div className="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl">
                                        🎓
                                    </div>
                                    <h3 className="text-xl font-semibold text-gray-900 dark:text-white">Student Dashboard</h3>
                                </div>
                                <ul className="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Continue Learning Path</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Progress Tracking & Streaks</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>AI-Powered Recommendations</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Achievement Badges</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Community & Q&A Forums</span>
                                    </li>
                                </ul>
                            </div>

                            {/* Instructor Dashboard Preview */}
                            <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <div className="text-center mb-6">
                                    <div className="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl">
                                        👨‍🏫
                                    </div>
                                    <h3 className="text-xl font-semibold text-gray-900 dark:text-white">Instructor Dashboard</h3>
                                </div>
                                <ul className="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Course Builder & Analytics</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Earnings & Payout Management</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Student Progress Monitoring</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Review & Rating Management</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Bulk Messaging Tools</span>
                                    </li>
                                </ul>
                            </div>

                            {/* Admin Dashboard Preview */}
                            <div className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                                <div className="text-center mb-6">
                                    <div className="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl">
                                        🛡️
                                    </div>
                                    <h3 className="text-xl font-semibold text-gray-900 dark:text-white">Admin Dashboard</h3>
                                </div>
                                <ul className="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Platform Analytics & KPIs</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Course Approval Workflow</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>User & Instructor Management</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Payment & Transaction Logs</span>
                                    </li>
                                    <li className="flex items-center space-x-2">
                                        <span className="text-green-500">✓</span>
                                        <span>Marketing & Promo Tools</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Testimonials */}
                <section className="py-20 bg-white dark:bg-gray-800">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                                💬 What Our Community Says
                            </h2>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {testimonials.map((testimonial, index) => (
                                <div key={index} className="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                                    <div className="flex items-center mb-4">
                                        <div className="text-3xl mr-3">{testimonial.avatar}</div>
                                        <div>
                                            <h4 className="font-semibold text-gray-900 dark:text-white">{testimonial.name}</h4>
                                            <p className="text-sm text-gray-600 dark:text-gray-300">{testimonial.role}</p>
                                        </div>
                                    </div>
                                    <p className="text-gray-700 dark:text-gray-300 italic">"{testimonial.content}"</p>
                                    <div className="mt-4 flex text-yellow-400">
                                        ⭐⭐⭐⭐⭐
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* CTA Section */}
                <section className="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                    <div className="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                        <h2 className="text-4xl font-bold mb-6">
                            🚀 Ready to Start Your Learning Journey?
                        </h2>
                        <p className="text-xl text-blue-100 mb-8">
                            Join thousands of successful students and instructors already using our platform
                        </p>
                        {!auth.user && (
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link
                                    href={route('register')}
                                    className="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-colors shadow-lg"
                                >
                                    🎯 Start Learning Free
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="bg-transparent border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 transition-colors"
                                >
                                    👨‍🏫 Become an Instructor
                                </Link>
                            </div>
                        )}
                    </div>
                </section>

                {/* Footer */}
                <footer className="bg-gray-900 text-gray-300 py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div>
                                <div className="flex items-center space-x-2 mb-4">
                                    <span className="text-2xl">🎓</span>
                                    <h3 className="text-xl font-bold text-white">EduPlatform</h3>
                                </div>
                                <p className="text-gray-400">
                                    The comprehensive online learning platform for everyone.
                                </p>
                            </div>
                            <div>
                                <h4 className="text-lg font-semibold text-white mb-4">For Students</h4>
                                <ul className="space-y-2 text-sm">
                                    <li>Browse Courses</li>
                                    <li>Learning Paths</li>
                                    <li>Certificates</li>
                                    <li>Community</li>
                                </ul>
                            </div>
                            <div>
                                <h4 className="text-lg font-semibold text-white mb-4">For Instructors</h4>
                                <ul className="space-y-2 text-sm">
                                    <li>Create Courses</li>
                                    <li>Analytics</li>
                                    <li>Payouts</li>
                                    <li>Resources</li>
                                </ul>
                            </div>
                            <div>
                                <h4 className="text-lg font-semibold text-white mb-4">Company</h4>
                                <ul className="space-y-2 text-sm">
                                    <li>About Us</li>
                                    <li>Contact</li>
                                    <li>Privacy</li>
                                    <li>Terms</li>
                                </ul>
                            </div>
                        </div>
                        <div className="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                            <p>&copy; 2024 EduPlatform. Built with ❤️ for learners everywhere.</p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}