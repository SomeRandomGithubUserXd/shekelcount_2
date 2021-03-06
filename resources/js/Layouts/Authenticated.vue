<template>
    <div>
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="fixed inset-0 flex z-40 md:hidden" @close="sidebarOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                                 enter-from="opacity-0" enter-to="opacity-100"
                                 leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                                 leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75"/>
                </TransitionChild>
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                 enter-from="-translate-x-full" enter-to="translate-x-0"
                                 leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                 leave-to="-translate-x-full">
                    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-indigo-700">
                        <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                                         enter-to="opacity-100" leave="ease-in-out duration-300"
                                         leave-from="opacity-100" leave-to="opacity-0">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button type="button"
                                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebarOpen = false">
                                    <span class="sr-only">Close sidebar</span>
                                    <XIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                                </button>
                            </div>
                        </TransitionChild>
                        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                            <div class="flex-shrink-0 flex items-center px-4">
                                <ApplicationLogo :size="40" :with-alt="true"/>
                            </div>
                            <nav class="mt-5 px-2 space-y-1">
                                <Link v-for="item in navigation" :key="item.name" :href="item.href"
                                      :class="[item.current ? 'bg-indigo-800 text-white' : 'text-white hover:bg-indigo-600 hover:bg-opacity-75', 'group flex items-center px-2 py-2 text-base font-medium rounded-md']">
                                    <component :is="item.icon" class="mr-4 flex-shrink-0 h-6 w-6 text-indigo-300"
                                               aria-hidden="true"/>
                                    {{ item.name }}
                                </Link>
                            </nav>
                        </div>
                        <div class="flex-shrink-0 flex border-t border-indigo-800 p-4">
                            <div class="flex-shrink-0 group block">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p class="text-base font-medium text-white">{{ $page.props.auth.user.name }}</p>
                                        <a href="#" @click="logout"
                                           class="text-sm font-medium text-indigo-200 group-hover:text-white">Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </TransitionChild>
                <div class="flex-shrink-0 w-14" aria-hidden="true">
                    <!-- Force sidebar to shrink to fit close icon -->
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex-1 flex flex-col min-h-0 bg-indigo-700">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <ApplicationLogo :size="40" :with-alt="true"/>
                    </div>
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        <Link v-for="item in navigation" :key="item.name" :href="item.href"
                              :class="[item.current ? 'bg-indigo-800 text-white' : 'text-white hover:bg-indigo-600 hover:bg-opacity-75', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                            <component :is="item.icon" class="mr-3 flex-shrink-0 h-6 w-6 text-indigo-300"
                                       aria-hidden="true"/>
                            {{ item.name }}
                        </Link>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t border-indigo-800 p-4">
                    <div class="flex-shrink-0 w-full group block">
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ $page.props.auth.user.name }}</p>
                                <a href="#"
                                   @click="logout"
                                   class="text-xs font-medium text-indigo-200 group-hover:text-white">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:pl-64 flex flex-col flex-1">
            <div class="sticky top-0 z-10 md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-gray-100">
                <button type="button"
                        class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                        @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <MenuIcon class="h-6 w-6" aria-hidden="true"/>
                </button>
            </div>
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <slot name="header"/>
                    </div>
                    <div class="max-w-7xl mt-5 mx-auto px-4 sm:px-6 md:px-8">
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                            <slot/>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script>
import {ref} from 'vue'
import {Dialog, DialogOverlay, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {
    PencilIcon,
    HomeIcon,
    MenuIcon,
    AnnotationIcon,
    SearchIcon,
    XIcon,
    SwitchHorizontalIcon,
} from '@heroicons/vue/outline'
import ApplicationLogo from "@/Components/ApplicationLogo";
import {Inertia} from "@inertiajs/inertia";
import {Link} from "@inertiajs/inertia-vue3";


export default {
    components: {
        ApplicationLogo,
        Dialog,
        DialogOverlay,
        TransitionChild,
        TransitionRoot,
        MenuIcon,
        AnnotationIcon,
        XIcon,
        PencilIcon,
        SearchIcon,
        Link
    },
    data() {
        return {
            navigation: [
                {
                    name: 'Dashboard',
                    href: route('dashboard'),
                    icon: HomeIcon,
                    current: route().current('dashboard')
                },
                {
                    name: 'Entries',
                    href: route('entries.index'),
                    icon: AnnotationIcon,
                    current: route().current('entries.*')
                },
                {
                    name: 'Mutators',
                    href: route('mutators.index'),
                    icon: SwitchHorizontalIcon,
                    current: route().current('mutators.*')
                },
                {
                    name: 'Search',
                    href: route('search.index'),
                    icon: SearchIcon,
                    current: route().current('search.*')
                },
            ]
        }
    },
    setup() {
        const sidebarOpen = ref(false)
        return {
            sidebarOpen,
        }
    },
    methods: {
        logout() {
            Inertia.post(route('logout'))
        }
    }
}
</script>
