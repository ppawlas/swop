<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'organizations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name', 'active'];

    /**
     * Get the users belonging to the organization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * Get the indicators for the organization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function indicators()
    {
        return $this->belongsToMany('App\Indicator')->withPivot('coefficient')->withTimestamps();
    }

    /**
     * Get the groups belonging to the organization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany('App\Group');
    }

}
