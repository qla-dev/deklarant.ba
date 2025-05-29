<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Runtime-only token (not saved in DB)
    public $plainTextToken = null;

    // Append token to JSON / array output
    protected $appends = ['token'];

    protected $fillable = [
        'username',
        'email',
        'password',
        'avatar',
        'first_name',
        'last_name',
        'phone_number',
        'skills',
        'designation',
        'website',
        'city',
        'country',
        'zip_code',
        'description',
        'company',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'skills' => 'array',
        'company' => 'array',
        'role' => 'string',
    ];

    /**
     * Accessor for runtime token (plain text)
     */
    public function getTokenAttribute()
    {
        return $this->plainTextToken;
    }

    /**
     * Accessor to format the joining_date as a date from created_at
     */
    public function getJoiningDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M, Y') : null;
    }

    /**
     * Retrieve a user by email.
     */
    public static function getUserByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * Create a user instance from local database data.
     */
    public static function fromLocalDatabase(array $userData)
    {
        return new self([
            'id'            => $userData['id'],
            'username'      => $userData['username'],
            'email'         => $userData['email'],
            'password'      => Hash::make($userData['password']),
            'avatar'        => $userData['avatar'],
            'first_name'    => $userData['first_name'] ?? null,
            'last_name'     => $userData['last_name'] ?? null,
            'phone_number'  => $userData['phone_number'] ?? null,
            'skills'        => $userData['skills'] ?? [],
            'designation'   => $userData['designation'] ?? null,
            'website'       => $userData['website'] ?? null,
            'city'          => $userData['city'] ?? null,
            'country'       => $userData['country'] ?? null,
            'zip_code'      => $userData['zip_code'] ?? null,
            'description'   => $userData['description'] ?? null,
            'role'          => $userData['role'] ?? 'user',
        ]);
    }
}
