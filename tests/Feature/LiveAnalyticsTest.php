<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\MatchSession;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_live_analytics_uses_popular_names_and_shows_goalkeeper_statistics(): void
    {
        [$analyst, $match, $goalkeeper] = $this->createMatchWithPlayers();

        $this->actingAs($analyst)
            ->get(route('matches.live', $match))
            ->assertOk()
            ->assertSee('Keeper One')
            ->assertDontSee('Full Goalkeeper Name')
            ->assertSee('class="flex flex-col items-center cursor-pointer pitch-player group', false)
            ->assertSee('data-name="Keeper One"', false)
            ->assertSee('data-position="GK"', false)
            ->assertSee("document.getElementById('pitch-players').addEventListener('click'", false)
            ->assertDontSee("document.getElementById('bench-container').addEventListener('click'", false)
            ->assertSee('Goalkeeping')
            ->assertSee('Shots Faced')
            ->assertSee('Goals Conceded')
            ->assertSee('Clean Sheets')
            ->assertSee('Saves')
            ->assertSee('Penalty Saves')
            ->assertSee('High Claims')
            ->assertSee('Aerial & Defensive')
            ->assertSee('Punches')
            ->assertSee('Sweeper Actions')
            ->assertSee('Catches')
            ->assertSee('Crosses Stopped')
            ->assertSee('Distribution')
            ->assertSee('Accurate Goal Kicks')
            ->assertSee('Unsuccessful Passes')
            ->assertSee('Accurate Throws');

        $this->get(route('matches.getPlayerStats', [$match, $goalkeeper]))
            ->assertOk()
            ->assertJsonPath('position', 'GK')
            ->assertJsonPath('stats.gk_shots_faced', 0)
            ->assertJsonPath('stats.gk_successful_passes', 0);
    }

    public function test_attacking_midfielder_is_rendered_above_the_central_midfield_line(): void
    {
        [$analyst, $match, , $outfieldPlayer] = $this->createMatchWithPlayers();
        $outfieldPlayer->update([
            'name' => 'Attacking Midfielder',
            'popular_name' => 'AM Player',
            'position' => 'AM',
        ]);
        $match->players()->updateExistingPivot($outfieldPlayer->id, ['position' => 'AM']);

        $this->actingAs($analyst)
            ->get(route('matches.live', $match))
            ->assertOk()
            ->assertSee('data-name="AM Player"', false)
            ->assertSee('data-position="AM"', false)
            ->assertSee('absolute left-1/2 -translate-x-1/2 -top-8 z-20', false)
            ->assertSee('class="relative flex justify-around items-start px-8 sm:px-14"', false);
    }

    public function test_goalkeeper_statistics_are_saved_and_outfield_keys_are_rejected(): void
    {
        [$analyst, $match, $goalkeeper] = $this->createMatchWithPlayers();
        $match->players()->updateExistingPivot($goalkeeper->id, ['position' => 'Cadangan']);

        $this->actingAs($analyst)
            ->get(route('matches.getPlayerStats', [$match, $goalkeeper]))
            ->assertOk()
            ->assertJsonPath('position', 'Cadangan')
            ->assertJsonPath('stats.gk_saves', 0);

        $this->actingAs($analyst)
            ->postJson(route('matches.storeStatistic', $match), [
                'player_id' => $goalkeeper->id,
                'stat_key' => 'gk_saves',
                'action' => 'increment',
            ])
            ->assertOk()
            ->assertJsonPath('current_value', 1);

        $this->assertDatabaseHas('statistics', [
            'match_id' => $match->id,
            'player_id' => $goalkeeper->id,
            'analyst_id' => $analyst->id,
            'saves' => 1,
        ]);

        $this->postJson(route('matches.storeStatistic', $match), [
            'player_id' => $goalkeeper->id,
            'stat_key' => '3',
            'action' => 'increment',
        ])->assertUnprocessable();
    }

    public function test_outfield_statistics_remain_available_and_goalkeeper_keys_are_rejected(): void
    {
        [$analyst, $match, , $outfieldPlayer] = $this->createMatchWithPlayers();

        $this->actingAs($analyst)
            ->postJson(route('matches.storeStatistic', $match), [
                'player_id' => $outfieldPlayer->id,
                'stat_key' => '3',
                'action' => 'increment',
            ])
            ->assertOk()
            ->assertJsonPath('current_value', 1);

        $this->assertDatabaseHas('statistics', [
            'match_id' => $match->id,
            'player_id' => $outfieldPlayer->id,
            'goal' => 1,
        ]);

        $this->postJson(route('matches.storeStatistic', $match), [
            'player_id' => $outfieldPlayer->id,
            'stat_key' => 'gk_saves',
            'action' => 'increment',
        ])->assertUnprocessable();
    }

    private function createMatchWithPlayers(): array
    {
        $analyst = User::create([
            'name' => 'Analyst',
            'email' => 'analyst@example.com',
            'password' => 'password',
            'role' => 'Analis',
            'status' => 'Aktif',
        ]);
        $club = Club::create(['name' => 'Analytics FC']);
        $goalkeeper = Player::create([
            'club_id' => $club->id,
            'name' => 'Full Goalkeeper Name',
            'popular_name' => 'Keeper One',
            'position' => 'GK',
            'status' => 'Aktif',
        ]);
        $outfieldPlayer = Player::create([
            'club_id' => $club->id,
            'name' => 'Full Striker Name',
            'popular_name' => 'Striker One',
            'position' => 'ST',
            'status' => 'Aktif',
        ]);
        $match = MatchSession::create([
            'title' => 'Analytics FC',
            'opponent' => 'Opponent FC',
            'match_date' => now()->addDay(),
            'status' => 'Upcoming',
            'created_by' => $analyst->id,
        ]);
        $match->players()->attach([
            $goalkeeper->id => ['position' => 'GK'],
            $outfieldPlayer->id => ['position' => 'ST'],
        ]);

        return [$analyst, $match, $goalkeeper, $outfieldPlayer];
    }
}
