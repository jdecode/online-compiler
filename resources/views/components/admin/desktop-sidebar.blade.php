<!-- Static sidebar for desktop -->
<div
    class="hidden md:flex md:flex-col md:fixed md:inset-y-0"
    x-bind:class="sidebarCollapsed ? 'md:w-20' : 'md:w-64'"
    xmlns:x-bind="http://www.w3.org/1999/xhtml">
    <!-- Sidebar component, swap this element with another sidebar if you like -->
    <div class="flex flex-col flex-grow pt-5 bg-gray-300 dark:bg-gray-800 overflow-y-auto">
        <div class="
            flex items-center justify-between
            px-0 ml-4 -mr-1
            text-gray-500
            text-4xl
            ">
            <a href="{{ route('admin.dashboard') }}" class="shrink-0">
                <x-application-logo class="block h-10 w-auto fill-current"></x-application-logo>
            </a>
            <div
                class="
                flex items-center cursor-pointer
                hidden md:block"
                x-cloak
                x-show="!sidebarCollapsed"
                @click="sidebarCollapsed = true"
                title="Shift + left-arrow to collapse / Shift + right-arrow to expand">
                <span class="material-icons-outlined dark:hover:text-gray-200  hover:text-gray-700">arrow_left</span>
            </div>
            <div
                class="
                flex items-center cursor-pointer
                hidden md:block"
                x-cloak
                x-show="sidebarCollapsed"
                @click="sidebarCollapsed = false"
                title="Shift + left-arrow to collapse / Shift + right-arrow to expand">
                <span class="material-icons-outlined  dark:hover:text-gray-200 hover:text-gray-700">arrow_right</span>
            </div>
        </div>
        <div class="mt-5 flex-1 flex flex-col">
            <x-admin.common-sidebar></x-admin.common-sidebar>
        </div>
    </div>
</div>
