<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'start_date', 'end_date'];

    /**
     * Get the users belonging to the report.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('view_self', 'view_all')->withTimestamps();
    }

    /**
     * Get the indicators belonging to the report.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function indicators()
    {
        return $this->belongsToMany('App\Indicator')->withPivot('show_value', 'show_points')->withTimestamps();
    }

}
