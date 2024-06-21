<?php

namespace App\Filament\Resources;

use App\Models\Tax;
use Filament\Forms;
use Filament\Tables;
use App\Models\Region;
use App\Models\Country;
use App\Models\Survice;
use Filament\Forms\Form;
use App\Models\Subsurvice;
use Filament\Tables\Table;
use App\Models\Minisurvice;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MinisurviceResource\Pages;
use App\Filament\Resources\MinisurviceResource\RelationManagers;

class MinisurviceResource extends Resource
{
    //protected static ?string $model = Minisurvice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.minisurvice.label');
    }

    public static function form(Form $form): Form
    {
        $governmentalTax = Tax::where('name','=','governmental')->first()->value ;
        return $form
            ->schema([
                Map::make('location')
                ->mapControls([
                    'mapTypeControl'    => true,
                    'scaleControl'      => true,
                    'streetViewControl' => true,
                    'rotateControl'     => true,
                    'fullscreenControl' => true,
                    'searchBoxControl'  => false, // creates geocomplete field inside map
                    'zoomControl'       => false,
                ])
                ->reactive()
                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                    $set('latitude', $state['lat']);
                    $set('longitude', $state['lng']);
                })
                ->height(fn () => '400px') // map height (width is controlled by Filament options)
                ->defaultZoom(5) // default zoom level when opening form
                ->autocomplete('full_address') // field on form to use as Places geocompletion field
                ->autocompleteReverse(true) // reverse geocode marker location to autocomplete field
                ->reverseGeocode([
                    'street' => '%n %S',
                    'city' => '%L',
                    'state' => '%A1',
                    'zip' => '%z',
                ]) // reverse geocode marker location to form fields, see notes below
                ->debug() // prints reverse geocode format strings to the debug console
                ->defaultLocation([39.526610, -107.727261]) // default for new forms
                ->draggable() // allow dragging to move marker
                ->clickable(true) // allow clicking to move marker
                ->geolocate() // adds a button to request device location and set map marker accordingly
                ->geolocateLabel('Get Location') // overrides the default label for geolocate button
                ->geolocateOnLoad(true, false) // geolocate on load, second arg 'always' (default false, only for new form))
                ->layers([
                    'https://googlearchive.github.io/js-v2-samples/ggeoxml/cta.kml',
                ]) // array of KML layer URLs to add to the map
                ->geoJson('https://fgm.test/storage/AGEBS01.geojson') // GeoJSON file, URL or JSON
                ->geoJsonContainsField('geojson')
                ->label(__('filament-panels::layout.actions.table.location.label')),

                Forms\Components\TextInput::make('name_ar')->label(__('filament-panels::layout.actions.table.name_ar.label'))->required()->autofocus(),
                Forms\Components\TextInput::make('name_en')->label(__('filament-panels::layout.actions.table.name_en.label'))->required(),
                Forms\Components\FileUpload::make('imagepath')->label(__('filament-panels::layout.actions.table.image.label'))->disk('survice_images')->nullable(),
                Forms\Components\TextInput::make('description_ar')->label(__('filament-panels::layout.actions.table.description_ar.label')),
                Forms\Components\TextInput::make('description_en')->label(__('filament-panels::layout.actions.table.description_en.label')),
                Forms\Components\TextInput::make('address_ar')->label(__('filament-panels::layout.actions.table.address_ar.label')),
                Forms\Components\TextInput::make('address_en')->label(__('filament-panels::layout.actions.table.address_en.label')),
                Forms\Components\TextInput::make('baby_price')->label(__('filament-panels::layout.actions.table.baby_price.label')),
                Forms\Components\TextInput::make('adult_price')->label(__('filament-panels::layout.actions.table.adult_price.label')),
                Forms\Components\TextInput::make('points')->label(__('filament-panels::layout.actions.table.points.label')),
                Forms\Components\TextInput::make('rating')->default(0)->label(__('filament-panels::layout.actions.table.rating.label')),
                Forms\Components\TextInput::make('humannumber')->default(0)->label(__('filament-panels::layout.actions.table.humannumber.label')),
                Forms\Components\DatePicker::make('start_at')->label(__('filament-panels::layout.actions.table.start_at.label')),
                Forms\Components\DatePicker::make('end_at')->label(__('filament-panels::layout.actions.table.end_at.label')),
                Select::make('isOffer')
                ->options([
                    '0' => 'لا',
                    '1' => 'نعم',
                    //'1' => 'نعم',
                ])
                ->label(__('filament-panels::layout.actions.table.isOffer.label')),

                Select::make('isFamily')
                ->options([
                    '0' => 'لا',
                    '1' => 'نعم',
                    //'1' => 'نعم',
                ])
                ->label(__('filament-panels::layout.actions.table.isFamily.label')),

                Forms\Components\TextInput::make('phone')
                ->tel()
                ->prefix('+966')
                ->label(__('filament-panels::layout.actions.table.phone.label'))
                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),


                //Forms\Components\TextInput::make('tax')->label(__('filament-panels::layout.actions.table.tax.label')),
                Hidden::make('tax')->default($governmentalTax),
                Hidden::make('provider_id')->default(auth()->user()->id),
                Hidden::make('survice_id')->default(auth()->user()->survice_id),
                Select::make('country_id')->label(__('filament-panels::layout.actions.table.country.label'))->options(Country::all()->pluck('name', 'id'))->searchable(),
                Select::make('region_id')->label(__('filament-panels::layout.actions.table.region.label'))->options(Region::all()->pluck('name_ar', 'id'))->searchable(),
                Forms\Components\TextInput::make('description_region_ar')->label(__('filament-panels::layout.actions.table.description_region_ar.label')),
                Forms\Components\TextInput::make('description_region_en')->label(__('filament-panels::layout.actions.table.description_region_en.label')),
                Select::make('subsurvice_id')->label(__('filament-panels::layout.actions.table.subsurvice.label'))->options(Subsurvice::where('survice_id','=',auth()->user()->survice_id)->pluck('name_ar', 'id'))->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $userId = auth()->user()->id;

        return $table
        ->modifyQueryUsing(function (Builder $query) use ($userId) {
            $query->where('provider_id', $userId);
        })
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')->label(__('filament-panels::layout.actions.table.name_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('name_en')->label(__('filament-panels::layout.actions.table.name_en.label'))->searchable(),
                Tables\Columns\ImageColumn::make('imagepath')->label(__('filament-panels::layout.actions.table.image.label'))->square()->disk('survice_images'),
                Tables\Columns\TextColumn::make('description_ar')->label(__('filament-panels::layout.actions.table.description_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('description_en')->label(__('filament-panels::layout.actions.table.description_en.label'))->searchable(),
                Tables\Columns\TextColumn::make('address_ar')->label(__('filament-panels::layout.actions.table.address_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('address_en')->label(__('filament-panels::layout.actions.table.address_en.label'))->searchable(),
                Tables\Columns\TextColumn::make('baby_price')->label(__('filament-panels::layout.actions.table.baby_price.label'))->searchable(),
                Tables\Columns\TextColumn::make('adult_price')->label(__('filament-panels::layout.actions.table.adult_price.label'))->searchable(),
                Tables\Columns\TextColumn::make('points')->label(__('filament-panels::layout.actions.table.points.label'))->searchable(),
                Tables\Columns\TextColumn::make('tax')->label(__('filament-panels::layout.actions.table.tax.label')),
                Tables\Columns\TextColumn::make('humannumber')->label(__('filament-panels::layout.actions.table.humannumber.label')),
                Tables\Columns\TextColumn::make('start_at')->label(__('filament-panels::layout.actions.table.start_at.label')),
                Tables\Columns\TextColumn::make('end_at')->label(__('filament-panels::layout.actions.table.end_at.label')),
                Tables\Columns\TextColumn::make('isOffer')
                ->label(__('filament-panels::layout.actions.table.isOffer.label'))
                ->searchable()
                ->getStateUsing(function ($record) {
                    return $record->isOffer == 0 ? 'No' : 'Yes';
                }),
                Tables\Columns\TextColumn::make('isFamily')
                ->label(__('filament-panels::layout.actions.table.isFamily.label'))
                ->searchable()
                ->getStateUsing(function ($record) {
                    return $record->isFamily == 0 ? 'No' : 'Yes';
                }),
                Tables\Columns\TextColumn::make('survice.name_en')->label(__('filament-panels::layout.actions.table.survice.label'))->searchable(),
                Tables\Columns\TextColumn::make('country.name')->label(__('filament-panels::layout.actions.table.country.label'))->searchable(),
                Tables\Columns\TextColumn::make('region.name_ar')->label(__('filament-panels::layout.actions.table.region.label'))->searchable(),
                Tables\Columns\TextColumn::make('description_region_ar')->label(__('filament-panels::layout.actions.table.description_region_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('description_region_en')->label(__('filament-panels::layout.actions.table.description_region_en.label'))->searchable(),
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
            'index' => Pages\ListMinisurvices::route('/'),
            'create' => Pages\CreateMinisurvice::route('/create'),
            'edit' => Pages\EditMinisurvice::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.minisurvice.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.minisurvice.label');
    }
}
