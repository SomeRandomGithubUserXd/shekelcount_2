<template>
    <div
        class="flex flex-col shadow-lg rounded bg-gray-100 p-5">
        <div class="flex">
            <h3 class="font-semibold text-lg flex items-center">
                {{ mutator.name }}
            </h3>
            <div class="ml-auto flex items-center">
                <div>
                    <tippy content="Delete Mutator">
                        <TrashIcon class="w-5 h-5 text-gray-600 cursor-pointer hover:text-red-500 transition"
                                   @click="deleteMutator"/>
                    </tippy>
                </div>
            </div>
        </div>
        <div class="flex flex-col mt-3">
            <div class="flex items-center">
                <AdjustmentsIcon class="w-5 h-5 mr-1"/>
                Type:&nbsp;<span class="font-medium">{{
                    mutator.type
                }}</span>
            </div>
            <div class="flex flex-col mt-3">
                <div class="flex items-center">
                    <EyeIcon class="w-5 h-5 mr-1"/>
                    Transform Visualisation:
                </div>
                <div class="flex items-center mt-1">
                    <span class="font-medium">{{ mutator.transform_visualisation.before }}</span>
                    <ArrowSmRightIcon class="mx-3 w-5 h-5"/>
                    <span class="font-medium">{{ mutator.transform_visualisation.after }}</span>
                </div>
            </div>
            <div class="flex flex-col mt-3" v-if="mutator.additional_info">
                <div class="flex items-center">
                    <ViewListIcon class="w-5 h-5 mr-1"/>
                    Additional Statements:
                </div>
                <div class="flex items-center mt-1" v-for="statement in mutator.additional_info">
                    - {{ statement.key }}:&nbsp;<span class="font-medium">{{ statement.value }}</span>
                </div>
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
    AnnotationIcon,
    AdjustmentsIcon,
    EyeIcon,
    PencilAltIcon,
    ViewListIcon,
    CollectionIcon,
    ArrowSmRightIcon
} from "@heroicons/vue/solid"
import CategoryModal from "@/Components/Entries/Category/CategoryModal";
import CategoryDeleteModal from "@/Components/Entries/Category/CategoryDeleteModal";

export default {
    components: {
        CategoryDeleteModal,
        CollectionIcon,
        ViewListIcon,
        CategoryModal,
        EyeIcon,
        TrashIcon,
        PencilAltIcon,
        AdjustmentsIcon,
        AnnotationIcon,
        ArrowSmRightIcon,
        Tippy,
        CurrencyDollarIcon
    },
    props: {
        mutator: Object
    },
    methods: {
        deleteMutator() {
            this.$emit('deleteMutator', this.mutator.id)
        }
    }
}
</script>
