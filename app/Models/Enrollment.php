<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Enrollment
 *
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property float $price_paid
 * @property string $status
 * @property float $progress_percentage
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $last_accessed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\User $student
 * @property-read \App\Models\Course $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LessonProgress[] $lessonProgress
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment wherePricePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereProgressPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereLastAccessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment active()
 * @method static \Illuminate\Database\Eloquent\Builder|Enrollment completed()
 * @method static \Database\Factories\EnrollmentFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Enrollment extends Model
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
        'price_paid',
        'status',
        'progress_percentage',
        'completed_at',
        'last_accessed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_paid' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active enrollments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed enrollments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the student that owns the enrollment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the course that owns the enrollment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lesson progress for the enrollment.
     */
    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }
}