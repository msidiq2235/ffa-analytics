<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_delete_any_match_from_the_schedule(): void
    {
        $superAdmin = $this->createUser('Super Admin', 'superadmin@example.com');
        $match = $this->createMatch($superAdmin, 'Any FC');

        $this->actingAs($superAdmin)
            ->get(route('matches.index'))
            ->assertOk()
            ->assertSee('Hapus');

        $this->get(route('matches.index', ['view' => 'card']))
            ->assertOk()
            ->assertSee('Hapus Jadwal');

        $this->delete(route('matches.destroy', $match))
            ->assertRedirect(route('matches.index'))
            ->assertSessionHas('success', 'Jadwal pertandingan berhasil dihapus.');

        $this->assertDatabaseMissing('matches', ['id' => $match->id]);
    }

    public function test_coach_can_delete_only_matches_for_assigned_clubs(): void
    {
        $coach = $this->createUser('Pelatih', 'coach@example.com');
        $admin = $this->createUser('Admin', 'admin@example.com');
        Club::create(['name' => 'Coach FC', 'coach_id' => $coach->id]);
        $assignedMatch = $this->createMatch($admin, 'Coach FC');
        $otherMatch = $this->createMatch($admin, 'Other FC');

        $this->actingAs($coach)
            ->get(route('matches.index'))
            ->assertOk()
            ->assertSee('Hapus')
            ->assertSee('Coach FC')
            ->assertDontSee('Other FC');

        $this->delete(route('matches.destroy', $otherMatch))->assertNotFound();
        $this->assertDatabaseHas('matches', ['id' => $otherMatch->id]);

        $this->delete(route('matches.destroy', $assignedMatch))
            ->assertRedirect(route('matches.index'));
        $this->assertDatabaseMissing('matches', ['id' => $assignedMatch->id]);
    }

    public function test_admin_cannot_delete_matches(): void
    {
        $admin = $this->createUser('Admin', 'admin@example.com');
        $match = $this->createMatch($admin, 'Any FC');

        $this->actingAs($admin)
            ->get(route('matches.index'))
            ->assertOk()
            ->assertDontSee('Hapus');

        $this->delete(route('matches.destroy', $match))->assertForbidden();
        $this->assertDatabaseHas('matches', ['id' => $match->id]);
    }

    private function createMatch(User $creator, string $team): MatchSession
    {
        return MatchSession::create([
            'title' => $team,
            'opponent' => 'Opponent FC',
            'match_date' => now()->addDay(),
            'status' => 'Upcoming',
            'created_by' => $creator->id,
        ]);
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
