<template>
    <div class="min-h-screen flex flex-col justify-center items-center py-12 md:py-0 bg-gray-100">
        <div>
            <slot name="logo" />
        </div>
        <div class="sm:max-w-md w-11/12">
            <slot name="heading" />
        </div>

        <div class="w-11/12 sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
