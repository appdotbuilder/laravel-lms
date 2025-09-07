<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create course categories
        $categories = [
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Learn modern web development technologies',
                'color' => '#3B82F6',
                'icon' => 'code',
                'sort_order' => 1,
            ],
            [
                'name' => 'Data Science',
                'slug' => 'data-science',
                'description' => 'Master data analysis and machine learning',
                'color' => '#10B981',
                'icon' => 'chart',
                'sort_order' => 2,
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'UI/UX design and creative skills',
                'color' => '#F59E0B',
                'icon' => 'design',
                'sort_order' => 3,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business skills and entrepreneurship',
                'color' => '#8B5CF6',
                'icon' => 'briefcase',
                'sort_order' => 4,
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Digital marketing and growth strategies',
                'color' => '#EF4444',
                'icon' => 'megaphone',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Get instructor users
        $instructors = User::instructors()->get();
        $categories = CourseCategory::all();

        if ($instructors->isEmpty() || $categories->isEmpty()) {
            return;
        }

        // Create featured courses with specific content
        $featuredCourses = [
            [
                'title' => 'Complete React Masterclass',
                'description' => 'Master React from basics to advanced concepts including hooks, context, testing, and performance optimization. Build real-world projects and become a React expert.',
                'short_description' => 'Learn React from zero to hero with hands-on projects',
                'price' => 89.99,
                'status' => 'published',
                'difficulty' => 'intermediate',
                'duration_minutes' => 1200, // 20 hours
                'tags' => ['React', 'JavaScript', 'Frontend', 'Web Development'],
                'featured' => true,
                'rating' => 4.8,
                'total_reviews' => 245,
                'total_students' => 1250,
                'published_at' => now()->subMonths(2),
            ],
            [
                'title' => 'Python for Data Science',
                'description' => 'Complete guide to Python for data science including pandas, numpy, matplotlib, and machine learning with scikit-learn. Perfect for beginners.',
                'short_description' => 'Master Python for data analysis and machine learning',
                'price' => 79.99,
                'status' => 'published',
                'difficulty' => 'beginner',
                'duration_minutes' => 900, // 15 hours
                'tags' => ['Python', 'Data Science', 'Machine Learning', 'Analytics'],
                'featured' => true,
                'rating' => 4.7,
                'total_reviews' => 189,
                'total_students' => 890,
                'published_at' => now()->subMonths(1),
            ],
            [
                'title' => 'UI/UX Design Fundamentals',
                'description' => 'Learn the principles of user interface and user experience design. Create beautiful, functional designs using Figma and modern design tools.',
                'short_description' => 'Design beautiful user interfaces and experiences',
                'price' => 69.99,
                'status' => 'published',
                'difficulty' => 'beginner',
                'duration_minutes' => 720, // 12 hours
                'tags' => ['UI Design', 'UX Design', 'Figma', 'Design Thinking'],
                'featured' => true,
                'rating' => 4.9,
                'total_reviews' => 156,
                'total_students' => 650,
                'published_at' => now()->subWeeks(3),
            ],
        ];

        foreach ($featuredCourses as $index => $courseData) {
            $instructor = $instructors->get($index % $instructors->count());
            $category = $categories->where('slug', 
                $index === 0 ? 'web-development' : 
                ($index === 1 ? 'data-science' : 'design')
            )->first();

            $courseData['slug'] = Str::slug($courseData['title']);
            $courseData['instructor_id'] = $instructor->id;
            $courseData['category_id'] = $category?->id;
            $courseData['thumbnail'] = '/images/course-' . ($index + 1) . '.jpg';

            $course = Course::create($courseData);

            // Create lessons for each course
            $this->createLessonsForCourse($course, $index);
        }

        // Create additional random courses
        Course::factory()->count(15)->create();

        // Create lessons for the random courses
        $courses = Course::whereNotIn('title', array_column($featuredCourses, 'title'))->get();
        foreach ($courses as $course) {
            CourseLesson::factory()->count(random_int(5, 12))->create([
                'course_id' => $course->id,
                'sort_order' => function () use ($course) {
                    return CourseLesson::where('course_id', $course->id)->count() + 1;
                },
            ]);
        }
    }

    /**
     * Create lessons for a specific course.
     *
     * @param Course $course
     * @param int $courseIndex
     */
    protected function createLessonsForCourse(Course $course, int $courseIndex): void
    {
        $lessonSets = [
            // React Course Lessons
            [
                ['title' => 'Introduction to React', 'duration_minutes' => 45, 'is_free' => true],
                ['title' => 'Setting Up Your Development Environment', 'duration_minutes' => 30, 'is_free' => true],
                ['title' => 'Understanding JSX', 'duration_minutes' => 60, 'is_free' => false],
                ['title' => 'Components and Props', 'duration_minutes' => 75, 'is_free' => false],
                ['title' => 'State and Event Handling', 'duration_minutes' => 90, 'is_free' => false],
                ['title' => 'React Hooks - useState and useEffect', 'duration_minutes' => 85, 'is_free' => false],
                ['title' => 'Context API and State Management', 'duration_minutes' => 95, 'is_free' => false],
                ['title' => 'React Router for Navigation', 'duration_minutes' => 70, 'is_free' => false],
                ['title' => 'Testing React Applications', 'duration_minutes' => 80, 'is_free' => false],
                ['title' => 'Performance Optimization', 'duration_minutes' => 65, 'is_free' => false],
                ['title' => 'Building a Complete Project', 'duration_minutes' => 120, 'is_free' => false],
            ],
            // Python Course Lessons
            [
                ['title' => 'Python Basics and Syntax', 'duration_minutes' => 50, 'is_free' => true],
                ['title' => 'Data Types and Variables', 'duration_minutes' => 40, 'is_free' => true],
                ['title' => 'Introduction to Pandas', 'duration_minutes' => 65, 'is_free' => false],
                ['title' => 'Data Manipulation with NumPy', 'duration_minutes' => 55, 'is_free' => false],
                ['title' => 'Data Visualization with Matplotlib', 'duration_minutes' => 70, 'is_free' => false],
                ['title' => 'Exploratory Data Analysis', 'duration_minutes' => 85, 'is_free' => false],
                ['title' => 'Introduction to Machine Learning', 'duration_minutes' => 75, 'is_free' => false],
                ['title' => 'Supervised Learning Algorithms', 'duration_minutes' => 90, 'is_free' => false],
                ['title' => 'Model Evaluation and Validation', 'duration_minutes' => 80, 'is_free' => false],
                ['title' => 'Final Project: Predicting Housing Prices', 'duration_minutes' => 110, 'is_free' => false],
            ],
            // Design Course Lessons
            [
                ['title' => 'Design Thinking Fundamentals', 'duration_minutes' => 45, 'is_free' => true],
                ['title' => 'Color Theory and Typography', 'duration_minutes' => 55, 'is_free' => true],
                ['title' => 'Introduction to Figma', 'duration_minutes' => 60, 'is_free' => false],
                ['title' => 'User Research and Personas', 'duration_minutes' => 70, 'is_free' => false],
                ['title' => 'Wireframing and Prototyping', 'duration_minutes' => 80, 'is_free' => false],
                ['title' => 'Visual Hierarchy and Layout', 'duration_minutes' => 65, 'is_free' => false],
                ['title' => 'Mobile-First Design', 'duration_minutes' => 75, 'is_free' => false],
                ['title' => 'Accessibility in Design', 'duration_minutes' => 60, 'is_free' => false],
                ['title' => 'Design Systems and Components', 'duration_minutes' => 85, 'is_free' => false],
                ['title' => 'Portfolio Project: Complete App Design', 'duration_minutes' => 120, 'is_free' => false],
            ],
        ];

        $lessons = $lessonSets[$courseIndex] ?? [];
        
        foreach ($lessons as $index => $lessonData) {
            $lessonData['course_id'] = $course->id;
            $lessonData['sort_order'] = $index + 1;
            $lessonData['type'] = 'video';
            $lessonData['content'] = 'In this lesson, you will learn about ' . strtolower($lessonData['title']) . '. We will cover the key concepts and provide hands-on examples.';
            $lessonData['video_url'] = 'https://example.com/videos/lesson-' . ($index + 1);
            
            CourseLesson::create($lessonData);
        }
    }
}