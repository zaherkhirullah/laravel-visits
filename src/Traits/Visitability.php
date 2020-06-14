<?php

namespace Hayrullah\LaravelVisits\Traits;

use Hayrullah\LaravelVisits\Models\Like;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * This file is part of Laravel visits,.
 *
 * @license MIT
 */
trait Visitability
{
    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function visits()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    /**
     * Return a collection with the User visitsd Model.
     * The Model needs to have the visitsable trait.
     *
     * @param  $class *** Accepts for example: Post::class or 'App\Post' ****
     *
     * @return Collection
     */
    public function visitors($class)
    {
        return $this->visits()->where('visitable_type', $class)->with('visitable')->get()->mapWithKeys(function ($item) {
            if (isset($item['visitable'])) {
                return [$item['visitable']->id => $item['visitable']];
            }

            return [];
        });
    }

    /**
     * Add the object to the User visits.
     * The Model needs to have the visitsable trai.
     *
     * @param object $object
     */
    public function addVisit($object)
    {
        $object->addVisit($this->id);
    }

    /**
     * Remove the Object from the user visits.
     * The Model needs to have the visitable trai.
     *
     * @param object $object
     */
    public function removeVisit($object)
    {
        $object->removeVisit($this->id);
    }

    /**
     * Toggle the visits status from this Object from the user visits.
     * The Model needs to have the visitable trai.
     *
     * @param object $object
     */
    public function toggleVisit($object)
    {
        $object->toggleVisits($this->id);
    }

    /**
     * Check if the user has visits this Object
     * The Model needs to have the visitable trai.
     *
     * @param object $object
     *
     * @return bool
     */
    public function isVisited($object)
    {
        return $object->isVisited($this->id);
    }

    /**
     * Check if the user has visited this Object
     * The Model needs to have the visitable trai.
     *
     * @param object $object
     *
     * @return bool
     */
    public function hasVisit($object)
    {
        return $object->isvisitsd($this->id);
    }
}
