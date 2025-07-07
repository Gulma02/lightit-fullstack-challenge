<template>
    <div class="relative">
        <select
            id="mySelect"
            name="mySelect"
            class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent sm:text-sm appearance-none pr-8"
            v-model="prefixContent"
        >
            <option value="">Select an option</option>
            <option v-for="prefix of prefixes" :value="prefix.code" :label="prefix.code" />
        </select>
    </div>
</template>

<script setup lang="ts">
    import {onBeforeMount, ref} from "vue";
    import axios from "axios";

    const prefixes = ref([])
    onBeforeMount(() => {
        axios.get(route("prefix.list")).then((response) => {
            console.log(response.data)
            prefixes.value = response.data.prefix;
        })
    })

    const prefixContent = defineModel({
        type: [String, null],
        required: true
    })
</script>

<style scoped>

</style>
