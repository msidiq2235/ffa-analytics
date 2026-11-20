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

class PlayerPerformanceDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_detail_shows_career_statistic_totals_and_keeps_match_history_as_links(): void
    {
        $analyst = User::create([
            'name' => 'Analyst',
            'email' => 'analyst@example.com',
            'password' => 'password',
            'role' => 'Analis',
            'status' => 'Aktif',
        ]);
        $club = Club::create(['name' => 'Performance FC']);
        $player = Player::create([
            'club_id' => $club->id,
            'name' => 'Performance Player',
            'popular_name' => 'Performer',
            'status' => 'Aktif',
            'position' => 'GK',
        ]);
        $olderMatch = $this->createMatch($analyst, 'Older Match', now()->subDays(10));
        $latestMatch = $this->createMatch($analyst, 'Latest Match', now()->subDay());
        $olderMatch->players()->attach($player->id, ['position' => 'GK']);
        $latestMatch->players()->attach($player->id, ['position' => 'GK']);

        Statistic::create([
            'match_id' => $olderMatch->id,
            'player_id' => $player->id,
            'analyst_id' => $analyst->id,
            'goal' => 1,
            'successful_passes' => 12,
            'saves' => 4,
        ]);
        Statistic::create([
            'match_id' => $latestMatch->id,
            'player_id' => $player->id,
            'analyst_id' => $analyst->id,
            'goal' => 2,
            'assist' => 2,
            'goals_conceded' => 1,
            'accurate_throws' => 3,
        ]);

        $olderVector = $this->createVector($analyst, $player, $olderMatch, 'SHOT', 10, 20, 30, 40);
        $latestVector = $this->createVector($analyst, $player, $latestMatch, 'PASS', 15, 25, 55, 65, 'SUCCESS');

        $this->actingAs($analyst)
            ->get(route('reports.player.show', $player))
            ->assertOk()
            ->assertSee('Older Match')
            ->assertSee('Latest Match')
            ->assertSee(route('reports.player.match.show', [$player->id, $latestMatch->id]), false)
            ->assertDontSee('Download PDF')
            ->assertSee('Rekapan Statistik Performa')
            ->assertSee('data-stat-total="goal" data-stat-value="3"', false)
            ->assertSee('data-stat-total="assist" data-stat-value="2"', false)
            ->assertSee('data-stat-total="successful_passes" data-stat-value="12"', false)
            ->assertSee('data-stat-total="saves" data-stat-value="4"', false)
            ->assertSee('data-stat-total="accurate_throws" data-stat-value="3"', false)
            ->assertSee('Umpan berhasil')
            ->assertDontSee('Total Gol')
            ->assertDontSee('Total Assist')
            ->assertDontSee('Total Bermain')
            ->assertDontSee('Data Vektor Terbaru')
            ->assertDontSee('data-vector-id=', false);

        $this->get(route('reports.player.match.show', [$player->id, $latestMatch->id]))
            ->assertOk()
            ->assertSee('Latest Match')
            ->assertSee('Statistik Live Analytics')
            ->assertSee('Goals conceded')
            ->assertSee('Peta Vektor Pemain')
            ->assertSee('Umpan · SUCCESS')
            ->assertSee('stroke="#22c55e"', false)
            ->assertSee('data-vector-id="'.$latestVector->id.'"', false)
            ->assertDontSee('data-vector-id="'.$olderVector->id.'"', false);

        $notPlayedMatch = $this->createMatch($analyst, 'Not Played Match', now());
        $this->get(route('reports.player.match.show', [$player->id, $notPlayedMatch->id]))
            ->assertNotFound();
        $this->get(route('reports.player.match.pdf', [$player->id, $notPlayedMatch->id]))
            ->assertNotFound();

        $matchPdf = $this->actingAs($analyst)
            ->get(route('reports.player.match.pdf', [$player->id, $latestMatch->id]));
        $matchPdf->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $matchPdf->getContent());

        $profilePdf = $this->get(route('reports.player.pdf', $player->id));
        $profilePdf->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $profilePdf->getContent());

        $latestDate = $latestMatch->match_date;
        $season = $latestDate->month >= 7 ? $latestDate->year : $latestDate->year - 1;
        $seasonPdf = $this->get(route('reports.player.season.pdf', [$player->id, $season]));
        $seasonPdf->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $seasonPdf->getContent());

        $teamMatchPdf = $this->get(route('reports.match.pdf', $latestMatch->id));
        $teamMatchPdf->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $teamMatchPdf->getContent());

        $this->get(route('reports.index', ['tab' => 'match']))
            ->assertOk()
            ->assertSee(route('reports.match.detail', $latestMatch->id), false);
        $this->get(route('reports.match.detail', $latestMatch->id))
            ->assertOk()
            ->assertSee('Statistik Pemain')
            ->assertSee('Performance Player')
            ->assertSee('Peta Vektor Pertandingan')
            ->assertSee('Umpan · SUCCESS')
            ->assertSee('stroke="#22c55e"', false);
        $this->get(route('reports.match.show', $latestMatch->id))
            ->assertOk()
            ->assertSee('Statistik Pemain');
    }

    public function test_vector_subtypes_are_saved_and_validated_for_each_event_type(): void
    {
        $analyst = User::create([
            'name' => 'Vector Analyst',
            'email' => 'vector-analyst@example.com',
            'password' => 'password',
            'role' => 'Analis',
            'status' => 'Aktif',
        ]);
        $club = Club::create(['name' => 'Vector FC']);
        $player = Player::create([
            'club_id' => $club->id,
            'name' => 'Vector Player',
            'status' => 'Aktif',
            'position' => 'CM',
        ]);
        $match = $this->createMatch($analyst, 'Vector Match', now());
        $match->players()->attach($player->id, ['position' => 'CM']);

        $this->actingAs($analyst)
            ->get(route('matches.vector', $match))
            ->assertOk()
            ->assertSee('id="event-subtypes" class="mt-3 hidden', false)
            ->assertSee('Cross · Unsuccess')
            ->assertSee('Pass · Assist')
            ->assertDontSee('id="shot-subtypes"', false)
            ->assertSee("canvas.addEventListener('click'", false)
            ->assertDontSee("canvas.addEventListener('pointerdown'", false)
            ->assertDontSee("canvas.addEventListener('pointermove'", false);

        foreach ([
            ['SHOT', 'GOAL'],
            ['PASS', 'SUCCESS'],
            ['PASS', 'UNSUCCESS'],
            ['PASS', 'ASSIST'],
            ['CROSS', 'SUCCESS'],
            ['CROSS', 'UNSUCCESS'],
        ] as [$action, $subtype]) {
            $this->actingAs($analyst)
                ->postJson(route('matches.storeVector', $match), [
                    'player_id' => $player->id,
                    'action_type' => $action,
                    'subtype' => $subtype,
                    'start_x' => 10,
                    'start_y' => 20,
                    'end_x' => 30,
                    'end_y' => 40,
                    'timestamp_mode' => 'manual',
                    'match_minute' => 5,
                ])
                ->assertOk()
                ->assertJsonPath('data.action_type', $action)
                ->assertJsonPath('data.subtype', $subtype);
        }

        $this->actingAs($analyst)
            ->postJson(route('matches.storeVector', $match), [
                'player_id' => $player->id,
                'action_type' => 'CROSS',
                'subtype' => 'ASSIST',
                'start_x' => 10,
                'start_y' => 20,
                'end_x' => 30,
                'end_y' => 40,
                'timestamp_mode' => 'manual',
                'match_minute' => 5,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('subtype');

        $this->assertDatabaseCount('vector_directions', 6);
    }

    private function createMatch(User $creator, string $title, $date): MatchSession
    {
        return MatchSession::create([
            'title' => $title,
            'opponent' => 'Opponent FC',
            'match_date' => $date,
            'status' => 'Selesai',
            'created_by' => $creator->id,
        ]);
    }

    private function createVector(
        User $analyst,
        Player $player,
        MatchSession $match,
        string $actionType,
        float $startX,
        float $startY,
        float $endX,
        float $endY,
        ?string $subtype = null
    ): VectorDirection {
        return VectorDirection::create([
            'match_id' => $match->id,
            'player_id' => $player->id,
            'analyst_id' => $analyst->id,
            'action_type' => $actionType,
            'subtype' => $subtype,
            'start_x' => $startX,
            'start_y' => $startY,
            'end_x' => $endX,
            'end_y' => $endY,
        ]);
    }
}
