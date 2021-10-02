<template>
    <div class="container-chat clearfix">
        <!--left bar-->
        <left-bar />
        <main-layout />
    </div>
</template>

<script>
import MainLayout from "./layouts/MainLayout";
import LeftBar from "./components/common/LeftBar";
export default {
    name: "App",
    components: { LeftBar, MainLayout },

    async mounted() {
        const parser = document.createElement("a");
        parser.href = window.location.href;

        await this.$router.push({ path: "/" });
        await this.$router.push({ path: parser.pathname });

        const convId = this.$route.params["conv_id"];

        await this.$store.dispatch("user/getMessages", convId);
    }
};
</script>

<style scoped></style>
