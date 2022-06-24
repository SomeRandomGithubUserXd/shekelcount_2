<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="open = false">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                                 enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                                 leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"/>
                </TransitionChild>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <TransitionChild as="template" enter="ease-out duration-300"
                                 enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                 leave-from="opacity-100 translate-y-0 sm:scale-100"
                                 leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <form
                        autocomplete="off"
                        @submit.prevent="handleForm"
                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <PlusIcon class="h-6 w-6 text-green-600" aria-hidden="true"/>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900">
                                    {{ !mode ? "Create" : "Edit" }} Category
                                </DialogTitle>
                                <div class="mt-5 flex flex-col">
                                    <validation-errors class="mb-4"/>
                                    <div>
                                        <label for="name">
                                            Name
                                        </label>
                                        <Input id="name" class="block w-full p-2 shadow" v-model="form.name"/>
                                    </div>
                                    <div class="mt-3">
                                        <label for="color">
                                            Color
                                        </label>
                                        <Input id="color" class="block w-full p-2 shadow" v-model="form.color"
                                               type="color"/>
                                    </div>
                                    <div class="mt-3">
                                        <label for="icon">
                                            Icon
                                        </label>
                                        <v-select @search="onIconSearch" :options="iconSelectPaginated"
                                                  v-model="form.icon">
                                            <template v-slot:option="option">
                                                <i :class="option.label"></i>
                                                <span class="ml-2 capitalize">{{ option.label.split("-").pop() }}</span>
                                            </template>
                                            <template v-slot:list-footer>
                                                <div class="flex w-full justify-between items-center">
                                                    <button type="button"
                                                            class="w-1/4 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                            :disabled="!iconSelectHasPrevPage"
                                                            @click="iconSelect.offset -= iconSelect.limit">
                                                        <ArrowSmLeftIcon class="-ml-1 mr-2 h-5 w-5"
                                                                         aria-hidden="true"/>
                                                        Prev
                                                    </button>
                                                    <span class="text-l text-gray-700">{{
                                                            iconSelect.offset + iconSelectPaginated.length
                                                        }}/{{ this.iconSelectFiltered.length }}</span>
                                                    <button type="button"
                                                            :disabled="!iconSelectHasNextPage"
                                                            @click="iconSelect.offset += iconSelect.limit"
                                                            class="inline-flex w-1/4 items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Next
                                                        <ArrowSmRightIcon class="ml-1 mr-2 h-5 w-5"
                                                                          aria-hidden="true"/>
                                                    </button>
                                                </div>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ !mode ? "Create" : "Update" }}
                            </button>
                            <button type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                    ref="cancelButtonRef"
                                    @click="open = false">
                                Cancel
                            </button>
                        </div>
                    </form>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {PlusIcon, ArrowSmLeftIcon, ArrowSmRightIcon} from '@heroicons/vue/outline'
import Button from "@/Components/Button";
import {useForm} from "@inertiajs/inertia-vue3";
import Input from "@/Components/Input";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import ValidationErrors from "@/Components/ValidationErrors";

export default {
    components: {
        ValidationErrors,
        Input,
        Button,
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        PlusIcon,
        vSelect,
        ArrowSmLeftIcon,
        ArrowSmRightIcon
    },
    data() {
        return {
            open: false,
            mode: 0,
            form: useForm({
                category_id: null,
                name: "",
                color: '#000000',
                icon: null
            }),
            iconSelect: {
                search: '',
                offset: 0,
                limit: 8,
            }
        }
    },
    computed: {
        iconSelectFiltered() {
            return this.$page.props.icons.filter((icon) =>
                icon.toLocaleLowerCase().includes(this.iconSelect.search.toLocaleLowerCase())
            )
        },
        iconSelectPaginated() {
            return this.iconSelectFiltered.slice(this.iconSelect.offset, this.iconSelect.limit + this.iconSelect.offset)
        },
        iconSelectHasNextPage() {
            const nextOffset = this.iconSelect.offset + this.iconSelect.limit
            return !!this.iconSelectFiltered.slice(nextOffset, this.iconSelect.limit + nextOffset).length

        },
        iconSelectHasPrevPage() {
            const prevOffset = this.iconSelect.offset - this.iconSelect.limit
            return !!this.iconSelectFiltered.slice(prevOffset, this.iconSelect.limit + prevOffset).length

        },
    },
    methods: {
        handleForm() {
            !this.mode ? this.create() : this.update()
        },
        init(form, mode) {
            this.open = true;
            this.form = form
            this.mode = mode
        },
        create() {
            this.form.post(route('entries.categories.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.open = false
                    this.form.reset()
                }
            })
        },
        update() {
            this.form.patch(route('entries.categories.update', this.form.category_id), {
                preserveScroll: true,
                onSuccess: () => {
                    this.open = false
                    this.form.reset()
                }
            })
        },
        onIconSearch(query) {
            this.iconSelect.search = query
            this.iconSelect.offset = 0
        },
    }
}
</script>

<style>
.vs__dropdown-menu {
    border-left: 0;
    border-right: 0;
    border-bottom: 0;
    padding: 0;
}
</style>
