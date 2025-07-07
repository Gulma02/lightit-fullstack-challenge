<template>
    <Modal :show="patientImgModalVisible">
        <img :src="patientDocImg">
    </Modal>
</template>

<script setup lang="ts">
    import Modal from "@/Components/Modal.vue";

    defineProps<{
        patientImgModalVisible: boolean,
        patientDocImg: string
    }>()
</script>
