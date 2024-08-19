<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';
 //This sorts the icons in the navbar
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required(),

                Forms\Components\TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->maxlength(255)
                //When updating, ignoreRecord: true, allows the user to keep the current e-mail.
                ->unique(ignoreRecord: true)
                ->required(),
                
                Forms\Components\DateTimePicker::make('email_verified_at')
                ->label('Email Verified At')
                ->default(now()),

                Forms\Components\TextInput::make('password')
                ->password()
                // When updating this user i created, if i don't write anything in the password field, the password won't be updated and it will be seen by the server as if it doesn't exist in the form submission. However if i write something, it will replace the previous password.
                ->dehydrated(fn (?string $state): bool => filled($state))
                //Makes it required only when there's no previous record added. Meaning when you update, it won't be required, letting dehydrated logic handle the whole thing.
                ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Searchable() allows the items to be searched in the table
                //sortable() allows you to sort the items based on datetime()
                Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('email')->searchable(),
            Tables\Columns\TextColumn::make('email_verified_at')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Shows every action in the user table
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
                
                //Shows every action in a dropdown menu
                Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class
        ];
    }
//This is quite tricky to understand. Basically you can find the name by searching other attributes, for example, email. But you need to have the $recordTitleAttribute variable up there set as the name.
public static function getGloballySearchableAttributes(): array
{
    return ['name', 'email'];
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
