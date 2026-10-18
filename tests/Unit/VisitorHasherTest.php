<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\VisitorHasher;
use PHPUnit\Framework\TestCase;

class VisitorHasherTest extends TestCase
{
    public function test_hasher_generates_consistent_sha256_hash_for_same_day(): void
    {
        // Arrange
        $hasher = new VisitorHasher('test-salt-secret');
        $ip = '192.168.1.1';
        $ua = 'Mozilla/5.0 Chrome/120.0';
        $date = '2026-10-14';

        // Act
        $hash1 = $hasher->hash($ip, $ua, $date);
        $hash2 = $hasher->hash($ip, $ua, $date);

        // Assert
        $this->assertSame(64, strlen($hash1));
        $this->assertSame($hash1, $hash2);
    }

    public function test_hasher_generates_different_hash_for_different_days(): void
    {
        // Arrange
        $hasher = new VisitorHasher('test-salt-secret');
        $ip = '192.168.1.1';
        $ua = 'Mozilla/5.0';

        // Act
        $hashDay1 = $hasher->hash($ip, $ua, '2026-10-14');
        $hashDay2 = $hasher->hash($ip, $ua, '2026-10-15');

        // Assert
        $this->assertNotSame($hashDay1, $hashDay2);
    }
}
