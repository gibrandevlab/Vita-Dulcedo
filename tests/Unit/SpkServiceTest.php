<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SpkService;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Database\Eloquent\Collection;

class SpkServiceTest extends TestCase
{
    private SpkService $spkService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->spkService = new SpkService();
    }

    /**
     * Test empty campaigns returns empty arrays.
     */
    public function test_empty_campaigns()
    {
        $result = $this->spkService->calculate(new Collection());

        $this->assertEmpty($result['ranking']);
        $this->assertNotEmpty($result['criteria']);
        $this->assertNotEmpty($result['weights']);
    }

    /**
     * Test single campaign returns perfect scores (1.0).
     */
    public function test_single_campaign()
    {
        $campaign = new Campaign([
            'title' => 'Single Campaign',
            'target_amount' => 10000000,
            'collected_amount' => 5000000,
            'deadline' => now()->addDays(10)->toDateString(),
            'status' => 'active',
        ]);
        $campaign->setRelation('donations', new Collection());

        $result = $this->spkService->calculate(new Collection([$campaign]));

        $this->assertCount(1, $result['ranking']);
        $this->assertEquals(1.0, $result['ranking'][0]['topsis_score']);
        $this->assertEquals(1.0, $result['ranking'][0]['saw_score']);
        $this->assertEquals(1.0, $result['ranking'][0]['final_score']);
        $this->assertEquals(1, $result['ranking'][0]['final_rank']);
    }

    /**
     * Test calculation and sorting order for multiple campaigns.
     */
    public function test_multiple_campaigns_ranking()
    {
        // Campaign A: 90% funded, 2 days left (very urgent), 10 donations
        $campaignA = new Campaign([
            'title' => 'Campaign A',
            'target_amount' => 10000000,
            'collected_amount' => 9000000,
            'deadline' => now()->addDays(2)->toDateString(),
            'status' => 'active',
        ]);
        $donationsA = new Collection();
        for ($i = 0; $i < 10; $i++) {
            $donationsA->push(new Donation(['status' => 'approved']));
        }
        $campaignA->setRelation('donations', $donationsA);

        // Campaign B: 10% funded, 30 days left, 2 donations
        $campaignB = new Campaign([
            'title' => 'Campaign B',
            'target_amount' => 10000000,
            'collected_amount' => 1000000,
            'deadline' => now()->addDays(30)->toDateString(),
            'status' => 'active',
        ]);
        $donationsB = new Collection();
        for ($i = 0; $i < 2; $i++) {
            $donationsB->push(new Donation(['status' => 'approved']));
        }
        $campaignB->setRelation('donations', $donationsB);

        $campaigns = new Collection([$campaignA, $campaignB]);
        $result = $this->spkService->calculate($campaigns, 'default');

        $this->assertCount(2, $result['ranking']);

        // Since Campaign A is 90% funded, very urgent (2 days left), and has 10 donations,
        // it should rank #1 on default/recommendation preset
        $firstRanked = $result['ranking'][0];
        $secondRanked = $result['ranking'][1];

        $this->assertEquals('Campaign A', $firstRanked['campaign']->title);
        $this->assertEquals('Campaign B', $secondRanked['campaign']->title);

        $this->assertGreaterThan($secondRanked['final_score'], $firstRanked['final_score']);
        $this->assertEquals(1, $firstRanked['final_rank']);
        $this->assertEquals(2, $secondRanked['final_rank']);
    }

    /**
     * Test that preset config works and loads correct weights.
     */
    public function test_presets_loading()
    {
        $presets = SpkService::getPresets();
        $this->assertArrayHasKey('default', $presets);
        $this->assertArrayHasKey('urgent', $presets);
        $this->assertArrayHasKey('almost_done', $presets);
        $this->assertArrayHasKey('popular', $presets);

        // Fetching with 'urgent' preset
        $campaign1 = new Campaign([
            'title' => 'C1',
            'target_amount' => 1000000,
            'collected_amount' => 500000,
            'status' => 'active',
        ]);
        $campaign1->setRelation('donations', new Collection());

        $campaign2 = new Campaign([
            'title' => 'C2',
            'target_amount' => 2000000,
            'collected_amount' => 100000,
            'status' => 'active',
        ]);
        $campaign2->setRelation('donations', new Collection());

        $result = $this->spkService->calculate(new Collection([$campaign1, $campaign2]), 'urgent');
        $this->assertEquals('urgent', $result['preset']);
        $this->assertEquals([0.10, 0.50, 0.10, 0.20, 0.10], $result['weights']);
    }
}
