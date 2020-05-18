<?php

namespace Hayrullah\LaravelVisits\Traits;

use Hayrullah\LaravelVisits\Models\Visit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * This file is part of Laravel visits,
 *
 * @license MIT
 * @package Hayrullah/laravel-visits
 *
 * Copyright (c) 2016 Christian Kuri
 */
trait Visitability
{
    /**
     * Define a one-to-many relationship.
     *
     * @return HasMany
     */
    public function visitss()
    {
        return $this->hasMany(Visit::class, 'user_id');
    }

    /**
     * Return a collection with the User visitsd Model.
     * The Model needs to have the visitsable trait
     * 
     * @param  $class *** Accepts for example: Post::class or 'App\Post' ****
     *
     * @return Collection
     */
    public function visits($class)
    {
        return $this->visitss()->where('visitsable_type', $class)->with('visitsable')->get()->mapWithKeys(function ($item) {
            if (isset($item['visitsable'])) {
                return [$item['visitsable']->id=>$item['visitsable']];
            }

            return [];
        });
    }

    /**
     * Add the object to the User visitss.
     * The Model needs to have the visitsable trai
     * 
     * @param Object $object
     */
    public function addvisits($object)
    {
        $object->addvisits($this->id);
    }

    /**
     * Remove the Object from the user visitss.
     * The Model needs to have the visitsable trai
     * 
     * @param Object $object
     */
    public function removevisits($object)
    {
        $object->removevisits($this->id);
    }

    /**
     * Toggle the visits status from this Object from the user visitss.
     * The Model needs to have the visitsable trai
     * 
     * @param Object $object
     */
    public function togglevisits($object)
    {
        $object->togglevisits($this->id);
    }

    /**
     * Check if the user has visitsd this Object
     * The Model needs to have the visitsable trai
     * 
     * @param Object $object
     * @return boolean
     */
    public function isvisitsd($object)
    {
        return $object->isvisitsd($this->id);
    }

    /**
     * Check if the user has visitsd this Object
     * The Model needs to have the visitsable trai
     * 
     * @param Object $object
     * @return boolean
     */
    public function hasvisitsd($object)
    {
        return $object->isvisitsd($this->id);
    }
}