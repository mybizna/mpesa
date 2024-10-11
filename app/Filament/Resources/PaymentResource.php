<?php

namespace Modules\Mpesa\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Base\Filament\Resources\BaseResource;
use Modules\Base\Filament\Resources\Pages;
use Modules\Mpesa\Models\Payment;

class PaymentResource extends BaseResource
{
    protected static ?string $model = Payment::class;

    protected static ?string $slug = 'mpesa/payment';

    protected static ?string $navigationGroup = 'Mpesa';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('trans_type')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('trans_id')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('trans_time')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('trans_amount')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('business_short_code')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('bill_ref_number')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('invoice_number')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('org_account')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('third_party_id')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('msisdn')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('first_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('last_name')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('published')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('completed')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('successful')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trans_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('trans_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('trans_time')
                    ->searchable(),
                Tables\Columns\TextColumn::make('trans_amount')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_short_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bill_ref_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('org_account')
                    ->searchable(),
                Tables\Columns\TextColumn::make('third_party_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('msisdn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('published')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('successful')
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

    public static function getPages(): array
    {

        Pages\ListBase::setResource(static::class);

        return [
            'index' => Pages\ListBase::route('/'),
            'create' => Pages\CreateBase::route('/create'),
            'edit' => Pages\EditBase::route('/{record}/edit'),
        ];
    }
}
