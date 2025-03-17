<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TmdbSeason.
 *
 * @property int         $id
 * @property int         $tmdb_tv_id
 * @property int         $season_number
 * @property string|null $name
 * @property string|null $overview
 * @property string|null $poster
 * @property string|null $air_date
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TmdbSeason extends Model
{
    /** @use HasFactory<\Database\Factories\TmdbSeasonFactory> */
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Has Many Torrents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Torrent, $this>
     */
    public function torrents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Torrent::class, 'tmdb_tv_id', 'tmdb_tv_id')->whereRelation('category', 'tv_meta', '=', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TmdbTv, $this>
     */
    public function tv(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TmdbTv::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TmdbEpisode, $this>
     */
    public function episodes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TmdbEpisode::class)
            ->oldest('episode_number');
    }
}
