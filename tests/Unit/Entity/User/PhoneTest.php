<?php

namespace Tests\Unit\Entity\User;

use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhoneTest extends TestCase
{
//    use RefreshDatabase;

    use DatabaseTransactions;

    public function testDefault(): void
    {
        $user = factory(User::class)->create([
            'phone' => null,
            'phone_verified' => false,
            'phone_verified_token' => null
        ]);

        self::assertFalse($user->isPhoneVerified());
    }

    public function testRequestEmptyPhone(): void
    {
        $user = factory(User::class)->create([
            'phone' => null,
            'phone_verified' => false,
            'phone_verified_token' => null
        ]);

        $this->expectExceptionMessage('Phone number is empty');
        $user->requestPhoneVerification(Carbon::now());
    }

    public function testRequest(): void
    {
        $user = factory(User::class)->create([
            'phone' => '798551515',
            'phone_verified' => false,
            'phone_verified_token' => null
        ]);

        $token = $user->requestPhoneVerification(Carbon::now());

        self::assertFalse($user->isPhoneVerified());
        self::assertNotEmpty($token);
    }

    public function testRequestWithOldPhone(): void
    {
        $user = factory(User::class)->create([
            'phone' => '798551515',
            'phone_verified' => true,
            'phone_verified_token' => null
        ]);

        self::assertTrue($user->isPhoneVerified());
        $user->requestPhoneVerification(Carbon::now());

        self::assertFalse($user->isPhoneVerified());
        self::assertNotEmpty($user->phone_verified_token);
    }

    public function testRequestAlreadySentTimeout(): void
    {
        $user = factory(User::class)->create([
            'phone' => '798551515',
            'phone_verified' => true,
            'phone_verified_token' => null
        ]);

        $user->requestPhoneVerification($now = Carbon::now());
        $user->requestPhoneVerification($now->copy()->addSeconds(500));

        self::assertFalse($user->isPhoneVerified());
    }

    public function testRequestAlreadySend(): void
    {
        $user = factory(User::class)->create([
            'phone' => '798551515',
            'phone_verified' => true,
            'phone_verified_token' => null
        ]);

        $user->requestPhoneVerification($now = Carbon::now());

        $this->expectExceptionMessage('Token is already requested.');
        $user->requestPhoneVerification($now->copy()->addSeconds(15));
    }

    public function testVerify(): void
    {
        $user = factory(User::class)->create([
            'phone' => '798551515',
            'phone_verified' => false,
            'phone_verified_token' => $token = 'token',
            'phone_verified_token_expire' => $now = Carbon::now()
        ]);

        self::assertFalse($user->isPhoneVerified());

        $user->verifyPhone($token , $now->copy()->subSeconds(15));

        self::assertTrue($user->isPhoneVerified());
    }

    public function testVerifyIncorrectToken(): void
    {
        $user = factory(User::class)->create([
            'phone' => '798551515',
            'phone_verified' => false,
            'phone_verified_token' => 'token',
            'phone_verified_token_expire' => $now = Carbon::now()
        ]);

//        self::assertFalse($user->isPhoneVerified());

        $this->expectExceptionMessage('Incorrect verify token.');

        $user->verifyPhone('other_token' , $now->copy()->subSecond(15));
    }

    public function testVerifyExpiredTime(): void
    {
        $user = factory(User::class)->create([
            'phone' => '798551515',
            'phone_verified' => false,
            'phone_verified_token' => $token = 'token',
            'phone_verified_token_expire' => $now = Carbon::now()
        ]);

        self::assertFalse($user->isPhoneVerified());
        $this->expectExceptionMessage('Token is expired.');
        $user->verifyPhone($token , $now->copy()->addSeconds(500));
    }




}
