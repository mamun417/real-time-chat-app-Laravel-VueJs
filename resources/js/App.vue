<template>
    <div>
        <left-bar />
        <main-layout />
    </div>
</template>

<script>
import MainLayout from "./layouts/MainLayout";
import LeftBar from "./components/common/LeftBar";
export default {
    name: "App",
    components: { LeftBar, MainLayout }
};
</script>

<style scoped></style>
