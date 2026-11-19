<?php

namespace Tests\Unit;

use App\Models\Posko;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PoskoStatusKesiapanTest extends TestCase
{
    #[DataProvider('statusCases')]
    public function test_status_kesiapan_matches_capacity_boundaries(int $capacity, int $occupied, string $operationalStatus, string $expected): void
    {
        $posko = new Posko([
            'kapasitas' => $capacity,
            'jumlah_terisi' => $occupied,
        ]);
        $posko->status_operasional = $operationalStatus;

        $this->assertSame($expected, $posko->status_kesiapan);
    }

    public static function statusCases(): array
    {
        return [
            '78 percent is ready' => [50, 39, 'Beroperasi', 'Siap'],
            '80 percent is almost full' => [50, 40, 'Beroperasi', 'Hampir Penuh'],
            '98 percent is almost full' => [50, 49, 'Beroperasi', 'Hampir Penuh'],
            '100 percent is full' => [50, 50, 'Beroperasi', 'Penuh'],
            'five of seven is ready' => [7, 5, 'Beroperasi', 'Siap'],
            'six of seven is almost full' => [7, 6, 'Beroperasi', 'Hampir Penuh'],
            'not operating overrides occupancy' => [80, 20, 'Tidak Beroperasi', 'Tidak Beroperasi'],
        ];
    }
}
