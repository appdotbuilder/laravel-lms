<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\CourseLesson
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property string|null $video_url
 * @property int $duration_minutes
 * @property int $sort_order
 * @property bool $is_free
 * @property string $type
 * @property array|null $resources
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\Course $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LessonProgress[] $progress
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereIsFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereResources($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseLesson free()
 * @method static \Database\Factories\CourseLessonFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class CourseLesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'content',
        'video_url',
        'duration_minutes',
        'sort_order',
        'is_free',
        'type',
        'resources',
        'course_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_free' => 'boolean',
        'resources' => 'array',
    ];

    /**
     * Scope a query to only include free lessons.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    /**
     * Get the course that owns the lesson.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the progress records for the lesson.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }
}