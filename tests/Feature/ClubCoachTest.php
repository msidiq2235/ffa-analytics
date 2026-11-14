<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubCoachTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_club_can_be_assigned_to_a_registered_coach(): void
    {
        $admin = $this->createUser('Super Admin', 'admin@example.com');
        $coach = $this->createUser('Pelatih', 'coach@example.com');

        $this->actingAs($admin)
            ->post(route('clubs.store'), [
                'name' => 'Future FC',
                'coach_id' => $coach->id,
            ])
            ->assertRedirect(route('clubs.index'));

        $club = Club::where('name', 'Future FC')->firstOrFail();
        $this->assertSame($coach->id, $club->coach_id);
        $this->assertSame($coach->id, $club->coach->id);
    }

    public function test_only_users_with_the_coach_role_can_be_assigned_to_a_club(): void
    {
        $admin = $this->createUser('Super Admin', 'admin@example.com');
        $analyst = $this->createUser('Analis', 'analyst@example.com');

        $this->actingAs($admin)
            ->from(route('clubs.create'))
            ->post(route('clubs.store'), [
                'name' => 'Future FC',
                'coach_id' => $analyst->id,
            ])
            ->assertSessionHasErrors('coach_id');

        $this->assertDatabaseMissing('clubs', ['name' => 'Future FC']);
    }

    public function test_the_assigned_coach_is_available_in_club_edit_and_display_pages(): void
    {
        $admin = $this->createUser('Super Admin', 'admin@example.com');
        $coach = $this->createUser('Pelatih', 'coach@example.com');
        $club = Club::create([
            'name' => 'Future FC',
            'coach_id' => $coach->id,
        ]);

        $this->actingAs($admin)
            ->get(route('clubs.edit', $club))
            ->assertOk()
            ->assertSee($coach->email);

        $this->get(route('clubs.index'))
            ->assertOk()
            ->assertSee($coach->name);

        $this->get(route('clubs.show', $club))
            ->assertOk()
            ->assertSee($coach->name);
    }

    private function createUser(string $role, string $email): User
    {
        return User::create([
            'name' => $role,
            'email' => $email,
            'password' => 'password',
            'role' => $role,
            'status' => 'Aktif',
        ]);
    }
}
