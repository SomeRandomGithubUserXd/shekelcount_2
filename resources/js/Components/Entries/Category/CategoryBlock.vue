<template>
    <div
        class="flex flex-col shadow-lg rounded bg-gray-100 p-5">
        <div class="flex">
            <h3 class="font-semibold text-lg flex items-center">
                <i
                    class="mr-3 text-2xl"
                    v-if="category.icon"
                    :class="category.icon"
                    :style="{color: category.color}"></i>
                {{ category.name }}
            </h3>
            <div class="ml-auto flex items-center">
                <div class="mr-3">
                    <tippy content="List Entries">
                        <CollectionIcon class="w-5 h-5 text-gray-600 cursor-pointer hover:text-green-500 transition"
                                        @click="redirect"/>
                    </tippy>
                </div>
                <div class="mr-3">
                    <tippy content="Edit Category">
                        <PencilAltIcon class="w-5 h-5 text-gray-600 cursor-pointer hover:text-green-500 transition"
                                       @click="editCategory"/>
                    </tippy>
                </div>
                <div>
                    <tippy content="Delete Category">
                        <TrashIcon class="w-5 h-5 text-gray-600 cursor-pointer hover:text-red-500 transition"
                                   @click="deleteCategory"/>
                    </tippy>
                </div>
            </div>
        </div>
        <div class="flex flex-col mt-3">
            <div class="flex items-center">
                <CurrencyDollarIcon class="w-5 h-5 mr-1"/>
                Entries Sum:&nbsp;<span class="font-medium text-red-500">-{{
                    priceFormat(category.entries_sum)
                }}</span>
            </div>
            <div class="flex items-center mt-2">
                <ClockIcon class="w-5 h-5 mr-1"/>
                Created At:&nbsp;<span class="font-medium">{{
                    category.created_at
                }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import 'tippy.js/dist/tippy.css'
import {Tippy} from 'vue-tippy'
import {
    TrashIcon,
    CurrencyDollarIcon,
    BookOpenIcon,
    ClockIcon,
    PencilAltIcon,
    CollectionIcon
} from "@heroicons/vue/solid"
import CategoryModal from "@/Components/Entries/Category/CategoryModal";
import CategoryDeleteModal from "@/Components/Entries/Category/CategoryDeleteModal";
import {Inertia} from "@inertiajs/inertia";

export default {
    components: {
        CategoryDeleteModal,
        CollectionIcon,
        CategoryModal,
        TrashIcon,
        PencilAltIcon,
        ClockIcon,
        BookOpenIcon,
        Tippy,
        CurrencyDollarIcon
    },
    props: {
        category: Object
    },
    methods: {
        redirect() {
            Inertia.visit(route('entries.categories.show', this.category.id))
        },
        priceFormat(number) {
            return priceFormat(number);
        },
        editCategory() {
            this.$emit('editCategory', this.category)
        },
        deleteCategory() {
            this.$emit('deleteCategory', this.category)
        }
    }
}
</script>
