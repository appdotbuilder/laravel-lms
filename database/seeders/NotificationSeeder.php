<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('Seeding notifications...');

        // Get users by role
        $admins = User::where('role', 'admin')->get();
        $instructors = User::where('role', 'instructor')->get();
        $students = User::where('role', 'student')->get();
        $courses = Course::all();

        // Create admin notifications
        foreach ($admins as $admin) {
            // Course approval notifications
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'course_approval',
                'notifiable_type' => Course::class,
                'notifiable_id' => $courses->random()->id ?? 1,
                'title' => 'Course Approval Required',
                'message' => 'A new course has been submitted for approval.',
                'read' => false,
            ]);

            // Instructor approval notifications
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'instructor_approval',
                'notifiable_type' => User::class,
                'notifiable_id' => $instructors->random()->id ?? 1,
                'title' => 'Instructor Approval Required',
                'message' => 'A new instructor application needs review.',
                'read' => false,
            ]);

            // Payout request notifications
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'payout_request',
                'notifiable_type' => User::class,
                'notifiable_id' => $instructors->random()->id ?? 1,
                'title' => 'Payout Request',
                'message' => 'An instructor has requested a payout.',
                'read' => false,
            ]);
        }

        // Create instructor notifications
        foreach ($instructors as $instructor) {
            // New review notifications
            for ($i = 0; $i < random_int(1, 5); $i++) {
                Notification::create([
                    'user_id' => $instructor->id,
                    'type' => 'course_review',
                    'notifiable_type' => Course::class,
                    'notifiable_id' => $courses->random()->id ?? 1,
                    'title' => 'New Course Review',
                    'message' => 'Your course received a new review.',
                    'read' => random_int(0, 1) === 1,
                ]);
            }

            // Student enrollment notifications
            for ($i = 0; $i < random_int(1, 3); $i++) {
                Notification::create([
                    'user_id' => $instructor->id,
                    'type' => 'enrollment',
                    'notifiable_type' => Course::class,
                    'notifiable_id' => $courses->random()->id ?? 1,
                    'title' => 'New Student Enrollment',
                    'message' => 'A student enrolled in your course.',
                    'read' => random_int(0, 1) === 1,
                ]);
            }

            // Q&A forum messages
            for ($i = 0; $i < random_int(5, 15); $i++) {
                Notification::create([
                    'user_id' => $instructor->id,
                    'type' => 'forum_reply',
                    'notifiable_type' => Course::class,
                    'notifiable_id' => $courses->random()->id ?? 1,
                    'title' => 'New Q&A Message',
                    'message' => 'A student asked a question in your course.',
                    'read' => random_int(0, 1) === 1,
                ]);
            }
        }

        // Create student notifications
        foreach ($students as $student) {
            // Course recommendation notifications
            for ($i = 0; $i < random_int(2, 6); $i++) {
                Notification::create([
                    'user_id' => $student->id,
                    'type' => 'course_recommendation',
                    'notifiable_type' => Course::class,
                    'notifiable_id' => $courses->random()->id ?? 1,
                    'title' => 'New Course Recommendation',
                    'message' => 'We found a course you might like.',
                    'read' => random_int(0, 1) === 1,
                ]);
            }

            // Forum reply notifications
            for ($i = 0; $i < random_int(1, 4); $i++) {
                Notification::create([
                    'user_id' => $student->id,
                    'type' => 'forum_reply',
                    'notifiable_type' => Course::class,
                    'notifiable_id' => $courses->random()->id ?? 1,
                    'title' => 'Forum Reply',
                    'message' => 'Someone replied to your forum post.',
                    'read' => random_int(0, 1) === 1,
                ]);
            }

            // Achievement notifications
            Notification::create([
                'user_id' => $student->id,
                'type' => 'achievement',
                'notifiable_type' => Course::class,
                'notifiable_id' => $courses->random()->id ?? 1,
                'title' => 'Achievement Unlocked!',
                'message' => 'You completed a learning milestone.',
                'read' => random_int(0, 1) === 1,
            ]);
        }

        // Create some pending course approvals by updating course status
        $pendingCourses = $courses->random(3);
        foreach ($pendingCourses as $course) {
            $course->update(['status' => 'pending_approval']);
        }

        // Create some unapproved instructors
        $pendingInstructors = $instructors->random(2);
        foreach ($pendingInstructors as $instructor) {
            $instructor->update(['is_approved' => false]);
        }

        $totalNotifications = Notification::count();
        $this->command->info("Created {$totalNotifications} notifications successfully!");
    }
}