<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\CourseCategory
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $color
 * @property string|null $icon
 * @property int $sort_order
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseCategory active()
 * @method static \Database\Factories\CourseCategoryFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class CourseCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'sort_order',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Scope a query to only include active categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get the courses for the category.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}