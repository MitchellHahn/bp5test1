<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\node
 *
 * @property int $id
 * @property string|null $question
 * @property string|null $answer
 * @property-read \App\Models\relation|null $relation
 * @method static \Illuminate\Database\Eloquent\Builder|node newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|node newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|node query()
 * @method static \Illuminate\Database\Eloquent\Builder|node whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|node whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|node whereQuestion($value)
 */
	class node extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\relation
 *
 * @property int $id
 * @property int $node_yes
 * @property int $node_no
 * @property int $parent_node
 * @property-read \App\Models\node $no
 * @property-read \App\Models\node|null $node
 * @property-read \App\Models\node $yes
 * @method static \Illuminate\Database\Eloquent\Builder|relation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|relation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|relation query()
 * @method static \Illuminate\Database\Eloquent\Builder|relation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|relation whereNodeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|relation whereNodeYes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|relation whereParentNode($value)
 */
	class relation extends \Eloquent {}
}

