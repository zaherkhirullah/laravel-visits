<?php

namespace Hayrullah\LaravelVisits\Traits;

use Hayrullah\LaravelVisits\Models\Visit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * This file is part of Laravel Visit,
 *
 * @license MIT
 * @package ChristianKuri/laravel-favorite
 *
 * Copyright (c) 2016 Christian Kuri
 */
trait Visitable
{
    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @return MorphMany
     */
    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    /**
     * Add this Object to the user visits
     *
     * @param int $user_id [if  null its added to the auth user]
     */
    public function addVisit($user_id = null)
    {
        $user_id = $this->getUserId($user_id);

        $visit = new Visit(['user_id' => $user_id]);

        $visit->ip = $this->getIp();
        $visit->previous = request()->previous;

        $details = $this->getIpDetails();
        $visit->domain = url('/');
        $visit->iso_code = $details->country ?? null;
        $visit->country = $details->country ?? null;
        $visit->city = $details->city ?? null;
        $visit->state = $details->region ?? null;
        $visit->postal_code = $details->postal ?? null;
        $visit->location = $details->loc ?? null;
        $visit->lat = explode(',', $details->loc)[0] ?? null;
        $visit->lon = explode(',', $details->loc)[1] ?? null;
        $visit->timezone = $details->timezone ?? null;
        $visit->org = $details->org ?? null;

        // Get Full Src
        //$visit->src = $this->getFullSrc($request['fullSrc']);
        //parse_str(parse_url($request['full_src'], PHP_URL_QUERY), $params);
        //$visit->device = get_item_if_exists($params, 'device');
        //$visit->device_model = get_item_if_exists($params, 'devicemodel');
        //$visit->utm_campaign = get_item_if_exists($params, 'utm_campaign');
        //$visit->utm_content = get_item_if_exists($params, 'utm_content');
        //$visit->utm_medium = get_item_if_exists($params, 'utm_medium');
        //$visit->utm_source = get_item_if_exists($params, 'utm_source');
        //$visit->keyword = get_item_if_exists($params, 'keyword');
        //$visit->placement = get_item_if_exists($params, 'placement');
        //$visit->ad_position = get_item_if_exists($params, 'adposition');
        //$visit->match_type = get_item_if_exists($params, 'matchtype');
        $this->visits()->save($visit);

        return $visit;
    }

    /**
     * Remove this Object from the user visits
     *
     * @param int $user_id [if  null its added to the auth user]
     *
     */
    public function removeVisit($user_id = null)
    {
        $this->visits()->where('user_id', $this->getUserId($user_id))->delete();
    }

    /**
     * Toggle the favorite status from this Object
     *
     * @param int $user_id [if  null its added to the auth user]
     */
    public function toggleVisit($user_id = null)
    {
        $this->isVisited($user_id) ? $this->removeVisit($user_id) : $this->addVisit($user_id);
    }

    /**
     * Check if the user has favorited this Object
     *
     * @param int $user_id [if  null its added to the auth user]
     *
     * @return boolean
     */
    public function isVisited($user_id = null)
    {
        return $this->visits()->where('user_id', ($user_id) ? $user_id : Auth::id())->exists();
    }

    /**
     * Return a collection with the Users who marked as favorite this Object.
     *
     * @return Collection
     */
    public function visitedBy()
    {
        $user = $this->visits()->with('user')->get();

        return $user->mapWithKeys(function ($item) {
            return [$item['user']->id => $item['user']];
        });
    }

    /**
     * Count the number of visits
     *
     * @return int
     */
    public function getVisitsCountAttribute()
    {
        return $this->visits()->count();
    }

    /**
     * @return visitsCount attribute
     */
    public function visitsCount()
    {
        return $this->visitsCount;
    }

    /**
     * Add deleted observer to delete visits registers
     *
     * @return void
     */
    public static function bootVisitable()
    {
        static::deleted(
            function ($model) {
                $model->visits()->delete();
            }
        );
    }

    private function getUserId($user_id = null)
    {

        $user_id = ($user_id) ? $user_id : null;
        if (!$user_id) {
            \auth()->check() ? Auth::id() : null;
        }

        return $user_id;
    }

    private function getIp()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        return request()->ip();
    }

    private function getIpDetails()
    {
        $details = json_decode(file_get_contents("https://ipinfo.io/{$ip}?token=f5e0d2a13bc766"));
        if (!$details) {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
        }

//        $details = @json_decode(@file_get_contents('http://ipinfo.io/'.$geo->ip));

        return $details;
    }

}