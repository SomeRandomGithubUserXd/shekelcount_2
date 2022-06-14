<template>
    <nav class="pagination user-select-none with-pointer">
        <div class="flex justify-center">
            <div class="col0">
                <a @click="switchPage(current_page - 1)" class="pagination__link">
                    Prev
                </a>
            </div>
            <div class="flex">
                <div class="col0" v-if="current_page !== 1">
                    <p @click="switchPage(1)" class="pagination__link">
                        1
                    </p>
                </div>
                <div class="col0" v-if="current_page - 1 && current_page - 1 !== 1">
                    <p @click="switchPage(current_page - 1)"
                       class="pagination__link">
                        {{ current_page - 1 }}
                    </p>
                </div>
                <div class="col0">
                    <p @click="switchPage(current_page)"
                       class="pagination__link pagination__link_current">
                        {{ current_page }}
                    </p>
                </div>
                <div class="col0" v-if="current_page + 1 <= max && current_page + 1 !== max">
                    <p @click="switchPage(current_page + 1)" class="pagination__link">
                        {{ current_page + 1 }}
                    </p>
                </div>
                <div class="col0" v-if="current_page !== max">
                    <p @click="switchPage(max)" class="pagination__link">
                        {{ max }}
                    </p>
                </div>
            </div>
            <div class="col0">
                <p @click="switchPage(current_page + 1)" class="pagination__link">
                    Next
                </p>
            </div>
        </div>
    </nav>
</template>

<script>

export default {
    name: 'Pagination',
    props: {
        current: Number,
        max: Number,
    },
    components: {},
    data() {
        return {
            current_page: this.current || 1,
        }
    },
    methods: {
        switchPage(page) {
            if (page < 1 || page > this.max) return;
            this.current_page = page;
            this.$emit('switch-page', page)
        }
    },
    watch: {
        current: function () {
            this.current_page = this.current;
        }
    }
}

</script>
<style scoped>
.pagination {
    border-top: 1px solid #e5e5e5;
    margin-top: 2rem;
}

.pagination__link {
    display: block;
    padding: .4rem .5rem;
    font-size: .9rem;
    cursor: pointer;
    border-top: 3px solid transparent;
}

.pagination__link_current {
    border-color: #3D8CE4;
}
</style>
