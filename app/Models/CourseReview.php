<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CourseReview
 *
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property int $rating
 * @property string|null $review
 * @property bool $approved
 * @property bool $instructor_read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $student
 * @property-read \App\Models\Course $course
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereInstructorRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseReview approved()
 * @method static \Database\Factories\CourseReviewFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class CourseReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_id',
        'course_id',
        'rating',
        'review',
        'approved',
        'instructor_read',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved' => 'boolean',
        'instructor_read' => 'boolean',
    ];

    /**
     * Scope a query to only include approved reviews.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Get the student that owns the review.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the course that owns the review.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}