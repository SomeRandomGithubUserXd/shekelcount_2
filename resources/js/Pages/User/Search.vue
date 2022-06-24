<template>
    <Head title="Search"/>

    <Authenticated id="top">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight items-center">
                Search
            </h2>
        </template>
        <div class="flex flex-col w-full p-6 bg-white border-b border-gray-200">
            <div class="w-full flex">
                <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                    Search for Entries
                </h2>
            </div>
            <form class="flex flex-col" autocomplete="off"
                  @submit.prevent="reloadData(1)">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <div class="mt-5 flex flex-col">
                            <validation-errors class="mb-4"/>
                            <div class="flex flex-col">
                                <label>
                                    Order by
                                </label>
                                <div class="relative inline-flex shadow-sm rounded-l-md">
                                    <v-select
                                        class="relative inline-flex items-center partof__order-by-select capitalize"
                                        :get-option-label="(column) => column.replace('_', ' ')"
                                        :options="['sum', 'description', 'performed_at']"
                                        v-model="form.orderBy.column"/>
                                    <button type="button"
                                            @click="switchOrderDirection"
                                            class="-ml-px relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        <component
                                            :is="sortIcon"
                                            class="-ml-1 mr-2 h-5 w-5 text-gray-400"
                                            aria-hidden="true"/>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label>
                                    Category
                                </label>
                                <category-select
                                    :categories="$page.props.categories"
                                    v-model="form.category"/>
                            </div>
                            <div class="mt-3">
                                <label for="sum_is_more_than">
                                    Sum is More than (₽)
                                </label>
                                <Input type="number" step=".01" v-model="form.sum_is_more_than" id="sum_is_more_than"
                                       class="block w-full p-2 shadow"/>
                            </div>
                            <div class="mt-3">
                                <label for="sum_is_less_than">
                                    Sum is Less than (₽)
                                </label>
                                <Input type="number" v-model="form.sum_is_less_than" step=".01" id="sum_is_less_than"
                                       class="block w-full p-2 shadow"/>
                            </div>
                            <div class="mt-3">
                                <label for="description">
                                    Description
                                </label>
                                <textarea v-model="form.description" id="description"
                                          class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"/>
                            </div>
                            <div class="mt-3 flex flex-col">
                                <label>
                                    Date Range
                                </label>
                                <date-picker class="w-full__important" mode="dateTime" v-model="form.date_range"
                                             is-range/>
                            </div>
                            <div class="mt-3 flex justify-between sm:justify-start">
                                <button type="submit"
                                        class="w-1/2 sm:w-auto inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <SearchIcon class="-ml-1 mr-3 h-5 w-5" aria-hidden="true"/>
                                    Search
                                </button>
                                <button type="button" @click="resetFilter"
                                        class="ml-3 w-1/2 sm:w-auto inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <TrashIcon class="-ml-1 mr-3 h-5 w-5" aria-hidden="true"/>
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="flex flex-col mt-5" id="entry_list">
                <div class="flex mt-3 flex-col md:flex-row">
                    <h2 class="text-lg font-semibold flex">Entries Total: <span
                        class="text-red-500 ml-auto md:ml-3">-{{ priceFormat($page.props.misc.entries_total) }}</span>
                    </h2>
                    <div class="md:w-auto w-full flex items-center mt-3 mb-2 md:my-0 md:ml-auto">
                        <entry-actions
                            @transferToCategory="transferToCategory"
                            @deleteEntries="deleteEntries"
                            v-if="selectedEntries.length"
                            :entry-ids="selectedEntries"/>
                        <div v-if="$page.props.entries.data.length" class="relative flex items-start ml-auto md:ml-0">
                            <div class="flex items-center h-5">
                                <input
                                    @change="selectAllEntries"
                                    v-model="selectWholePage"
                                    id="select_all"
                                    type="checkbox"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"/>
                            </div>
                            <div class="ml-3 text-sm user-select-none with-pointer">
                                <label
                                    for="select_all"
                                    class="font-medium text-gray-700">Select Whole Page</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="entry-list mt-3">
                    <p v-if="!$page.props.entries.data.length">No entries</p>
                    <div
                        class="min-h-50"
                        :key="key"
                        v-for="(entry, key) in $page.props.entries.data">
                        <entry-block
                            :selectable="true"
                            :selected="checkIfEntrySelected(entry.id)"
                            class="w-full h-full"
                            @editEntry="editEntry"
                            @deleteEntry="deleteEntry"
                            @selectEntry="selectEntry"
                            @unselectEntry="unselectEntry"
                            :category="entry.category"
                            :entry="entry"/>
                    </div>
                </div>
            </div>
            <pagination
                v-if="$page.props.entries.data.length"
                :current="$page.props.entries.current_page"
                @switch-page="switchPage"
                :max="$page.props.entries.last_page"/>
        </div>
        <entry-modal
            :user-categories="$page.props.categories"
            ref="createModal"/>
        <entry-delete-modal ref="deleteModal"/>
        <transfer-to-category-modal
            @transferred="onEntryActionComplete"
            :user-categories="$page.props.categories"
            ref="transferModal"/>
        <delete-entries-modal
            ref="deleteManyModal"
            @deleted="onEntryActionComplete"
            :entry-ids="selectedEntries"/>
    </Authenticated>
