<?php

namespace Tests\Unit\Entity\User;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    public function testChange(): void
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);

        self::assertFalse($user->isAdmin());

        $user->changeRole(User::ROLE_ADMIN);

        self::assertTrue($user->isAdmin());
    }

    public function testAlready(): void
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);

        $this->expectExceptionMessage('Role is already assigned.');

        $user->changeRole(User::ROLE_ADMIN);
    }

    public function testChangeRoleToModerator(): void
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);

        $user->changeRole(User::ROLE_MODERATOR);

        self::assertEquals(User::ROLE_MODERATOR , $user->role);
        self::assertFalse($user->isAdmin());
        self::assertFalse($user->isUserRole());
    }
}
