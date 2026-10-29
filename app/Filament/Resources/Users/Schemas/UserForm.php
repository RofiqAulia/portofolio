<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

final class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun Pengguna')
                    ->description('Kredensial login autentikasi, hak akses, dan status keaktifan akun')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 2])->schema([
                            TextInput::make('name')
                                ->label('Nama Pengguna')
                                ->placeholder('Contoh: Administrator')
                                ->prefixIcon('heroicon-o-user')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->placeholder('admin@domain.com')
                                ->prefixIcon('heroicon-o-envelope')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                        ]),

                        Grid::make(['default' => 1, 'md' => 2])->schema([
                            TextInput::make('password')
                                ->label('Kata Sandi')
                                ->placeholder('Masukkan sandi baru (kosongkan jika tidak diubah)')
                                ->prefixIcon('heroicon-o-key')
                                ->password()
                                ->revealable()
                                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn (string $operation) => $operation === 'create')
                                ->maxLength(255),

                            Select::make('role')
                                ->label('Peran / Hak Akses')
                                ->options([
                                    UserRole::Admin->value => UserRole::Admin->label(),
                                    UserRole::Editor->value => UserRole::Editor->label(),
                                ])
                                ->required(),
                        ]),

                        Toggle::make('is_active')
                            ->label('Status Akun Aktif')
                            ->helperText('Akun non-aktif tidak akan dapat masuk ke panel administrasi')
                            ->default(true),
                    ]),
            ]);
    }
}
