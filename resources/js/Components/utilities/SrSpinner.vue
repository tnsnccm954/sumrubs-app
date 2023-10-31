<script setup>
defineProps({
    isLoading: Boolean,
    loadType: String,
});
</script>

<template>
    <div v-if="isLoading && loadType == 'mask'" class="load-section">
        <span class="loader"></span>
    </div>
    <div v-else-if="isLoading && !loadType">
        <span class="loader"></span>
    </div>
</template>

<style>
.load-section {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 43vh;
    /* top: 50%; */
    text-align: center;
}

.loader {
    width: 3rem;
    height: 3rem;
    border: 3px solid #333;
    border-radius: 50%;
    display: inline-block;
    position: relative;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}
.loader::after {
    content: "";
    box-sizing: border-box;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    border: 3px solid transparent;
    border-bottom-color: #ff3d00;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
