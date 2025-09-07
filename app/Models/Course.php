<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Course
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $short_description
 * @property string $slug
 * @property string|null $thumbnail
 * @property float $price
 * @property string $status
 * @property string $difficulty
 * @property int $duration_minutes
 * @property array|null $tags
 * @property int $instructor_id
 * @property int|null $category_id
 * @property int|null $max_students
 * @property bool $featured
 * @property float $rating
 * @property int $total_reviews
 * @property int $total_students
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $instructor
 * @property-read \App\Models\CourseCategory|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseLesson[] $lessons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Enrollment[] $enrollments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CourseReview[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDifficulty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereInstructorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereMaxStudents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTotalReviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereTotalStudents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course published()
 * @method static \Illuminate\Database\Eloquent\Builder|Course featured()
 * @method static \Illuminate\Database\Eloquent\Builder|Course byInstructor($instructorId)
 * @method static \Database\Factories\CourseFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'slug',
        'thumbnail',
        'price',
        'status',
        'difficulty',
        'duration_minutes',
        'tags',
        'instructor_id',
        'category_id',
        'max_students',
        'featured',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'featured' => 'boolean',
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Scope a query to only include published courses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include featured courses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to filter by instructor.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $instructorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    /**
     * Get the instructor that owns the course.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the category that owns the course.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    /**
     * Get the lessons for the course.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class)->orderBy('sort_order');
    }

    /**
     * Get the enrollments for the course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the reviews for the course.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    /**
     * Get the transactions for the course.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}