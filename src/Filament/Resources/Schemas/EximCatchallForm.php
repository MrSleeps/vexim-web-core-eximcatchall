<?php

namespace VEximweb\Core\EximCatchall\Filament\Resources\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use VEximweb\Core\Data\Models\Domain;
use VEximweb\Core\Data\Models\EximUser;
use VEximweb\Core\Data\Models\Setting;

class EximCatchallForm
{
    public static function forwardingDestinationOptions(): array
    {
        $user = auth()->user();

        if (!$user || (!$user->isSystemAdmin() && !$user->isDomainAdmin())) {
            return [];
        }

        $query = EximUser::query()
            ->whereIn('type', ['alias', 'local']);

        if (!$user->isSystemAdmin()) {
            $domainIds = $user->domains()->pluck('domains.domain_id');
            $query->whereIn('users.domain_id', $domainIds);
        }

        $options = $query
            ->orderBy('username')
            ->pluck('username', 'username')
            ->toArray();

        $options['other'] = 'Other (enter external email)';

        return $options;
    }

    public static function configure(Schema $schema): Schema
    {
        $record = $schema->getRecord();
        $isCreating = $record === null;
        
        return $schema
            ->components([
                Section::make('Catchall Configuration')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Hidden::make('localpart')
                                    ->default('*'),
                                
                                Select::make('domain_id')
                                    ->label('Domain')
                                    ->options(function () use ($isCreating, $record) {
                                        $user = auth()->user();
                                        
                                        if ($isCreating) {
                                            $domainsQuery = $user->isSystemAdmin() ? Domain::query() : $user->domains();
                                            $domainsQuery->whereNotExists(function ($query) {
                                                $query->select('domain_id')
                                                    ->from('users')
                                                    ->whereColumn('domains.domain_id', 'users.domain_id')
                                                    ->where('users.type', 'catch');
                                            });
                                            return $domainsQuery->pluck('domain', 'domain_id');
                                        } else {
                                            $domainsQuery = $user->isSystemAdmin() ? Domain::query() : $user->domains();
                                            return $domainsQuery->pluck('domain', 'domain_id');
                                        }
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->disabled(!$isCreating)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $domain = Domain::find($state);
                                            if ($domain) {
                                                $set('username', '*@' . $domain->domain);
                                            }
                                        }
                                    })
                                    ->columnSpan(1),
                                
                                Hidden::make('username'),
                                
                                Hidden::make('username')
                                    ->label('Catchall Address')
                                    ->disabled()
                                    ->helperText('Anything@selected domain will be caught here')
                                    ->columnSpan(1)
                                    ->visible(!$isCreating),
                            ]),
                    ]),
                    
                Section::make('Forwarding Destination')
                    ->schema([
                        Select::make('smtp_selection')
                            ->label('Forward To')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->options(fn (): array => static::forwardingDestinationOptions()),

                        TextInput::make('custom_smtp')
                            ->label('External Email Address')
                            ->email()
                            ->live()
                            ->visible(fn (callable $get) => $get('smtp_selection') === 'other')
                            ->helperText('Enter the external email address where catchall emails should be forwarded')
                            ->required(fn (callable $get) => $get('smtp_selection') === 'other'),

                        Hidden::make('pop')->default(':fail:'),
                    ]),
                    
                Section::make('Status')
                    ->schema([
                        Toggle::make('enabled')
                            ->label('Catchall Enabled')
                            ->default(true),
                    ]),
                    
                Hidden::make('type')->default('catch'),
                Hidden::make('on_forward')->default(true),
                Hidden::make('quota')->default(0),
                Hidden::make('uid')->default(Setting::get('default_uid', 5000)),
                Hidden::make('gid')->default(Setting::get('default_gid', 5000)),
            ]);
    }
}
