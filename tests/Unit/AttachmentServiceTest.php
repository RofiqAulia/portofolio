<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Contracts\AttachmentStorage;
use App\Enums\AttachmentType;
use App\Exceptions\InvalidAttachmentException;
use App\Services\AttachmentService;
use App\Services\FileAttachmentHandler;
use App\Services\LinkAttachmentHandler;
use PHPUnit\Framework\TestCase;

class AttachmentServiceTest extends TestCase
{
    private AttachmentStorage $storageMock;

    private AttachmentService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storageMock = $this->createMock(AttachmentStorage::class);
        $fileHandler = new FileAttachmentHandler($this->storageMock);
        $linkHandler = new LinkAttachmentHandler($this->storageMock);

        $this->service = new AttachmentService($fileHandler, $linkHandler);
    }

    public function test_attachment_service_resolves_correct_handler_for_file_type(): void
    {
        // Act
        $handler = $this->service->resolveHandler(AttachmentType::File);

        // Assert
        $this->assertInstanceOf(FileAttachmentHandler::class, $handler);
    }

    public function test_attachment_service_resolves_correct_handler_for_link_type(): void
    {
        // Act
        $handler = $this->service->resolveHandler(AttachmentType::Link);

        // Assert
        $this->assertInstanceOf(LinkAttachmentHandler::class, $handler);
    }

    public function test_link_handler_throws_exception_when_url_is_empty(): void
    {
        // Arrange
        $handler = $this->service->resolveHandler(AttachmentType::Link);

        // Expect
        $this->expectException(InvalidAttachmentException::class);

        // Act
        $handler->handle(['title' => 'Sample Link', 'url' => '']);
    }

    public function test_link_handler_clears_file_fields_and_keeps_url(): void
    {
        // Arrange
        $handler = $this->service->resolveHandler(AttachmentType::Link);

        // Act
        $result = $handler->handle([
            'title' => 'Certificate Link',
            'url' => 'https://example.com/certificate',
        ]);

        // Assert
        $this->assertSame('https://example.com/certificate', $result['url']);
        $this->assertNull($result['file_path']);
        $this->assertNull($result['mime_type']);
    }
}
