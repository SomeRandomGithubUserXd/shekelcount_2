<template>
    <Head title="Entries"/>

    <Authenticated>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Entries
            </h2>
        </template>

        <div class="flex flex-col p-6 bg-white border-b border-gray-200">
            <div class="w-full flex">
                <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                    Categories
                </h2>
                <div class="ml-auto">
                    <div class="flex items-center">
                        <div class="mr-3">
                            <tippy content="Create Category">
                                <FolderIcon @click="createCategory"
                                            class="w-6 h-6 text-gray-600 hover:text-black transition cursor-pointer"/>
                            </tippy>
                        </div>
                        <div class="mr-3">
                            <tippy content="Import Entries">
                                <UploadIcon @click="importEntries"
                                            class="w-6 h-6 text-gray-600 hover:text-black transition cursor-pointer"/>
                            </tippy>
                        </div>
                        <div>
                            <tippy content="Delete all categories">
                                <TrashIcon @click="deleteAllCategories"
                                           class="w-6 h-6 text-gray-600 hover:text-black transition cursor-pointer"/>
                            </tippy>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col mt-3">
                <p v-if="!$page.props.categories.data.length">No categories</p>
                <category-block
                    class="mb-3"
                    @editCategory="editCategory"
                    @deleteCategory="deleteCategory"
                    v-for="category in $page.props.categories.data"
                    :category="category"/>
            </div>
            <pagination
                v-if="$page.props.categories.data.length"
                :current="$page.props.categories.current_page"
                @switch-page="switchPage"
                :max="$page.props.categories.last_page"/>
        </div>
        <category-modal ref="createModal"/>
        <category-delete-modal ref="deleteModal"/>
        <import-modal
            :import-options="$page.props.import.options"
            :import-extensions="$page.props.import.extensions"
            :user-mutators="$page.props.mutators"
            ref="importModal"/>
        <delete-all-categories-modal ref="deleteAllModal"/>
    </Authenticated>
</template>

<script>
import "@fortawesome/fontawesome-free/css/all.css"
import Authenticated from "@/Layouts/Authenticated";
import {Tippy} from 'vue-tippy'
import 'tippy.js/dist/tippy.css'
import {FolderIcon, UploadIcon, TrashIcon} from "@heroicons/vue/solid"
import CategoryModal from "@/Components/Entries/Category/CategoryModal";
import CategoryBlock from "@/Components/Entries/Category/CategoryBlock";
import {useForm} from "@inertiajs/inertia-vue3";
import CategoryDeleteModal from "@/Components/Entries/Category/CategoryDeleteModal";
import ImportModal from "@/Components/Entries/ImportModal";
import Pagination from "@/Components/Pagination";
import {Inertia} from "@inertiajs/inertia";
import {Head} from '@inertiajs/inertia-vue3';
import DeleteAllCategoriesModal from "@/Components/Entries/Category/DeleteAllCategoriesModal";

export default {
    components: {
        DeleteAllCategoriesModal,
        Head,
        Pagination,
        ImportModal,
        CategoryDeleteModal,
        CategoryBlock,
        CategoryModal,
        Authenticated,
        Tippy,
        FolderIcon,
        UploadIcon,
        TrashIcon,
    },
    methods: {
        createCategory() {
            this.$refs['createModal'].init(this._getCategoryForm(), 0)
        },
        editCategory(category) {
            this.$refs['createModal'].init(this._getCategoryForm(category), 1)
        },
        deleteCategory(category_id) {
            this.$refs['deleteModal'].init(category_id)
        },
        _getCategoryForm(category = null) {
            return useForm({
                category_id: category ? category.id : null,
                name: category ? category.name : "",
                color: category ? category.color : "#000000",
                icon: category ? category.icon : null
            })
        },
        importEntries() {
            this.$refs['importModal'].init()
        },
        switchPage(page) {
            Inertia.visit(route('entries.index', {page}))
        },
        deleteAllCategories() {
            this.$refs['deleteAllModal'].init()
        }
    }
}
</script>