</template>

<script>

import {SearchIcon, TrashIcon, SortAscendingIcon, SortDescendingIcon} from "@heroicons/vue/solid"
import 'v-calendar/dist/style.css';
import {DatePicker} from 'v-calendar';
import {Head, useForm} from "@inertiajs/inertia-vue3";
import Authenticated from "@/Layouts/Authenticated";
import EntryBlock from "@/Components/Entries/Entry/EntryBlock";
import Pagination from "@/Components/Pagination";
import {Inertia} from "@inertiajs/inertia";
import EntryModal from "@/Components/Entries/Entry/EntryModal";
import EntryDeleteModal from "@/Components/Entries/Entry/EntryDeleteModal";
import Input from "@/Components/Input";
import ValidationErrors from "@/Components/ValidationErrors";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
import collect from 'collect.js';
import EntryActions from "@/Components/Entries/Entry/EntryActions";
import CategorySelect from "@/Components/Entries/Category/CategorySelect";
import {union} from "underscore"
import TransferToCategoryModal from "@/Components/SelectedEntryActions/TransferToCategoryModal";
import DeleteEntriesModal from "@/Components/SelectedEntryActions/DeleteEntriesModal";
import {toast} from "@/Traits/FiresToasts";
import {getEntryForm, priceFormat} from "@/Traits/InteractsWithEntries";
import {scrollTo} from "@/Traits/InteractsWithWindow";

