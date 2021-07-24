@extends('rapidez::layouts.app')

@section('title', __('Shop Search'))

@section('content')
    <div class="container mx-auto mb-5 px-3 sm:px-0">
        <h1 class="font-bold text-2xl mb-5">
            @lang('Shop Search')
        </h1>

        <gmap v-cloak v-slot="{ selectLocation, selectedLocation }">
            <div class="md:flex md:space-x-5">
                <div class="md:w-1/3">
                    <div class="flex space-x-3 justify-between mb-3">
                        <x-rapidez::input name="search" :label="false" wrapperClass="w-full" class="h-full" />
                        <x-rapidez::button>
                            @lang('Search')
                        </x-rapidez::button>
                    </div>

                    <a
                        href="#"
                        v-for="retailer in config.retailers"
                        class="block border border-l-4 rounded p-2 mb-2 bg-gray-50 hover:bg-white"
                        :class="{
                            'border-primary': selectedLocation && selectedLocation.address_id == retailer.address_id
                        }"
                        v-on:click.prevent="selectLocation(retailer.address_id)"
                    >
                        @{{ retailer.street }} - @{{ retailer.city }}
                    </a>
                </div>
                <div class="md:w-2/3 relative">
                    <div v-if="selectedLocation" class="absolute bg-white p-3 rounded shadow z-10 top-2 left-2">
                        <div class="mb-5">@{{ selectedLocation.street }} - @{{ selectedLocation.city }}</div>
                        <a
                            :href="'{{ Rapidez::config('store_locator/seo/base_url', 'stores') }}/' + selectedLocation.url_key"
                            class="flex items-center font-bold underline"
                        >
                            @lang('Show store')
                            <x-heroicon-s-chevron-right class="h-4 w-4"/>
                        </a>
                    </div>
                    <gmap-map
                        ref="map"
                        :zoom="8"
                        :center="{ lat: 0, lng: 0 }"
                        :options="{ mapTypeControl: false, streetViewControl: false }"
                        class="w-full h-[500px]"
                    >
                        <gmap-marker
                            v-for="retailer in config.retailers"
                            :key="retailer.address_id"
                            :position="{ lat: retailer.latitude, lng: retailer.longitude }"
                            :icon="{ url: config.maps.icon, scaledSize: { width: 50, height: 59 } }"
                            v-on:click="selectLocation(retailer.address_id)"
                        />
                    </gmap-map>
                    <div v-if="selectedLocation" class="sm:flex mt-5">
                        <div class="sm:w-1/3 mb-3">
                            <strong class="block mb-3">@{{ selectedLocation.city }}</strong>
                            @{{ selectedLocation.street }}<br>
                            @{{ selectedLocation.postcode }}
                        </div>
                        <div class="sm:w-1/3 mb-3">
                            <div v-if="selectedLocation.facilities">
                                <strong class="block mb-3">@lang('Facilities')</strong>
                                <div v-for="facility in selectedLocation.facilities" class="flex items-center">
                                    <x-heroicon-s-check class="h-4 w-4 mr-1 text-primary"/> @{{ facility.facility }}
                                </div>
                            </div>
                        </div>
                        <div class="sm:w-1/3 mb-3">
                            <strong class="block mb-3">@lang('Opening hours')</strong>
                            {{-- @{{ selectedLocation.times }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </gmap>
    </div>
@endsection
