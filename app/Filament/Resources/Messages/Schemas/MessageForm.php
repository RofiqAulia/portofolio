<?php

declare(strict_types=1);

namespace App\Filament\Resources\Messages\Schemas;

use App\Enums\MessageStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Status Pengelolaan')
                    ->description('Kelola status pembacaan dan tindak lanjut pesan')
                    ->schema([
                        Select::make('status')
                            ->label('Status Pesan')
                            ->options([
                                MessageStatus::New->value => MessageStatus::New->label(),
                                MessageStatus::Read->value => MessageStatus::Read->label(),
                                MessageStatus::Replied->value => MessageStatus::Replied->label(),
                                MessageStatus::Archived->value => MessageStatus::Archived->label(),
                            ])
                            ->required(),
                        DateTimePicker::make('read_at')
                            ->label('Waktu Dibaca')
                            ->nullable(),
                        DateTimePicker::make('created_at')
                            ->label('Waktu Diterima')
                            ->disabled(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Pesan Masuk')
                    ->description('Informasi lengkap pengirim dan isi pesan kontak')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Pengirim')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Email Pengirim')
                            ->disabled(),
                        TextInput::make('subject')
                            ->label('Subjek')
                            ->disabled(),
                        TextInput::make('ip_address')
                            ->label('IP Pengirim')
                            ->disabled(),
                        Textarea::make('body')
                            ->label('Isi Pesan')
                            ->rows(6)
                            ->columnSpanFull()
                            ->disabled(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Lampiran Berkas / Gambar')
                    ->description('Berkas atau dokumen yang diunggah oleh pengirim pesan')
                    ->schema([
                        Placeholder::make('attachments_view')
                            ->label('Daftar Berkas')
                            ->hiddenLabel()
                            ->content(fn ($record) => view('filament.components.message-attachments', ['record' => $record]))
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->visible(fn ($record) => $record && $record->hasAttachment()),
            ]);
    }
}
