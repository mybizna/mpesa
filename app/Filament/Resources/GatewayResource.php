<?php

namespace Modules\Mpesa\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Mpesa\Filament\Resources\GatewayResource\Pages;
use Modules\Mpesa\Models\Gateway;

class GatewayResource extends Resource
{
    protected static ?string $model = Gateway::class;

    protected static ?string $slug = 'mpesa/gateway';

    protected static ?string $navigationGroup = 'Mpesa';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('consumer_key')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('consumer_secret')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('initiator_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('initiator_password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('passkey')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('party_a')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('party_b')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_shortcode')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('type'),
                Forms\Components\TextInput::make('method'),
                Forms\Components\TextInput::make('ledger_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('currency_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('default')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('sandbox')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('published')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('consumer_key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('consumer_secret')
                    ->searchable(),
                Tables\Columns\TextColumn::make('initiator_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('passkey')
                    ->searchable(),
                Tables\Columns\TextColumn::make('party_a')
                    ->searchable(),
                Tables\Columns\TextColumn::make('party_b')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_shortcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('method'),
                Tables\Columns\TextColumn::make('ledger_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('default')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sandbox')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('published')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGateways::route('/'),
            'create' => Pages\CreateGateway::route('/create'),
            'edit' => Pages\EditGateway::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
