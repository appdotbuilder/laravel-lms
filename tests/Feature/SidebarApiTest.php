<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use App\Services\SidebarMenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SidebarApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_sidebar_api(): void
    {
        $response = $this->getJson('/api/sidebar-menu');
        
        $response->assertStatus(401);
    }

    public function test_admin_can_access_sidebar_menu(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/sidebar-menu');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                ])
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'type',
                            'title',
                            'items' => [
                                '*' => [
                                    'type',
                                    'title',
                                    'url',
                                    'icon',
                                ]
                            ]
                        ]
                    ]
                ]);
    }

    public function test_instructor_can_access_sidebar_menu(): void
    {
        $instructor = User::factory()->create([
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($instructor)->getJson('/api/sidebar-menu');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                ]);
    }

    public function test_student_can_access_sidebar_menu(): void
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $response = $this->actingAs($student)->getJson('/api/sidebar-menu');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                ]);
    }

    public function test_admin_sidebar_shows_pending_course_badge(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        
        // Create pending courses
        Course::factory()->count(3)->create([
            'instructor_id' => $instructor->id,
            'status' => 'pending_approval',
        ]);

        $response = $this->actingAs($admin)->getJson('/api/sidebar-menu');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $coursesItem = $this->findMenuItemByTitle($data, 'Courses');
        
        $this->assertNotNull($coursesItem);
        $this->assertEquals(3, $coursesItem['badge'] ?? 0);
    }

    public function test_admin_sidebar_shows_pending_instructor_badge(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Create pending instructors
        User::factory()->count(2)->create([
            'role' => 'instructor',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($admin)->getJson('/api/sidebar-menu');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $instructorsItem = $this->findMenuItemByTitle($data, 'Instructors');
        
        $this->assertNotNull($instructorsItem);
        $this->assertEquals(2, $instructorsItem['badge'] ?? 0);
    }

    public function test_instructor_sidebar_shows_unread_review_badge(): void
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);
        
        // Create different students for each review to avoid unique constraint
        $student1 = User::factory()->create(['role' => 'student']);
        $student2 = User::factory()->create(['role' => 'student']);
        $student3 = User::factory()->create(['role' => 'student']);
        
        // Create unapproved course reviews (these appear as unread to instructor)
        \App\Models\CourseReview::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student1->id,
            'approved' => false,
        ]);
        \App\Models\CourseReview::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student2->id,
            'approved' => false,
        ]);
        \App\Models\CourseReview::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student3->id,
            'approved' => false,
        ]);

        $response = $this->actingAs($instructor)->getJson('/api/sidebar-menu');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $reviewsItem = $this->findMenuItemByTitle($data, 'Reviews');
        
        $this->assertNotNull($reviewsItem);
        $this->assertGreaterThan(0, $reviewsItem['badge'] ?? 0);
    }

    public function test_student_sidebar_shows_forum_notification_badge(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        
        // Create unread forum notifications
        Notification::factory()->count(2)->create([
            'user_id' => $student->id,
            'type' => 'forum_reply',
            'read' => false,
        ]);

        $response = $this->actingAs($student)->getJson('/api/sidebar-menu');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $forumItem = $this->findMenuItemByTitle($data, 'Forums');
        
        $this->assertNotNull($forumItem);
        $this->assertEquals(2, $forumItem['badge'] ?? 0);
    }

    public function test_sidebar_menu_uses_cache(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Clear cache
        Cache::flush();
        
        // First request should cache the result
        $response1 = $this->actingAs($admin)->getJson('/api/sidebar-menu');
        $response1->assertStatus(200);
        
        // Check if cache key exists
        $cacheKey = "sidebar_menu_{$admin->id}_{$admin->role}";
        $this->assertTrue(Cache::has($cacheKey));
        
        // Second request should use cached result
        $response2 = $this->actingAs($admin)->getJson('/api/sidebar-menu');
        $response2->assertStatus(200);
        
        $this->assertEquals($response1->json(), $response2->json());
    }

    public function test_sidebar_service_returns_empty_for_invalid_role(): void
    {
        $user = User::factory()->make(['role' => 'student']); // Use make instead of create to avoid DB constraint
        $user->role = 'invalid_role'; // Set after making
        
        $service = new SidebarMenuService();
        $this->actingAs($user);
        
        $menuItems = $service->getMenuItems();
        
        $this->assertEmpty($menuItems);
    }

    /**
     * Helper method to find a menu item by title in nested menu structure.
     */
    private function findMenuItemByTitle(array $menuData, string $title): ?array
    {
        foreach ($menuData as $group) {
            if (isset($group['items'])) {
                foreach ($group['items'] as $item) {
                    if ($item['title'] === $title) {
                        return $item;
                    }
                }
            }
        }
        
        return null;
    }
}