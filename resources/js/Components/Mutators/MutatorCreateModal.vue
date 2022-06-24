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
                        @submit.prevent="addMutator" autocomplete="off"
                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <PlusIcon class="h-6 w-6 text-indigo-600" aria-hidden="true"/>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900"> Add mutator
                                </DialogTitle>
                                <div class="mt-5 flex flex-col">
                                    <validation-errors class="mb-4"/>
                                    <div class="mt-3">
                                        <label for="name">
                                            Mutator Name
                                        </label>
                                        <Input id="name" type="text" class="w-full" v-model="form.name"/>
                                    </div>
                                    <div class="mt-3">
                                        <label>
                                            Type
                                        </label>
                                        <v-select
                                            ref="self"
                                            :get-option-label="(category) => category.name"
                                            :options="mutatorOptions"
                                            v-model="form.option">
                                            <template v-slot:option="option">
                                                <div class="flex flex-col ml-2">
                                                    <span class="font-semibold">{{ option.name }}</span>
                                                    <p class="whitespace-normal">{{ option.description }}</p>
                                                </div>
                                            </template>
                                        </v-select>
                                    </div>
                                    <div class="flex mt-3"
                                         :class="meta.type === 'checkbox' ? 'items-center' : 'flex-col'"
                                         v-for="meta in form?.option?.params">
                                        <label :for="meta.name">
                                            {{ startCase(meta.name.toString()) }}
                                        </label>
                                        <Input :id="meta.name"
                                               :class="meta.type !== 'checkbox' || 'ml-2'"
                                               :type="meta.type"
                                               :key="meta.name"
                                               :value="form.dynamicFields[meta.name]"
                                               @input="setDynamicField($event, meta.name)"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Add
                            </button>
                            <button type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                                    @click="open = false" ref="cancelButtonRef">Cancel
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
import ValidationErrors from "@/Components/ValidationErrors";
import {useForm} from "@inertiajs/inertia-vue3";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import {startCase} from "lodash";
import Input from "@/Components/Input";
import {orderBy} from "lodash";

export default {
    components: {
        Input,
        ValidationErrors,
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        PlusIcon,
        vSelect
    },
    data() {
        return {
            open: false,
            form: useForm({
                name: '',
                option: null,
                dynamicFields: {}
            }),
        }
    },
    props: {
        mutatorOptions: Array
    },
    methods: {
        startCase: startCase,
        init() {
            this.open = true
        },
        addMutator() {
            this.form.transform((data) => ({
                ...data,
                ...data.dynamicFields,
                mutator_option_id: data?.option?.id,
            })).post(route('mutators.store'), {
                onSuccess: () => {
                    this.form.reset()
                    this.open = false
                }
            })
        },
        setDynamicField(event, name) {
            const elem = event.target
            this.form.dynamicFields[name] = elem.type === 'checkbox'
                ? elem.checked
                : elem.value
        }
    }
}
</script>
