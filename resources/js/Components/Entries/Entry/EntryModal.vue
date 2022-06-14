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
                                    {{ !mode ? "Write" : "Edit" }} Entry
                                </DialogTitle>
                                <div class="mt-5 flex flex-col">
                                    <validation-errors class="mb-4"/>
                                    <div class="mb-3" v-if="mode">
                                        <label>
                                            Category
                                        </label>
                                        <category-select
                                            ref="categorySelect"
                                            :categories="userCategories"
                                            v-model="form.category"/>
                                    </div>
                                    <div>
                                        <label for="sum">
                                            Sum (₽)
                                        </label>
                                        <Input id="sum" class="block w-full p-2 shadow" v-model="form.sum" type="number"
                                               step=".01"/>
                                    </div>
                                    <div class="mt-3">
                                        <label for="description">
                                            Description
                                        </label>
                                        <textarea id="description" v-model="form.description"
                                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"/>
                                    </div>
                                    <div class="mt-3 flex flex-col">
                                        <label for="performed_at">
                                            Date
                                        </label>
                                        <date-picker mode="dateTime" class="inline-block h-full"
                                                     v-model="form.performed_at">
                                            <template v-slot="{ inputValue, togglePopover }">
                                                <div class="flex items-center">
                                                    <button
                                                        type="button"
                                                        class="p-2 bg-blue-100 border border-blue-200 hover:bg-blue-200 text-blue-600 rounded-l focus:bg-blue-500 focus:text-white focus:border-blue-500 focus:outline-none"
                                                        @click="togglePopover()">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20"
                                                            class="w-4 h-4 fill-current">
                                                            <path
                                                                d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"
                                                            ></path>
                                                        </svg>
                                                    </button>
                                                    <input
                                                        id="performed_at"
                                                        :value="inputValue"
                                                        class="bg-white text-gray-700 w-full py-1 px-2 appearance-none border rounded-r focus:outline-none focus:border-blue-500"
                                                        readonly
                                                    />
                                                </div>
                                            </template>
                                        </date-picker>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ !mode ? "Write" : "Update" }}
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
import {PlusIcon} from '@heroicons/vue/outline'
import Button from "@/Components/Button";
import {useForm} from "@inertiajs/inertia-vue3";
import Input from "@/Components/Input";
import 'v-calendar/dist/style.css';
import {DatePicker} from 'v-calendar';
import ValidationErrors from "@/Components/ValidationErrors";
import CategorySelect from "@/Components/Categories/CategorySelect";

export default {
    components: {
        CategorySelect,
        ValidationErrors,
        Input,
        Button,
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        PlusIcon,
        DatePicker
    },
    props: {
        userCategories: Array
    },
    data() {
        return {
            open: false,
            mode: 0,
            form: useForm({
                entry_id: null,
                category: null,
                sum: "",
                description: "",
                performed_at: new Date()
            })
        }
    },
    computed: {
        transformedForm: {
            get() {
                return this.form.transform((data) => ({
                    ...data,
                    category_id: data.category.id
                }))
            }
        }
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
            this.transformedForm.post(route('entries.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.open = false
                    this.form.reset()
                }
            })
        },
        update() {
            this.transformedForm.patch(route('entries.update'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.open = false
                    this.form.reset()
                }
            })
        }
    }
}
</script>
