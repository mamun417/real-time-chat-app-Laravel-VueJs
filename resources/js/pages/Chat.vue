<template>
    <div>
        <chat-header :user-messages="userMessages" />
        <messages :key="$route.params.conv_id" />
    </div>
</template>

<script>
import Messages from "../components/common/Messages";
import ChatHeader from "../components/common/ChatHeader";
import { mapGetters } from "vuex";
export default {
    name: "Chat",
    components: { ChatHeader, Messages },

    computed: {
        ...mapGetters({
            userMessages: "user/getMessages"
        })
    }
};
</script>

<style scoped></style>
