<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\LessonProgress
 *
 * @property int $id
 * @property int $enrollment_id
 * @property int $lesson_id
 * @property bool $completed
 * @property int $watch_time_seconds
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Enrollment $enrollment
 * @property-read \App\Models\CourseLesson $lesson
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress query()
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress whereEnrollmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress whereWatchTimeSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LessonProgress completed()
 * @method static \Database\Factories\LessonProgressFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class LessonProgress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'enrollment_id',
        'lesson_id',
        'completed',
        'watch_time_seconds',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Scope a query to only include completed progress.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    /**
     * Get the enrollment that owns the progress.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Get the lesson that owns the progress.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }
}