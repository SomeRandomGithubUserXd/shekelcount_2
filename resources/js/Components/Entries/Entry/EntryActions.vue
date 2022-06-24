<template>
    <div class="flex items-center">
        <div class="mr-3 flex">
            <tippy content="Transfer to Category">
                <SwitchHorizontalIcon @click="transferToCategory"
                            class="w-6 h-6 text-gray-600 hover:text-black transition cursor-pointer"/>
            </tippy>
            <tippy content="Delete" class="ml-3">
                <TrashIcon @click="deleteEntries"
                            class="w-6 h-6 text-gray-600 hover:text-black transition cursor-pointer"/>
            </tippy>
        </div>
    </div>
</template>

<script>
import 'tippy.js/dist/tippy.css'
import {Tippy} from 'vue-tippy'
import {SwitchHorizontalIcon, TrashIcon} from "@heroicons/vue/solid";

export default {
    components: {
        Tippy,
        TrashIcon,
        SwitchHorizontalIcon
    },
    props: {
        entryIds: Array
    },
    methods: {
        transferToCategory() {
            this.$emit('transferToCategory')
        },
        deleteEntries() {
            this.$emit('deleteEntries')
        }
    },
}
</script>
