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
        $latestVector = $this->createVector($analyst, $player, $latestMatch, 'PASS', 15, 25, 55, 65);

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
            ->assertSee('Umpan')
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

        $latestDate = $latestMatch->match_date;
        $season = $latestDate->month >= 7 ? $latestDate->year : $latestDate->year - 1;
        $seasonPdf = $this->get(route('reports.player.season.pdf', [$player->id, $season]));
        $seasonPdf->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $seasonPdf->getContent());
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
        float $endY
    ): VectorDirection {
        return VectorDirection::create([
            'match_id' => $match->id,
            'player_id' => $player->id,
            'analyst_id' => $analyst->id,
            'action_type' => $actionType,
            'start_x' => $startX,
            'start_y' => $startY,
            'end_x' => $endX,
            'end_y' => $endY,
        ]);
    }
}
