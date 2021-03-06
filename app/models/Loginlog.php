<?php

class Loginlog extends Eloquent {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loginlog';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = array('password', 'remember_token');


    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    // public function getAuthIdentifier()
    // {
    //  return $this->getKey();
    // }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    // public function getAuthPassword()
    // {
    //  return $this->password;
    // }

    public function user()
    {
        return $this->belongsTo("User");
    }
}
