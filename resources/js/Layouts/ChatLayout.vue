<template>
    <div class="w-full  grow lg:flex xl:px-2">
        <div class="flex">
            <slot name="sidebar"/>
        </div>
        <div class="flex min-h-full flex-col flex-grow overflow-auto">
            <slot/>
        </div>
    </div>
</template>