export default {
    components: {
        DeleteEntriesModal,
        TransferToCategoryModal,
        CategorySelect,
        EntryActions,
        SortAscendingIcon,
        SortDescendingIcon,
        vSelect,
        ValidationErrors,
        Input,
        EntryDeleteModal,
        EntryModal,
        Pagination,
        EntryBlock,
        Authenticated,
        Head,
        TrashIcon,
        SearchIcon,
        DatePicker
    },
    computed: {
        transformedForm: {
            get() {
                return {
                    ...this.form,
                    category_id: this.form.category.id ?? null
                }
            },
        },
        sortIcon: {
            get() {
                return this.form.orderBy.direction === "ASC"
                    ? SortAscendingIcon
                    : SortDescendingIcon
            }
        },
    },
    data() {
        return {
            form: {
                orderBy: {
                    column: this.getUrlParams('orderBy[column]', 'performed_at'),
                    direction: this.getUrlParams('orderBy[direction]', 'DESC')
                },
                sum_is_more_than: this.getUrlParams('sum_is_more_than'),
                sum_is_less_than: this.getUrlParams('sum_is_less_than'),
                description: this.getUrlParams('description'),
                date_range: {
                    start: this.getUrlParams('date_range[start]', new Date),
                    end: this.getUrlParams('date_range[end]', new Date)
                },
                category: this.getCategory(),
            },
            selectedEntries: [],
            selectWholePage: false
        }
    },
    watch: {
        selectedEntries: {
            deep: true,
            handler() {
                this.detectSelectedEntries()
            }
        }
    },
    methods: {
        switchOrderDirection() {
            this.form.orderBy.direction === "ASC"
                ? this.form.orderBy.direction = "DESC"
                : this.form.orderBy.direction = "ASC"
        },
        setUrlParams(data) {
            const params = new URLSearchParams(route('search.index', data).split('?')[1])
            window.history.pushState('', '', `?${params.toString()}`)
        },
        getUrlParams(key = '', orReturn = '') {
            const params = new URLSearchParams(location.search)
            if (key) return params.get(key) ?? orReturn
            return params
        },
        getCategory() {
            const categories = collect(this.$page.props.categories)
            const params = this.getUrlParams()
            if (params.has('category_id')) {
                return categories.firstWhere('id', '==', params.get('category_id'))
            }
            return {};
        },
        priceFormat: priceFormat,
        resetFilter(scrollToTop = true) {
            this.selectedEntries = []
            Inertia.visit(route('search.index'), {
                preserveScroll: true,
                onFinish: () => !scrollToTop || scrollTo('top'),
            })
        },
        editEntry(entry) {
            this.$refs['createModal'].init(this.getEntryForm(entry), 1)
        },
        getEntryForm(entry) {
            return useForm(getEntryForm(entry))
        },
        deleteEntry(entry_id) {
            this.$refs['deleteModal'].init(entry_id)
        },
        switchPage(page) {
            this.reloadData(page)
        },
        reloadData(page = 1, conflictPrevented = false) {
            if (page.constructor !== Number) {
                page = this.getUrlParams('page', 1)
            }
            this.setUrlParams({
                page,
                ...this.transformedForm
            })
            this.selectWholePage = false
            Inertia.reload({
                onFinish: () => {
                    this.detectSelectedEntries()
                    scrollTo('entry_list')
                    if (!conflictPrevented) {
                        const entriesMeta = this.$page.props.entries
                        if (!entriesMeta.data.length && entriesMeta.current_page !== 1) {
                            this.firePageSetToast(entriesMeta.last_page)
                            return this.reloadData(entriesMeta.last_page, true)
                        }
                    }
                },
            })
        },
        detectSelectedEntries() {
            const pageEntries = collect(this.$page.props.entries.data)
                .pluck('id')
                .items
            const pageSelected = pageEntries.every(element => {
                return this.selectedEntries.includes(element);
            })
            pageSelected
                ? this.selectWholePage = true
                : this.selectWholePage = false
        },
        selectEntry(id) {
            this.selectedEntries.push(id)
            this.getUrlParams()
        },
        unselectEntry(id) {
            const index = this.selectedEntries.indexOf(id)
            this.selectedEntries.splice(index, 1)
        },
        checkIfEntrySelected(id) {
            return this.selectedEntries.includes(id)
        },
        selectAllEntries(event) {
            const checked = event.target.checked
            let selectedEntries = this.selectedEntries
            const entryIds = collect(this.$page.props.entries.data).pluck('id').items
            if (checked) {
                selectedEntries = union(selectedEntries, entryIds)
            } else {
                for (let entryId of entryIds) {
                    const index = selectedEntries.indexOf(entryId)
                    selectedEntries.splice(index, 1)
                }
            }
            this.selectedEntries = selectedEntries
        },
        transferToCategory() {
            this.$refs['transferModal'].init(this.selectedEntries)
        },
        deleteEntries() {
            this.$refs['deleteManyModal'].init(this.selectedEntries)
        },
        onEntryActionComplete() {
            this.selectedEntries = []
            const entriesMeta = this.$page.props.entries
            if (!entriesMeta.total) {
                this.fireFilterResetToast()
                return this.resetFilter(false)
            }
            if (!entriesMeta.data.length && entriesMeta.current_page !== 1) {
                this.firePageSetToast(entriesMeta.last_page)
                return this.reloadData(entriesMeta.last_page, true)
            }
        },
        fireFilterResetToast() {
            this.$swal(toast({
                icon: 'info',
                title: 'Filter was force reset'
            }))
        },
        firePageSetToast(page) {
            this.$swal(toast({
                icon: 'info',
                title: 'Page was set to ' + page
            }))
        }
    }
}
</script>
