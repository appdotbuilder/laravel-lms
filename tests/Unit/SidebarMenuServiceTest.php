<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\CourseReview;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\User;
use App\Services\SidebarMenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SidebarMenuServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SidebarMenuService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SidebarMenuService();
    }

    public function test_get_menu_items_returns_empty_array_for_unauthenticated_user(): void
    {
        // Without authenticated user, should return empty array
        $result = $this->service->getMenuItems();
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_clear_cache_removes_user_cache(): void
    {
        $user = User::factory()->create();
        $cacheKey = "sidebar_menu_user_{$user->id}";
        
        // Set cache first
        Cache::put($cacheKey, ['test' => 'data'], 300);
        $this->assertTrue(Cache::has($cacheKey));
        
        // Clear cache
        $this->service->clearCache($user);
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_get_pending_course_count(): void
    {
        // Create courses with different statuses
        Course::factory()->count(3)->create(['status' => 'pending_approval']);
        Course::factory()->count(2)->create(['status' => 'published']);
        Course::factory()->count(1)->create(['status' => 'draft']);
        
        // Use reflection to test protected method
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getPendingCourseCount');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service);
        
        $this->assertEquals(3, $count);
    }

    public function test_get_pending_instructor_count(): void
    {
        // Create instructors with different approval statuses
        User::factory()->count(2)->create(['role' => 'instructor', 'is_approved' => false]);
        User::factory()->count(3)->create(['role' => 'instructor', 'is_approved' => true]);
        User::factory()->count(1)->create(['role' => 'student', 'is_approved' => false]);
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getPendingInstructorCount');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service);
        
        $this->assertEquals(2, $count);
    }

    public function test_get_unread_notification_count(): void
    {
        $user = User::factory()->create();
        
        // Create notifications for the user
        Notification::factory()->count(4)->create(['user_id' => $user->id, 'read' => false]);
        Notification::factory()->count(2)->create(['user_id' => $user->id, 'read' => true]);
        
        // Create notifications for other users
        $otherUser = User::factory()->create();
        Notification::factory()->count(3)->create(['user_id' => $otherUser->id, 'read' => false]);
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getUnreadNotificationCount');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service, $user);
        
        $this->assertEquals(4, $count);
    }

    public function test_get_incomplete_course_count(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        
        // Create courses first to avoid constraint violations
        $courses = Course::factory()->count(10)->create();
        
        // Create enrollments for the student
        for ($i = 0; $i < 5; $i++) {
            Enrollment::factory()->create([
                'student_id' => $student->id,
                'course_id' => $courses[$i]->id,
                'status' => 'active'
            ]);
        }
        
        for ($i = 5; $i < 7; $i++) {
            Enrollment::factory()->create([
                'student_id' => $student->id,
                'course_id' => $courses[$i]->id,
                'status' => 'completed'
            ]);
        }
        
        // Create enrollments for other students
        $otherStudent = User::factory()->create(['role' => 'student']);
        for ($i = 7; $i < 10; $i++) {
            Enrollment::factory()->create([
                'student_id' => $otherStudent->id,
                'course_id' => $courses[$i]->id,
                'status' => 'active'
            ]);
        }
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getIncompleteCourseCount');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service, $student);
        
        $this->assertEquals(5, $count);
    }

    public function test_get_unread_forum_replies(): void
    {
        $user = User::factory()->create();
        
        // Create forum reply notifications
        Notification::factory()->count(3)->create([
            'user_id' => $user->id,
            'type' => 'forum_reply',
            'read' => false
        ]);
        
        // Create other types of notifications
        Notification::factory()->count(2)->create([
            'user_id' => $user->id,
            'type' => 'other_type',
            'read' => false
        ]);
        
        // Create read forum replies
        Notification::factory()->count(1)->create([
            'user_id' => $user->id,
            'type' => 'forum_reply',
            'read' => true
        ]);
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getUnreadForumReplies');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service, $user);
        
        $this->assertEquals(3, $count);
    }

    public function test_get_unread_review_count(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);
        
        // Create students for unique reviews
        $students = User::factory()->count(6)->create(['role' => 'student']);
        
        // Create unapproved reviews for the instructor's course
        for ($i = 0; $i < 4; $i++) {
            CourseReview::factory()->create([
                'course_id' => $course->id,
                'student_id' => $students[$i]->id,
                'approved' => false
            ]);
        }
        
        // Create approved reviews
        for ($i = 4; $i < 6; $i++) {
            CourseReview::factory()->create([
                'course_id' => $course->id,
                'student_id' => $students[$i]->id,
                'approved' => true
            ]);
        }
        
        // Create reviews for other instructor's courses
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $otherCourse = Course::factory()->create(['instructor_id' => $otherInstructor->id]);
        $otherStudents = User::factory()->count(3)->create(['role' => 'student']);
        
        for ($i = 0; $i < 3; $i++) {
            CourseReview::factory()->create([
                'course_id' => $otherCourse->id,
                'student_id' => $otherStudents[$i]->id,
                'approved' => false
            ]);
        }
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getUnreadReviewCount');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service, $instructor);
        
        $this->assertEquals(4, $count);
    }

    public function test_get_unread_message_count(): void
    {
        $user = User::factory()->create();
        
        // Create direct message notifications
        Notification::factory()->count(2)->create([
            'user_id' => $user->id,
            'type' => 'direct_message',
            'read' => false
        ]);
        
        // Create other notifications
        Notification::factory()->count(3)->create([
            'user_id' => $user->id,
            'type' => 'other_type',
            'read' => false
        ]);
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getUnreadMessageCount');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service, $user);
        
        $this->assertEquals(2, $count);
    }

    public function test_get_pending_payout_count_returns_zero(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getPendingPayoutCount');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service);
        
        $this->assertEquals(0, $count);
    }

    public function test_get_wishlist_count_returns_zero(): void
    {
        $user = User::factory()->create();
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getWishlistCount');
        $method->setAccessible(true);
        
        $count = $method->invoke($this->service, $user);
        
        $this->assertEquals(0, $count);
    }

    public function test_build_admin_menu_structure(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('buildAdminMenu');
        $method->setAccessible(true);
        
        $menu = $method->invoke($this->service, $admin);
        
        $this->assertIsArray($menu);
        $this->assertNotEmpty($menu);
        
        // Check that all expected groups are present
        $groupLabels = collect($menu)->pluck('label')->toArray();
        $expectedGroups = ['Administration', 'System', 'Personal'];
        
        foreach ($expectedGroups as $expectedGroup) {
            $this->assertContains($expectedGroup, $groupLabels);
        }
        
        // Check that each group has items
        foreach ($menu as $group) {
            $this->assertArrayHasKey('items', $group);
            $this->assertNotEmpty($group['items']);
        }
    }

    public function test_build_instructor_menu_structure(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('buildInstructorMenu');
        $method->setAccessible(true);
        
        $menu = $method->invoke($this->service, $instructor);
        
        $this->assertIsArray($menu);
        $this->assertNotEmpty($menu);
        
        $groupLabels = collect($menu)->pluck('label')->toArray();
        $expectedGroups = ['Teaching', 'Engagement', 'Personal'];
        
        foreach ($expectedGroups as $expectedGroup) {
            $this->assertContains($expectedGroup, $groupLabels);
        }
    }

    public function test_build_student_menu_structure(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('buildStudentMenu');
        $method->setAccessible(true);
        
        $menu = $method->invoke($this->service, $student);
        
        $this->assertIsArray($menu);
        $this->assertNotEmpty($menu);
        
        $groupLabels = collect($menu)->pluck('label')->toArray();
        $expectedGroups = ['Learning', 'Community', 'Personal'];
        
        foreach ($expectedGroups as $expectedGroup) {
            $this->assertContains($expectedGroup, $groupLabels);
        }
    }
}