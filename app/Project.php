<?php

namespace App;

use Actuallymab\LaravelComment\Commentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model 
{
    use SearchableTrait;
    use HasSlug;
    use Commentable;

    protected $table = 'projects';
    public $timestamps = true;
    protected $fillable = array('title', 'slug','user_id', 'category_id', 'description', 'start_date', 'deadline', 'location_id', 'timezone', 'impact', 'user_count', 'estimated_hours', 'resources_link', 'additional_info', 'flexible_start', 'on_site', 'renew','complete');
    protected $mustBeApproved = false;
    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'deadline'
    ];

    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'title' => 10,
            'description' => 10,
            'impact' => 2,
            'additional_info' => 2,
            'users.email' => 1,
            'users.first_name' => 1,
            'users.last_name' => 1,
            'categories.name' => 1,
            'skills.name' => 5,
        ],
        'joins' => [
            'users' => ['projects.user_id','users.id'],
            'categories' => ['projects.category_id','categories.id'],
            'project_skill' => ['project_skill.project_id','projects.id'],
            'skills' => ['skills.id','project_skill.skill_id'],
        ],
    ];

    static $rules = [
        'project_id' => 'sometimes|required',
        'title' => 'required',
        'category_id' => 'required',
        'description' => 'required',
        'acceptance_criteria' => 'required',
        'start_date' => 'required',
        'deadline' => 'required',
        'location_id' => 'required',
        'skills' => 'required',
        'user_count' => 'required|numeric|max:3',
        'estimated_hours' => 'required|numeric|max:20',
        'resources_link' => 'nullable|url',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function Owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function Category()
    {
        return $this->belongsTo('App\Category');
    }

    public function Location()
    {
        return $this->belongsTo('App\Location');
    }

    public function Users()
    {
        return $this->belongsToMany('App\User');
    }

    public function AcceptanceCriteria()
    {
        return $this->hasMany('App\AcceptanceCriteria');
    }

    public function Skills()
    {
        return $this->belongsToMany('App\Skill');
    }

    public function Favorites()
    {
        return $this->belongsToMany('App\User', 'favorites', 'project_id', 'user_id')->withTimeStamps();
    }

    public function Sponsors(){
        return $this->belongsToMany('App\User', 'project_sponsor');
    }


    /*
    |---------------------------------------------------------------|
    |Favorite Methods
    |---------------------------------------------------------------|
    */
    public function favorited()
    {
        return (bool) Favorite::where('user_id', Auth::id())
            ->where('project_id', $this->id)
            ->first();
    }

    public function favoriteCount(){
        return $this->Favorites()->count();
    }

    /*
    |---------------------------------------------------------------|
    |Sponsor Methods
    |---------------------------------------------------------------|
    */

    public function isSponsored(){
        return $this->Sponsors()->exists();
    }

    /*
    |---------------------------------------------------------------|
    |User Methods
    |---------------------------------------------------------------|
    */

    public function hasUser($user){
        return $this->Users()->where('users.id',$user->id)->exists();
    }

}