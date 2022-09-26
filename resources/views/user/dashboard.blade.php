    <x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="w-full mx-auto">
        <div class=" sm:rounded-md">
            <form
                action="{{ route('dashboard') }}"
                method="POST"
                x-data="{
                    error_in_code: {{ $error_in_code }}
                }"
                >
                @csrf
                <div class="w-48">
                    <label for="language" class="block text-base font-medium mb-4">Language</label>
                    <select
                        id="language"
                        name="language"
                        class="
                            mt-1 block w-full rounded-md text-base
                            py-2 pl-3 pr-10 mt-4
                            border-gray-500 focus:border-dev-500 focus:outline-none focus:ring-dev-500
                            bg-gray-100 dark:bg-gray-800
                            "
                        x-data="{
                            languages : [
                                {key: 'bash', value: 'Bash', default: false},
                                {key: 'gcc', value: 'C', default: false},
                                {key: 'node', value: 'JavaScript', default: false},
                                {key: 'php', value: 'PHP', default: false},
                                {key: 'python', value: 'Python', default: false}
                            ]
                        }"
                        >
                        <template x-for="language in languages" :key="language.key">
                            <option
                                :selected="language.key === '{{ $language }}'"
                                :value="language.key"
                                x-text="language.value"></option>
                        </template>
                    </select>
                </div>
                <div class="w-full">
                    <div class="flex items-start justify-between">
                        <div class="w-3/5">
                            <label for="code" class="block text-base font-medium mt-8 mb-4">Code</label>
                            <textarea
                                name="code"
                                id="code"
                                class="
                                mt-1 block w-full rounded-md text-base font-mono
                                py-2 pl-3 mt-4
                                h-48
                                border-gray-500 focus:border-dev-500 focus:outline-none focus:ring-dev-500
                                bg-gray-100 dark:bg-gray-800
                                "
                                placeholder="Paste your code here"
                            >{{ $code }}</textarea>
                        </div>
                        <div class="w-2/5 pl-4 ml-4 mt-1">
                            <label for="code" class="block text-base font-medium mt-8 mb-4">Command</label>
                            <div
                                class="
                                    font-mono border border-px border-gray-500 p-2
                                    bg-slate-800 dark:bg-slate-800
                                    text-gray-300
                                    "
                                >
                                {{ $command }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-48">
                    <button
                        type="submit"
                        name="run"
                        class="
                            mt-8 block rounded-md
                            focus:outline-none focus:ring-0 shadow-md
                            px-16 py-3
                            text-2xl font-bold text-gray-200
                            bg-dev-700 hover:bg-dev-500
                            "
                    >Run</button>
                </div>
                <div class="w-full">
                    <label for="result" class="block text-base font-medium mt-8 mb-4 flex items-center">
                        <span>Result</span>
                        @if( request()->isMethod('post'))
                            <span
                                class="material-icons-outlined ml-2 text-2xl"
                                :class="error_in_code === 0 ? 'text-green-500' : 'text-red-500'"
                                x-text="error_in_code === 0 ? 'check_circle' : 'cancel'"
                                ></span>
                        @endif
                    </label>
                    <div
                        id="result"
                        class="
                            block w-full rounded-md
                            h-72
                            m-1 p-4
                            focus:border-none focus:outline-none focus:ring-0
                            bg-slate-800 dark:bg-slate-800
                            text-gray-300
                            text-sm
                            font-mono
                            overflow-y-auto
                            overflow-x-hidden
                            "
                        :class="error_in_code === 0 ? 'text-green-500' : 'text-red-500'"
                        readonly
                    >{!! !empty($output) ? nl2br($output) : '' !!}</div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
