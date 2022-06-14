<template>
    <div
        class="flex flex-col bg-gray-100 shadow-lg rounded p-5"
        :class="[selected ? 'ring-offset-2 ring-2 ring-indigo-500' : '']">
        <div class="flex items-center">
            <tippy content="Unmark">
            <span
                v-if="entry.is_new"
                @click="unmarkNew"
                class="inline-flex mr-2 items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 cursor-pointer">
                New
            </span>
            </tippy>
            <i
                class="mr-3 text-2xl text-lg"
                v-if="category.icon"
                :class="category.icon"
                :style="{color: category.color}"></i>
            <h3 class="font-semibold text-lg flex items-center">
                {{ category.name }}
            </h3>
            <div class="ml-auto flex items-center">
                <div>
                    <tippy content="Edit Entry">
                        <PencilAltIcon class="w-5 h-5 text-gray-600 cursor-pointer hover:text-green-500 transition"
                                       @click="editEntry"/>
                    </tippy>
                </div>
                <div class="ml-2">
                    <tippy content="Delete Entry">
                        <TrashIcon class="w-5 h-5 text-gray-600 cursor-pointer hover:text-red-500 transition"
                                   @click="deleteEntry"/>
                    </tippy>
                </div>
                <div class="ml-2" v-if="selectable">
                    <tippy content="Select">
                        <input
                            @click="handleSelectSwitch"
                            :checked="selected"
                            type="checkbox"
                            class="outline-none h-4 w-4 text-indigo-600 border-gray-300 rounded cursor-pointer"/>
                    </tippy>
                </div>
            </div>
        </div>
        <div class="flex flex-col mt-3">
            <div class="flex items-center">
                <CurrencyDollarIcon class="w-5 h-5 mr-1"/>
                Sum:&nbsp;<span class="font-medium text-red-500">-{{
                    priceFormat(entry.sum)
                }}</span>
            </div>
            <div class="flex mt-2">
                <DocumentTextIcon class="w-5 h-5 mr-1"/>
                Description:&nbsp;<span class="font-medium">{{
                    entry.description
                }}</span>
            </div>
            <div class="flex mt-2">
                <ClockIcon class="w-5 h-5 mr-1"/>
                Performed At:&nbsp;<span class="font-medium">{{
                    entry.performed_at
                }}</span>
            </div>
            <div v-if="entry.entry_import_bank" class="flex mt-2">
                <LibraryIcon class="w-5 h-5 mr-1"/>
                Imported From:&nbsp;<span class="font-medium">{{
                    entry.entry_import_bank.name
                }}</span>&nbsp; <img width="30" height="30" class="ml-1" :src="entry.entry_import_bank.logo"
                                     alt="bank_logo"/>
            </div>
        </div>
    </div>
</template>

<script>
import {
    ClockIcon,
    CurrencyDollarIcon,
    DocumentTextIcon,
    LibraryIcon,
    PencilAltIcon,
    TrashIcon
} from "@heroicons/vue/solid";
import 'tippy.js/dist/tippy.css'
import {Tippy} from 'vue-tippy'
import "@fortawesome/fontawesome-free/css/all.css"
import {Inertia} from "@inertiajs/inertia";


export default {
    props: {
        category: Object,
        entry: Object,
        selectable: {
            required: false,
            type: Boolean,
            default: false
        },
        selected: {
            data() {
                return false
            }
        }
    },
    components: {
        PencilAltIcon, TrashIcon, Tippy, CurrencyDollarIcon, ClockIcon, DocumentTextIcon, LibraryIcon
    },
    methods: {
        priceFormat(number) {
            return priceFormat(number);
        },
        editEntry() {
            this.$emit('editEntry', this.entry)
        },
        deleteEntry() {
            this.$emit('deleteEntry', this.entry)
        },
        handleSelectSwitch(pointerEvent) {
            const isSelected = pointerEvent.target.checked;
            let eventToEmit = 'selectEntry'
            if (!isSelected) eventToEmit = 'unselectEntry'
            this.$emit(eventToEmit, this.entry.id)
        },
        unmarkNew() {
            this.entry.is_new = false
            Inertia.patch(route('entries.unmark_new', this.entry.id), {}, {
                preserveScroll: true,
            })
        }
    }
}
</script>
