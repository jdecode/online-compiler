<x-app-layout>
    <div class="mx-auto text-center">
        <a href="{{ route('home') }}">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500"></x-application-logo>
        </a>
    </div>
    <x-auth-card>
        <div class="w-full mx-auto">
            <div
                class="
                text-3xl text-center
                flex flex-col items-center
            ">
                <span class="mt-4">{{ config('app.name') }}</span>
                @if (app()->isLocal())
                    <div class="mt-8 text-base">
                        <div>
                            <a href="{{route('api.v1')}}">
                                API base URL<br/>
                                <span class="text-dev-500">{{route('api.v1')}}</span>
                            </a>
                            <a href="{{ route('api.v1') }}" target="_blank" class="text-dev-500">
                                <span class="material-icons-outlined">open_in_new</span>
                            </a>
                        </div>
                        <div class="mt-4">
                            <a href="{{route('admin.loginForm')}}">
                                Admin Login:<br/>
                                <span class="text-dev-500">{{route('admin.loginForm')}}</span>
                            </a>
                            <a href="{{ route('admin.loginForm') }}" target="_blank" class="text-dev-500">
                                <span class="material-icons-outlined">open_in_new</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-auth-card>
</x-app-layout>
