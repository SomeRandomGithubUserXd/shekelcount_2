<template>
    <div class="flex items-end">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-6">
                    <div class="flex items-center">
                        <PencilIcon class="h-8 w-8 text-gray-500" aria-hidden="true"/>
                        <div class="ml-4 text-lg leading-7 font-semibold">
                            <a href="#" @click="$refs['categorySelect'].focus()"
                               class="underline text-gray-900 ">
                                Write
                            </a>
                        </div>
                    </div>

                    <div class="ml-12">
                        <div class="mt-2 text-gray-600 text-sm">
                            Quickly add an entry to one of your categories by selecting it in input below.
                            <div class="flex flex-col mt-5">
                                <label>
                                    Select Category
                                </label>
                                <category-select
                                    ref="categorySelect"
                                    :categories="categories"
                                    v-model="category"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
                    <div class="flex items-center">
                        <UploadIcon class="h-8 w-8 text-gray-500" aria-hidden="true"/>
                        <div class="ml-4 text-lg leading-7 font-semibold">
                            <a href="#" @click="triggerImportModal" class="underline text-gray-900">
                                Import
                            </a>
                        </div>
                    </div>

                    <div class="ml-12">
                        <div class="mt-2 text-gray-600 text-sm flex flex-col">
                            Import all outlays from your bank account. To do so, you need to make sure that shekelcount
                            supports your bank's export table schema, and if so, upload operations file in modal from
                            links nearby.

                            <button type="button"
                                    @click="triggerImportModal"
                                    class="inline-flex mt-5 items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <UploadIcon class="-ml-1 mr-3 h-5 w-5" aria-hidden="true"/>
                                Import Entries
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200">
                    <div class="flex items-center">
                        <SwitchHorizontalIcon class="h-8 w-8 text-gray-500" aria-hidden="true"/>
                        <div class="ml-4 text-lg leading-7 font-semibold">
                            <a href="#" @click="redirectToMutators" class="underline text-gray-900">
                                Add Mutator
                            </a>
                        </div>
                    </div>

                    <div class="ml-12">
                        <div class="mt-2 text-gray-600 text-sm flex flex-col">
                            Bank's export data is not always sorted the way you want, so you can easily make it your way
                            by creating a mutator, which can exclude entries, move them to another category, etc.

                            <button type="button"
                                    @click="redirectToMutators"
                                    class="inline-flex mt-5 items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <SwitchHorizontalIcon class="-ml-1 mr-3 h-5 w-5" aria-hidden="true"/>
                                Go to Mutators page
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 md:border-l">
                    <div class="flex items-center">
                        <SearchIcon class="h-8 w-8 text-gray-500" aria-hidden="true"/>
                        <div class="ml-4 text-lg leading-7 font-semibold">
                            <a href="#" @click="$refs['searchDescription'].focus()"
                               class="underline text-gray-900">
                                Search
                            </a>
                        </div>
                    </div>

                    <div class="ml-12">
                        <div class="mt-2 text-gray-600 text-sm">
                            When you're done sorting your records, you're free to view them in any way you like. For
                            instance, a simple description search.
                            <div class="flex flex-col mt-5">
                                <label for="description">
                                    Description
                                </label>
                                <textarea rows="1" ref="searchDescription" v-model="searchDescription" id="description"
                                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"/>
                                <button type="button"
                                        @click="redirectToSearch"
                                        class="inline-flex mt-2 items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <SearchIcon class="-ml-1 mr-3 h-5 w-5" aria-hidden="true"/>
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <import-modal
            :import-options="$page.props.import.options"
            :import-extensions="$page.props.import.extensions"
            :user-mutators="$page.props.mutators"
            ref="importModal"/>
    </div>
</template>

<script>

import {UploadIcon, PencilIcon, SwitchHorizontalIcon, SearchIcon} from "@heroicons/vue/outline";
import ImportModal from "@/Components/Entries/ImportModal";
import {Inertia} from "@inertiajs/inertia";
import CategorySelect from "@/Components/Entries/Category/CategorySelect";

export default {
    props: {
        categories: Array
    },
    watch: {
        category: {
            handler(category) {
                Inertia.visit(route('entries.categories.show', {
                    category: category.id,
                    write_entry: 1
                }))
            }
        }
    },
    data() {
        return {
            category: null,
            searchDescription: null,
        }
    },
    components: {
        CategorySelect,
        ImportModal,
        SearchIcon,
        SwitchHorizontalIcon,
        PencilIcon,
        UploadIcon,
    },
    methods: {
        triggerImportModal() {
            this.$refs['importModal'].init()
        },
        redirectToMutators() {
            alert('Work in progress')
        },
        redirectToSearch() {
            Inertia.visit(route('search.index', {
                description: this.searchDescription
            }))
        }
    }
}
</script>
