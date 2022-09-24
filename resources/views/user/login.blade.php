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
                <div class="mt-8 text-base">
                    <div class="mt-4">
                        <a
                            href="{{route('github.login')}}"
                            class="
                                flex items-center
                                p-3 px-6
                                bg-slate-800 text-white
                                rounded-md
                                "
                        >
                            <span class="mt-1">
                                <i class="octicons octicon-mark-github-16 text-3xl"></i>
                            </span>
                            <span class="pl-4 text-xl">
                                Login with GitHub
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-auth-card>
</x-app-layout>
