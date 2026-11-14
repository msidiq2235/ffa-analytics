<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LineupRosterTest extends TestCase
{
    use RefreshDatabase;

    public function test_lineup_only_lists_active_players_from_the_match_team(): void
    {
        $admin = $this->createAdmin();
        $homeClub = Club::create(['name' => 'Home FC']);
        $awayClub = Club::create(['name' => 'Away FC']);
        $homePlayer = Player::create([
            'club_id' => $homeClub->id,
            'name' => 'Home Player',
            'status' => 'Aktif',
        ]);
        Player::create([
            'club_id' => $awayClub->id,
            'name' => 'Away Player',
            'status' => 'Aktif',
        ]);
        Player::create([
            'club_id' => $homeClub->id,
            'name' => 'Inactive Home Player',
            'status' => 'Nonaktif',
        ]);
        $match = $this->createMatch($admin, 'Home FC', 'Away FC');

        $this->actingAs($admin)
            ->get(route('matches.lineup', $match))
            ->assertOk()
            ->assertSee('Home Player')
            ->assertDontSee('Away Player')
            ->assertDontSee('Inactive Home Player');
    }

    public function test_lineup_does_not_fall_back_to_all_players_when_match_team_is_missing(): void
    {
        $admin = $this->createAdmin();
        $club = Club::create(['name' => 'Registered FC']);
        Player::create([
            'club_id' => $club->id,
            'name' => 'Registered Player',
            'status' => 'Aktif',
        ]);
        $match = $this->createMatch($admin, 'Unregistered Team', 'Opponent');

        $this->actingAs($admin)
            ->get(route('matches.lineup', $match))
            ->assertOk()
            ->assertDontSee('Registered Player')
            ->assertSee('Belum ada data pemain aktif yang terdaftar di sistem.');
    }

    public function test_lineup_update_rejects_players_from_another_team(): void
    {
        $admin = $this->createAdmin();
        $homeClub = Club::create(['name' => 'Home FC']);
        $awayClub = Club::create(['name' => 'Away FC']);
        $awayPlayer = Player::create([
            'club_id' => $awayClub->id,
            'name' => 'Away Player',
            'status' => 'Aktif',
        ]);
        $match = $this->createMatch($admin, 'Home FC', 'Away FC');

        $this->actingAs($admin)
            ->from(route('matches.lineup', $match))
            ->put(route('matches.lineup.update', $match), [
                'players' => [
                    $awayPlayer->id => [
                        'selected' => '1',
                        'position' => 'ST',
                    ],
                ],
            ])
            ->assertSessionHasErrors('player_ids.0');

        $this->assertDatabaseMissing('match_players', [
            'match_id' => $match->id,
            'player_id' => $awayPlayer->id,
        ]);
    }

    private function createMatch(User $creator, string $team, string $opponent): MatchSession
    {
        return MatchSession::create([
            'title' => $team,
            'opponent' => $opponent,
            'match_date' => now()->addDay(),
            'status' => 'Upcoming',
            'created_by' => $creator->id,
        ]);
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'Admin',
            'status' => 'Aktif',
        ]);
    }
}
