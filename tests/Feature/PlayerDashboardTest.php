<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\Statistic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_dashboard_shows_real_data_for_the_player_with_the_same_name(): void
    {
        $user = $this->createUser('Pemain Contoh', 'Pemain');
        $club = Club::create(['name' => 'Akademi Contoh']);
        $player = Player::create([
            'club_id' => $club->id,
            'name' => $user->name,
            'position' => 'ST',
            'jersey_number' => 10,
            'status' => 'Aktif',
        ]);
        $match = MatchSession::create([
            'title' => $club->name,
            'opponent' => 'Lawan FC',
            'match_date' => now()->subDay(),
            'status' => 'Selesai',
            'created_by' => $user->id,
        ]);
        $match->players()->attach($player->id, ['position' => 'ST']);

        Statistic::create([
            'match_id' => $match->id,
            'player_id' => $player->id,
            'analyst_id' => $user->id,
            'goal' => 2,
            'assist' => 1,
            'shooting_on_target' => 3,
            'shooting_off_target' => 2,
            'successful_passes' => 12,
            'successful_dribble' => 4,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Pemain')
            ->assertSee('Pemain Contoh')
            ->assertSee('Akademi Contoh')
            ->assertSee('data-player-metric="matches">1</span>', false)
            ->assertSee('data-player-metric="goals">2</span>', false)
            ->assertSee('data-player-metric="assists">1</span>', false)
            ->assertSee('data-player-metric="shots">5</span>', false)
            ->assertSee('data-player-metric="passes">12</span>', false)
            ->assertSee('data-player-metric="dribbles">4</span>', false)
            ->assertSee('Lawan FC')
            ->assertDontSee('Yazid Ardiyan');
    }

    public function test_player_dashboard_explains_when_no_profile_matches_the_account_name(): void
    {
        $user = $this->createUser('Pemain Tanpa Profil', 'Pemain');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Profil pemain belum ditemukan')
            ->assertSee('nama akun Anda sama dengan nama pada data pemain');
    }

    private function createUser(string $name, string $role): User
    {
        return User::create([
            'name' => $name,
            'email' => str_replace(' ', '.', strtolower($name)) . '@example.com',
            'password' => 'password',
            'role' => $role,
            'status' => 'Aktif',
        ]);
    }
}
