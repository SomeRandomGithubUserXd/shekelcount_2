<template>
    <Head :title="`${$page.props.category.name}`"/>

    <Authenticated>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight items-center">
                <i
                    class="mr-3 text-2xl"
                    v-if="$page.props.category.icon"
                    :class="$page.props.category.icon"
                    :style="{color: $page.props.category.color}"></i>
                {{ $page.props.category.name }}
            </h2>
        </template>
        <div class="flex flex-col w-full p-6 bg-white border-b border-gray-200">
            <div class="w-full flex">
                <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                    Entries
                </h2>
                <div class="ml-auto">
                    <div class="flex items-center">
                        <div>
                            <tippy content="Write Entry">
                                <PencilIcon @click="createEntry"
                                            class="w-6 h-6 text-gray-600 hover:text-black transition cursor-pointer"/>
                            </tippy>
                        </div>
                    </div>
                </div>
            </div>
            <div class="entry-list mt-3">
                <p v-if="!$page.props.entries.data.length">No entries</p>
                <div
                    class="min-h-50"
                    v-for="entry in $page.props.entries.data">
                    <entry-block
                        class="w-full h-full"
                        @editEntry="editEntry"
                        @deleteEntry="deleteEntry"
                        :category="$page.props.category"
                        :entry="entry"/>
                </div>
            </div>
            <pagination
                v-if="$page.props.entries.data.length"
                :current="$page.props.entries.current_page"
                @switch-page="switchPage"
                :max="$page.props.entries.last_page"/>
        </div>
        <entry-modal :user-categories="$page.props.categories" ref="createModal"/>
        <entry-delete-modal ref="deleteModal"/>
    </Authenticated>
</template>

<script>

import {Head} from '@inertiajs/inertia-vue3';
import {PencilIcon} from "@heroicons/vue/solid"
import "@fortawesome/fontawesome-free/css/all.css"
import Authenticated from "@/Layouts/Authenticated";
import EntryBlock from "@/Components/Entries/Entry/EntryBlock";
import EntryModal from "@/Components/Entries/Entry/EntryModal";
import {useForm} from "@inertiajs/inertia-vue3";
import EntryDeleteModal from "@/Components/Entries/Entry/EntryDeleteModal";
import Pagination from "@/Components/Pagination";
import {Inertia} from "@inertiajs/inertia";
import {Tippy} from 'vue-tippy'
import 'tippy.js/dist/tippy.css'
import {getEntryForm} from "@/Traits/InteractsWithEntries";

export default {
    components: {Pagination, EntryDeleteModal, EntryModal, EntryBlock, Authenticated, PencilIcon, Head, Tippy},
    mounted() {
        const params = new URLSearchParams(location.search);
        if(params.has('write_entry')) {
            this.createEntry()
        }
    },
    methods: {
        createEntry() {
            this.$refs['createModal'].init(this.getEntryForm(), 0)
        },
        editEntry(entry) {
            this.$refs['createModal'].init(this.getEntryForm(entry), 1)
        },
        deleteEntry(entry_id) {
            this.$refs['deleteModal'].init(entry_id)
        },
        switchPage(page) {
            const category = this.$page.props.category
            Inertia.visit(route('entries.categories.show', {
                page,
                category
            }));
        },
        getEntryForm(entry = null) {
            return useForm(getEntryForm(entry, this.$page.props.category))
        },
    }
}
</script>
