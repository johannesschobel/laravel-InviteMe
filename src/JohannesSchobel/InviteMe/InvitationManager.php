<?php

namespace JohannesSchobel\InviteMe;

use Carbon\Carbon;
use Illuminate\Support\Str;
use JohannesSchobel\InviteMe\Models\Invitation;

class InvitationManager
{

    /**
     * Checks, if there is an active invitation for this email address
     *
     * @param String $email the Email to be checked
     * @param String $model The class to be checked
     * @param Boolean $open check only pending (e.g., is_accepted == false) Invitations
     * @return bool
     */
    public function hasInvitation($email, $model = null, $open = false) {
        $tmp = Invitation::where('email', '=', $email)->where('is_active', true)->where('model_type', '=', $model);
        if($open) {
            $tmp = $tmp->where('is_accepted', '=', false)->where('expires_at', '>=', Carbon::now());
        }

        $tmp = $tmp->first();

        if($tmp) {
            return true;
        }
        return false;
    }

    /**
     * Create a new invitation for a email address.
     *
     * @param $email
     * @param integer $days null if config value should be used!
     * @param object $model The model, for which this invitation is valid
     * @param null $custom
     * @return Invitation|null
     */
    public function createInvitation($email, $days = null, $model = null, $custom = null) {

        if($days == null) {
            $days = config('inviteme.days');
        }

        $invitation = null;

        // check, if the email has already an invitation
        if($this->hasInvitation($email, get_class($model), true)) {
            // there already exists an invitation -- get it
            $invitation = Invitation::where('email', '=', $email)
                                ->where('is_active', true)
                                ->where('model_type', '=', get_class($model))
                                ->first();
            return $invitation;
        }

        // generate a new invitation
        $tmp = new Invitation();
        $tmp->email = $email;
        $tmp->code = $this->generateCode($email);
        $tmp->expires_at = Carbon::now()->addDays($days)->toDateTimeString();

        $tmp->is_active = true;
        $tmp->is_accepted = false;
        $tmp->accepted_at = null;

        if($model != null) {
            $tmp->model_type = get_class($model);
            $tmp->model_id = $model->id;
        }

        $tmp->custom = $custom;

        $tmp->save();
        $invitation = $tmp;

        return $invitation;
    }

    /**
     * Accept an Invitation. The invitation, however, must be active, not accepted till now and still not expired!
     *
     * @param Invitation $invitation
     * @return bool
     */
    public function acceptInvitation(Invitation $invitation) {

        // check if it is expired yet
        if($invitation->isExpired()) {
            // it has already expired
            return false;
        }

        // update it!
        $invitation->is_accepted = true;
        $invitation->accepted_at = Carbon::now()->toDateTimeString();
        $invitation->save();

        return true;
    }

    /**
     * Withdraw ( = delete) an Invitation
     *
     * @param Invitation $invitation
     * @return bool
     */
    public function withdrawInvitation(Invitation $invitation) {
        $invitation->delete();
        return true;
    }

    /**
     * Extend an Invitation for another n days. now + n days!
     *
     * @param Invitation $invitation
     * @param $days
     * @return bool
     */
    public function extendInvitation(Invitation $invitation, $days) {
        $invitation->expires_at = Carbon::now()->addDays($days)->toDateTimeString();
        $invitation->save();

        return true;
    }

    /**
     * Generate a Code based on random values and the email address
     *
     * @param $email
     * @return string
     */
    private function generateCode($email) {
        $token = hash_hmac('sha256', Str::random(20) . $email, config('app.key'));
        return $token;
    }
}