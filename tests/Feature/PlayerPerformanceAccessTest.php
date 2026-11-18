<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerPerformanceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_performance_menu_opens_the_shared_report_for_their_profile_only(): void
    {
        $playerUser = $this->createUser('Own Player', 'Pemain');
        $club = Club::create(['name' => 'Own Club']);
        $ownPlayer = Player::create([
            'club_id' => $club->id,
            'name' => $playerUser->name,
            'status' => 'Aktif',
        ]);
        $otherPlayer = Player::create([
            'club_id' => $club->id,
            'name' => 'Other Player',
            'status' => 'Aktif',
        ]);

        $this->actingAs($playerUser)
            ->get(route('performa.index'))
            ->assertRedirect(route('reports.player.show', $ownPlayer));

        $this->get(route('reports.player.show', $ownPlayer))
            ->assertOk()
            ->assertViewIs('reports.player')
            ->assertSee('Own Player')
            ->assertDontSee('Other Player');

        $this->get(route('reports.player.show', $otherPlayer))->assertForbidden();
        $this->get(route('reports.player.pdf', $otherPlayer))->assertForbidden();
        $this->get(route('reports.index'))->assertRedirect(route('reports.player.show', $ownPlayer));
    }

    public function test_player_cannot_open_team_performance_reports(): void
    {
        $playerUser = $this->createUser('Own Player', 'Pemain');
        $club = Club::create(['name' => 'Own Club']);
        $player = Player::create([
            'club_id' => $club->id,
            'name' => $playerUser->name,
            'status' => 'Aktif',
        ]);
        $match = MatchSession::create([
            'title' => 'Own Club',
            'opponent' => 'Opponent FC',
            'match_date' => now()->subDay(),
            'status' => 'Selesai',
            'created_by' => $playerUser->id,
        ]);
        $match->players()->attach($player->id, ['position' => 'ST']);

        $this->actingAs($playerUser)
            ->get(route('reports.match.show', $match))
            ->assertForbidden();

        $this->get(route('reports.match.pdf', $match))->assertForbidden();
    }

    public function test_super_admin_can_select_and_open_any_player_report(): void
    {
        $admin = $this->createUser('Admin FFA', 'Super Admin');
        $club = Club::create(['name' => 'Academy Club']);
        $player = Player::create([
            'club_id' => $club->id,
            'name' => 'Selected Player',
            'status' => 'Aktif',
        ]);

        $this->actingAs($admin)
            ->get(route('performa.index'))
            ->assertOk()
            ->assertViewIs('performa.index')
            ->assertSee('Selected Player');

        $this->followingRedirects()
            ->get(route('performa.show', $player))
            ->assertOk()
            ->assertViewIs('reports.player')
            ->assertSee('Selected Player');
    }

    private function createUser(string $name, string $role): User
    {
        return User::create([
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
            'password' => 'password',
            'role' => $role,
            'status' => 'Aktif',
        ]);
    }
}
