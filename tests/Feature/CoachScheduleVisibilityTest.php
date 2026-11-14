<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoachScheduleVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_only_sees_matches_for_assigned_clubs(): void
    {
        $coach = $this->createUser('Pelatih', 'coach@example.com');
        $admin = $this->createUser('Admin', 'admin@example.com');
        $club = Club::create(['name' => 'Future FC', 'coach_id' => $coach->id]);
        $otherClub = Club::create(['name' => 'Rival FC']);
        $player = Player::create([
            'club_id' => $club->id,
            'name' => 'Future Player',
            'status' => 'Aktif',
        ]);

        $this->createMatch($admin, $club->name)->players()->attach($player);
        $lineupMatch = $this->createMatch($admin, 'Academy Showcase');
        $lineupMatch->players()->attach($player);
        $this->createMatch($admin, $otherClub->name);

        $this->actingAs($coach)
            ->get(route('matches.index'))
            ->assertOk()
            ->assertSee('Future FC')
            ->assertDontSee('Academy Showcase')
            ->assertDontSee('Rival FC');
    }

    public function test_admin_sees_all_matches_when_using_coach_or_analyst_mode(): void
    {
        $admin = $this->createUser('Admin', 'admin@example.com');
        $coach = $this->createUser('Pelatih', 'coach@example.com');
        $coachClub = Club::create(['name' => 'Coach FC', 'coach_id' => $coach->id]);
        $otherClub = Club::create(['name' => 'Other FC']);
        $this->createMatch($admin, $coachClub->name);
        $this->createMatch($admin, $otherClub->name);

        foreach (['Pelatih', 'Analis'] as $role) {
            $this->actingAs($admin)
                ->withSession(['active_role' => $role])
                ->get(route('matches.index'))
                ->assertOk()
                ->assertSee('Coach FC')
                ->assertSee('Other FC');
        }
    }

    private function createMatch(User $creator, string $team): MatchSession
    {
        return MatchSession::create([
            'title' => $team,
            'match_date' => now()->addDays(2),
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
