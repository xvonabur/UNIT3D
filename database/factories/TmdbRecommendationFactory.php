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

namespace Database\Factories;

use App\Models\TmdbMovie;
use App\Models\TmdbTv;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TmdbRecommendation;

/** @extends Factory<TmdbRecommendation> */
class TmdbRecommendationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TmdbRecommendation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title'                     => $this->faker->sentence(),
            'poster'                    => $this->faker->word(),
            'vote_average'              => $this->faker->word(),
            'release_date'              => $this->faker->date(),
            'first_air_date'            => $this->faker->date(),
            'tmdb_movie_id'             => TmdbMovie::factory(),
            'recommended_tmdb_movie_id' => $this->faker->unique()->randomDigitNotNull(),
            'tmdb_tv_id'                => TmdbTv::factory(),
            'recommended_tmdb_tv_id'    => $this->faker->unique()->randomDigitNotNull(),
        ];
    }
}
