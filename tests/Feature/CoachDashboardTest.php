<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\Statistic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoachDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_coach_dashboard_only_shows_assigned_teams_and_their_data(): void
    {
        $coach = $this->createUser('Pelatih', 'coach@example.com');
        $analyst = $this->createUser('Analis', 'analyst@example.com');
        $club = Club::create(['name' => 'Future FC', 'coach_id' => $coach->id]);
        $otherClub = Club::create(['name' => 'Rival FC']);

        $player = Player::create([
            'club_id' => $club->id,
            'name' => 'Pemain Future',
            'status' => 'Aktif',
            'position' => 'ST',
            'jersey_number' => 9,
        ]);
        $otherPlayer = Player::create([
            'club_id' => $otherClub->id,
            'name' => 'Pemain Rival',
            'status' => 'Aktif',
        ]);

        MatchSession::create([
            'title' => $club->name,
            'match_date' => now()->addDays(2),
            'opponent' => 'Rival Akademi',
            'status' => 'Upcoming',
            'created_by' => $analyst->id,
        ]);
        MatchSession::create([
            'title' => $otherClub->name,
            'match_date' => now()->addDays(3),
            'status' => 'Upcoming',
            'created_by' => $analyst->id,
        ]);

        Statistic::create([
            'match_id' => MatchSession::where('title', $club->name)->value('id'),
            'player_id' => $player->id,
            'analyst_id' => $analyst->id,
            'goal' => 2,
            'assist' => 1,
        ]);
        Statistic::create([
            'match_id' => MatchSession::where('title', $otherClub->name)->value('id'),
            'player_id' => $otherPlayer->id,
            'analyst_id' => $analyst->id,
            'goal' => 7,
            'assist' => 4,
        ]);

        $this->actingAs($coach)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Future FC')
            ->assertSee('Pemain Future')
            ->assertSee('Rival Akademi')
            ->assertDontSee('Pemain Rival')
            ->assertDontSee('Rival FC')
            ->assertSee('Total Gol')
            ->assertSee('>2</td>', false);
    }

    public function test_admin_can_switch_to_coach_dashboard_and_see_global_data(): void
    {
        $admin = $this->createUser('Admin', 'admin@example.com');
        $coach = $this->createUser('Pelatih', 'coach@example.com');
        $coachClub = Club::create(['name' => 'Coach FC', 'coach_id' => $coach->id]);
        $otherClub = Club::create(['name' => 'Unassigned FC']);
        $coachPlayer = Player::create(['club_id' => $coachClub->id, 'name' => 'Coach Player', 'status' => 'Aktif']);
        $otherPlayer = Player::create(['club_id' => $otherClub->id, 'name' => 'Other Player', 'status' => 'Aktif']);
        $firstMatch = MatchSession::create([
            'title' => $coachClub->name,
            'match_date' => now()->addDays(2),
            'status' => 'Upcoming',
            'created_by' => $admin->id,
        ]);
        $secondMatch = MatchSession::create([
            'title' => $otherClub->name,
            'match_date' => now()->addDays(3),
            'status' => 'Upcoming',
            'created_by' => $admin->id,
        ]);
        Statistic::create([
            'match_id' => $firstMatch->id,
            'player_id' => $coachPlayer->id,
            'analyst_id' => $admin->id,
            'goal' => 2,
            'assist' => 1,
        ]);
        Statistic::create([
            'match_id' => $secondMatch->id,
            'player_id' => $otherPlayer->id,
            'analyst_id' => $admin->id,
            'goal' => 7,
            'assist' => 4,
        ]);

        $this->actingAs($admin)
            ->post(route('switch.role'), ['active_role' => 'Pelatih'])
            ->assertRedirect()
            ->assertSessionHas('active_role', 'Pelatih');

        $response = $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('activeRole', 'Pelatih')
            ->assertViewHas('isAssignedCoach', false)
            ->assertViewHas('coachClubs', fn ($clubs) => $clubs->count() === 2)
            ->assertViewHas('coachPlayers', fn ($players) => $players->count() === 2)
            ->assertViewHas('upcomingMatches', 2)
            ->assertViewHas('totalGoals', 9)
            ->assertSee('Unassigned FC')
            ->assertSee('Other Player')
            ->assertSee('>9</p>', false);

        $this->assertSame('Pelatih', $response->viewData('activeRole'));
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
