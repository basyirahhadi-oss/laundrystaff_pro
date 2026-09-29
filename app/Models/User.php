<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's staff profile picture (Base64 data URL or asset path).
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        $staff = \Illuminate\Support\Facades\DB::table('staff')
            ->where('user_id', $this->id)
            ->select('profile_picture')
            ->first();

        // Fallback: check by staff_id if email prefix matches
        if (!$staff && !empty($this->email) && str_contains($this->email, '@')) {
            $prefix = explode('@', $this->email)[0];
            $staff = \Illuminate\Support\Facades\DB::table('staff')
                ->where('staff_id', strtoupper($prefix))
                ->orWhere('staff_id', $prefix)
                ->select('profile_picture')
                ->first();
        }

        if (!$staff || empty($staff->profile_picture)) {
            return null;
        }

        $pic = $staff->profile_picture;
        if (str_starts_with($pic, 'data:image') || str_starts_with($pic, 'http')) {
            return $pic;
        }

        if (file_exists(public_path('uploads/staff/' . $pic))) {
            return asset('uploads/staff/' . $pic);
        }

        return null;
    }
}
