<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan & Sistem';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $title = 'Pengaturan Website & SEO';

    protected string $view = 'filament.pages.site-settings';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function mount(): void
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Informasi Dasar & Kontak')
                    ->schema([
                        TextInput::make('site_title')
                            ->label('Judul Website')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('author_name')
                            ->label('Nama Pemilik')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('contact_email')
                            ->label('Email Kontak')
                            ->email()
                            ->required(),
                        TextInput::make('contact_phone')
                            ->label('Nomor Telepon'),
                    ])->columns(2),

                Section::make('SEO & Metadata')
                    ->schema([
                        Textarea::make('site_description')
                            ->label('Deskripsi Meta Website')
                            ->rows(3),
                        Textarea::make('meta_keywords')
                            ->label('Kata Kunci SEO (pisahkan dengan koma)')
                            ->rows(2),
                        TextInput::make('footer_copyright')
                            ->label('Teks Hak Cipta Footer')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) ($value ?? ''), 'group' => 'general']
            );
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
