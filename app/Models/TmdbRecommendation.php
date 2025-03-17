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
 * App\Models\TmdbRecommendation.
 *
 * @property int         $id
 * @property string      $title
 * @property string|null $poster
 * @property string|null $vote_average
 * @property string|null $release_date
 * @property string|null $first_air_date
 * @property int|null    $tmdb_movie_id
 * @property int|null    $recommended_tmdb_movie_id
 * @property int|null    $tmdb_tv_id
 * @property int|null    $recommended_tmdb_tv_id
 */
class TmdbRecommendation extends Model
{
    /** @use HasFactory<\Database\Factories\TmdbRecommendationFactory> */
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TmdbMovie, $this>
     */
    public function movie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TmdbMovie::class, 'tmdb_movie_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TmdbTv, $this>
     */
    public function tv(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TmdbTv::class, 'tmdb_tv_id');
    }
}
