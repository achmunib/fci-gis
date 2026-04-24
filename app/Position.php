<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "opd";
    protected $fillable = [
        'nama_opd','sub_opd','upt',
    ];

    /**
     * Get outlet name_link attribute.
     *
     * @return string
     */
    public function getNameLinkAttribute()
    {
        $title = __('app.show_detail_title', [
            'name' => $this->name, 'type' => __('outlet.outlet'),
        ]);
        $link = '<a href="'.route('outlets.show', $this).'"';
        $link .= ' title="'.$title.'">';
        $link .= $this->name;
        $link .= '</a>';

        return $link;
    }

    /**
     * Outlet belongs to User model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

}
