<template>
    <Head title="Mutators"/>

    <Authenticated>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight items-center">
                Mutators
            </h2>
        </template>
        <div class="flex flex-col p-6 bg-white border-b border-gray-200">
            <div class="w-full flex">
                <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                    Mutators
                </h2>
                <div class="ml-auto">
                    <div class="flex items-center">
                        <div class="mr-3">
                            <tippy content="Create Category">
                                <PlusIcon @click="addMutator"
                                          class="w-6 h-6 text-gray-600 hover:text-black transition cursor-pointer"/>
                            </tippy>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col mt-3">
                <p v-if="!$page.props.mutators?.data?.length">No mutators</p>
                <mutator-block
                    class="mb-3"
                    @deleteMutator="deleteMutator"
                    v-for="mutator in $page.props.mutators.data"
                    :mutator="mutator"/>
            </div>
            <pagination
                v-if="$page.props.mutators?.data?.length"
                :current="$page.props.mutators?.meta?.current_page"
                @switch-page="switchPage"
                :max="$page.props.mutators.meta?.last_page"/>
        </div>
        <create-mutator-modal :mutator-options="$page.props.mutator_options" ref="createModal"/>
        <mutator-delete-modal ref="deleteModal"/>
    </Authenticated>
</template>

<script>

import {PlusIcon} from "@heroicons/vue/solid"
import {Head} from "@inertiajs/inertia-vue3";
import Authenticated from "@/Layouts/Authenticated";
import {Tippy} from "vue-tippy";
import 'tippy.js/dist/tippy.css'
import CreateMutatorModal from "@/Components/Mutators/MutatorCreateModal";
import MutatorBlock from "@/Components/Mutators/MutatorBlock";
import Pagination from "@/Components/Pagination";
import MutatorDeleteModal from "@/Components/Mutators/MutatorDeleteModal";
import {Inertia} from "@inertiajs/inertia";

export default {
    components: {
        MutatorDeleteModal,
        Pagination,
        MutatorBlock,
        CreateMutatorModal,
        Authenticated, Head, PlusIcon, Tippy,
    },
    methods: {
        addMutator() {
            this.$refs['createModal'].init()
        },
        deleteMutator(mutatorId) {
            this.$refs['deleteModal'].init(mutatorId)
        },
        switchPage(page) {
            Inertia.visit(route('mutators.index', {page}), {
                preserveScroll: true
            })
        }
    }
}
</script>
