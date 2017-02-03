<?php

namespace JohannesSchobel\InviteMe\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'code', 'expires_at', 'is_active', 'is_accepted', 'accepted_at'];
    protected $dates = ['expires_at', 'accepted_at'];

    /**
     * Checks if the Invitation is expired
     */
    public function isExpired() {
        if ($this->expires_at < Carbon::now()) {
            return true;
        }
        return false;
    }

    /**
     * returns the model of the given class
     * or null if the class was not found!
     *
     * @return mixed
     */
    public function getModel() {
        $class = $this->model_type;

        if(! class_exists($class)) {
            // the class does not exist
            return null;
        }

        // get the object of the respective class!
        $object = call_user_func($class . '::findOrFail', $this->model_id);

        return $object;
    }
}
