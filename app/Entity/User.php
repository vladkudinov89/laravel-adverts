<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Mockery\Exception\InvalidArgumentException;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property boolean $phone_auth
 * @property boolean $phone_verified
 * @property string $password
 * @property string $verify_token
 * @property string $phone_verified_token
 * @property Carbon $phone_verified_token_expire
 * @property string $status
 * @property string $role
 * */
class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    public const ROLE_USER = 'user';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_ADMIN = 'admin';


    protected $fillable = [
        'name', 'last_name', 'email', 'phone', 'password', 'status', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token', 'verify_token'
    ];

    protected $casts = [
        'phone_auth' => 'boolean',
        'phone_verified' => 'boolean',
        'phone_verified_token_expire' => 'datetime'
    ];

    public static function rolesList(): array
    {
        return [
          self::ROLE_USER => 'user',
          self::ROLE_MODERATOR => 'moderator',
          self::ROLE_ADMIN => 'admin'
        ];
    }

    public static function statusList()
    {
        return [
            self::STATUS_WAIT => 'wait',
            self::STATUS_ACTIVE => 'active'
        ];
    }

    public static function register(
        string $name,
        string $email,
        string $password
    ): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_token' => Str::uuid(),
            'status' => self::STATUS_WAIT
        ]);
    }

    public static function new(
        string $name,
        string $email
    ): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(Str::random()),
            'status' => self::STATUS_ACTIVE
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already vedified.');
        }

        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null
        ]);
    }

    public function changeRole($role): void
    {
        if (!\in_array($role, self::rolesList(), true)) {
            throw new InvalidArgumentException('Undefined role"' . $role . '"');
        }

         if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }

        $this->update(['role' => $role]);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUserRole(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isModerator(): bool
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    public function unverifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_verified_token = null;
        $this->phone_verified_token_expire = null;
        $this->saveOrFail();
    }

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified;
    }

    public function requestPhoneVerification(Carbon $now): string
    {
        if (empty($this->phone)) {
            throw new \DomainException('Phone number is empty.');
        }

        if (
            !empty($this->phone_verified_token)
            &&
            $this->phone_verified_token_expire
            &&
            $this->phone_verified_token_expire->gt($now)
        ) {
            throw new \DomainException('Token is already requested.');
        }

        $this->phone_verified = false;
        $this->phone_verified_token = (string) random_int(10000,99999);
        $this->phone_verified_token_expire = $now->copy()->addSeconds(300);
        $this->saveOrFail();

        return $this->phone_verified_token;
    }

    public function verifyPhone(string $token , Carbon $now): void
    {
        if($this->phone_verified_token !== $token)
        {
            throw new \DomainException('Incorrect verify token.');
        }

        if($this->phone_verified_token_expire->lt($now))
        {
            throw new \DomainException('Token is expired.');
        }

        $this->phone_verified = true;
        $this->phone_verified_token = null;
        $this->phone_verified_token_expire = null;
        $this->saveOrFail();
    }

    public function isPhoneAuthEnabled(): bool
    {
        return (bool)$this->phone_auth;
    }

    public function disablePhoneAuth(): void
    {
        $this->phone_auth = false;
        $this->saveOrFail();
    }

    public function enablePhoneAuth(): void
    {
        if (!empty($this->phone) && !$this->isPhoneVerified()) {
            throw new \DomainException('Phone number is empty.');
        }
        $this->phone_auth = true;
        $this->saveOrFail();
    }

    public function hasFilledProfile(): bool
    {
        return !empty($this->name) && !empty($this->last_name) && $this->isPhoneVerified();
    }
}
