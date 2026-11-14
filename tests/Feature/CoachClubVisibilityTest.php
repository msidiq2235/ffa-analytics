<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoachClubVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_only_sees_and_opens_assigned_clubs(): void
    {
        $coach = $this->createCoach();
        $assignedClub = Club::create([
            'name' => 'Future FC',
            'coach_id' => $coach->id,
        ]);
        $otherClub = Club::create(['name' => 'Rival FC']);

        $this->actingAs($coach)
            ->get(route('clubs.index'))
            ->assertOk()
            ->assertSee('Future FC')
            ->assertDontSee('Rival FC');

        $this->get(route('clubs.show', $assignedClub))
            ->assertOk()
            ->assertSee('Future FC');

        $this->get(route('clubs.show', $otherClub))
            ->assertNotFound();

        $this->get(route('clubs.edit', $otherClub))
            ->assertNotFound();
    }

    public function test_admin_sees_all_clubs_in_coach_mode(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'Admin',
            'status' => 'Aktif',
        ]);
        $coach = $this->createCoach();

        Club::create(['name' => 'Assigned FC', 'coach_id' => $coach->id]);
        Club::create(['name' => 'Unassigned FC']);

        $this->actingAs($admin)
            ->withSession(['active_role' => 'Pelatih'])
            ->get(route('clubs.index'))
            ->assertOk()
            ->assertSee('Assigned FC')
            ->assertSee('Unassigned FC');
    }

    private function createCoach(): User
    {
        return User::create([
            'name' => 'Pelatih',
            'email' => 'coach@example.com',
            'password' => 'password',
            'role' => 'Pelatih',
            'status' => 'Aktif',
        ]);
    }
}
