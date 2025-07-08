<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Filament\Resources\NoteResource\RelationManagers;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Model;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(auth()->id()),

                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->extraAttributes(['placeholder' => 'Enter title']),

                TextArea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(5)
                    ->extraAttributes(['placeholder' => 'Write description...']),

                DatePicker::make('date')
                    ->label('Date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serial_no')->label('No')->rowIndex(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('date')->date(),
                Tables\Columns\TextColumn::make('user.name'), // if notes belong to users
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }
    

    public static function getEloquentQuery(): Builder
    {
       $query = parent::getEloquentQuery();

        // Role 2: Only show their own notes
        if (auth()->check() && auth()->user()->role_id === 2) {
            $query->where('user_id', auth()->id());
        }

        // Role 1: see all notes (no filter)

        return $query;
    }
    public static function canCreate(): bool
    {
        return auth()->user()?->role_id === 2;
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->role_id === 2 && $record->user_id === auth()->id();
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->role_id === 2 && $record->user_id === auth()->id();
    }

}
