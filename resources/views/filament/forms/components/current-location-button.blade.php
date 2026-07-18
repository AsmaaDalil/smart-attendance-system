<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            loading: false,
            message: '',
            success: false,

            getCurrentLocation() {
                if (! navigator.geolocation) {
                    this.success = false;
                    this.message =
                        'Geolocation is not supported by this browser.';

                    return;
                }

                this.loading = true;
                this.message = '';

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latitude =
                            position.coords.latitude.toFixed(8);

                        const longitude =
                            position.coords.longitude.toFixed(8);

                        $wire.set(
                            'data.latitude',
                            latitude
                        );

                        $wire.set(
                            'data.longitude',
                            longitude
                        );

                        this.loading = false;
                        this.success = true;

                        this.message =
                            'Location captured successfully. Accuracy: '
                            + Math.round(position.coords.accuracy)
                            + ' meters.';
                    },

                    (error) => {
                        this.loading = false;
                        this.success = false;

                        if (error.code === 1) {
                            this.message =
                                'Location permission was denied. Please allow location access.';
                        } else if (error.code === 2) {
                            this.message =
                                'Your current location could not be determined.';
                        } else if (error.code === 3) {
                            this.message =
                                'Location request timed out. Please try again.';
                        } else {
                            this.message =
                                'An error occurred while obtaining the location.';
                        }
                    },

                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    }
                );
            }
        }"
        class="rounded-2xl border border-[#1a4a40]/15
               bg-[#1a4a40]/5 p-5"
    >

        <div class="flex flex-col sm:flex-row
                    sm:items-center sm:justify-between gap-4">

            <div>
                <p class="font-semibold text-[#1a4a40]">
                    Automatic Location Detection
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Use the location of this device as the room location.
                </p>
            </div>

            <button
                type="button"
                x-on:click="getCurrentLocation()"
                x-bind:disabled="loading"
                class="px-5 py-3 rounded-xl
                       bg-[#1a4a40] text-white
                       hover:bg-[#153d35]
                       disabled:opacity-50
                       disabled:cursor-not-allowed
                       transition whitespace-nowrap"
            >
                <span x-show="! loading">
                    Use Current Location
                </span>

                <span x-show="loading" x-cloak>
                    Detecting Location...
                </span>
            </button>

        </div>

        <div
            x-show="message"
            x-cloak
            class="mt-4 px-4 py-3 rounded-xl text-sm"
            x-bind:class="
                success
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'
            "
        >
            <span x-text="message"></span>
        </div>

    </div>
</x-dynamic-component>