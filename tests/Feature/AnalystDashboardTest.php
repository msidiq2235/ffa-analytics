<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\Statistic;
use App\Models\User;
use App\Models\VectorDirection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalystDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_analyst_dashboard_shows_interactive_match_and_own_statistics_data(): void
    {
        $analyst = $this->createUser('Analis', 'analyst@example.com');
        $otherAnalyst = $this->createUser('Analis', 'other-analyst@example.com');
        $club = Club::create(['name' => 'Future FC']);
        $player = Player::create([
            'club_id' => $club->id,
            'name' => 'Top Scorer',
            'jersey_number' => 10,
            'status' => 'Aktif',
        ]);
        $liveMatch = $this->createMatch($analyst, $club->name, 'Sleman FC', 'Live');
        $upcomingMatch = $this->createMatch($analyst, $club->name, 'Bantul FC', 'Upcoming');

        Statistic::create([
            'match_id' => $liveMatch->id,
            'player_id' => $player->id,
            'analyst_id' => $analyst->id,
            'goal' => 2,
            'assist' => 1,
            'shooting_on_target' => 3,
            'successful_passes' => 12,
        ]);
        Statistic::create([
            'match_id' => $upcomingMatch->id,
            'player_id' => $player->id,
            'analyst_id' => $otherAnalyst->id,
            'goal' => 9,
            'assist' => 8,
        ]);
        VectorDirection::create([
            'match_id' => $liveMatch->id,
            'player_id' => $player->id,
            'analyst_id' => $analyst->id,
            'action_type' => 'PASS',
            'start_x' => 1,
            'start_y' => 2,
            'end_x' => 3,
            'end_y' => 4,
        ]);

        $this->actingAs($analyst)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pusat Pertandingan')
            ->assertDontSee('Pantau. Analisis. Tingkatkan.')
            ->assertSee('data-analyst-filter="Live"', false)
            ->assertSee('id="analyst-match-search"', false)
            ->assertSee('Future FC')
            ->assertSee('Sleman FC')
            ->assertSee('Top Scorer')
            ->assertViewHas('analystLiveCount', 1)
            ->assertViewHas('analystUpcomingCount', 1)
            ->assertViewHas('analystGoals', 2)
            ->assertViewHas('analystAssists', 1)
            ->assertViewHas('analystVectors', 1);
    }

    public function test_admin_analyst_mode_uses_global_statistics(): void
    {
        $admin = $this->createUser('Admin', 'admin@example.com');
        $analyst = $this->createUser('Analis', 'analyst@example.com');
        $club = Club::create(['name' => 'Global FC']);
        $player = Player::create(['club_id' => $club->id, 'name' => 'Global Player', 'status' => 'Aktif']);
        $match = $this->createMatch($admin, $club->name, 'Opponent FC', 'Selesai');

        Statistic::create([
            'match_id' => $match->id,
            'player_id' => $player->id,
            'analyst_id' => $analyst->id,
            'goal' => 4,
            'assist' => 2,
        ]);

        $this->actingAs($admin)
            ->withSession(['active_role' => 'Analis'])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('activeRole', 'Analis')
            ->assertViewHas('isOwnAnalyst', false)
            ->assertViewHas('analystGoals', 4)
            ->assertSee('Global Player');
    }

    private function createMatch(User $creator, string $team, string $opponent, string $status): MatchSession
    {
        return MatchSession::create([
            'title' => $team,
            'opponent' => $opponent,
            'match_date' => $status === 'Upcoming' ? now()->addDay() : now()->subDay(),
            'status' => $status,
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
