<template>
    <div>
        <v-select
            ref="self"
            :get-option-label="(category) => category.name"
            :options="categories"
            v-model="modelValue">
            <template v-slot:option="category">
                <div class="flex items-center">
                    <i :class="category.icon" :style="{color: category.color}"/>
                    <span class="ml-2">{{ category.name }}</span>
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        vSelect
    },
    props: {
        categories: Array,
        modelValue: {
            data() {
                return false
            }
        }
    },
    methods: {
        focus() {
            this.$refs['self'].$el.querySelector('.vs__search').focus()
        },
    },
    watch: {
        modelValue: {
            handler(val) {
                this.$emit('update:modelValue', val)
            }
        }
    }
}
</script>
