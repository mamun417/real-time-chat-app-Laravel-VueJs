<template>
    <div class="chat" v-if="userMessages.user">
        <router-view />
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import { updateUserInfo } from "../store/user/mutations";
import LeftBar from "../components/common/LeftBar";
import ChatHeader from "../components/common/ChatHeader";
import Messages from "../components/common/Messages";

export default {
    name: "MainLayout",
    components: { Messages, ChatHeader, LeftBar },
    data() {
        return {
            typingTimer: {}
        };
    },

    computed: {
        ...mapGetters({
            users: "user/getUsers",
            userMessages: "user/getMessages"
        })
    },

    mounted() {
        this.getUsers();

        Echo.join("chat")
            .here(users => {
                users
                    .filter(user => user.id !== this.$authUser.id)
                    .forEach(user => {
                        this.$store.commit("user/updateUserInfo", {
                            user,
                            online_status: true
                        });
                    });
            })
            .joining(user => {
                this.$store.commit("user/updateUserInfo", {
                    user,
                    online_status: true
                });
            })
            .leaving(user => {
                this.$store.commit("user/updateUserInfo", {
                    user,
                    online_status: false
                });
            })
            .error(error => {
                console.error(error);
            });

        Echo.private(`send-message.${this.$authUser.id}`)
            .listen("MessageSentEvent", e => {
                // check the selected user send message to me
                if (
                    this.userMessages.user &&
                    e.message.from === this.userMessages.user.id
                ) {
                    this.$store.dispatch("user/getMessages", e.message.from);

                    this.$store.commit("user/updateUserInfo", {
                        user: this.userMessages.user,
                        typing_info: {}
                    });
                }
            })
            .listenForWhisper("typing", typingInfo => {
                const userId = typingInfo.user.id;

                this.$store.commit("user/updateUserInfo", {
                    user: typingInfo.user,
                    typing_info: typingInfo
                });

                clearTimeout(this.typingTimer[userId]);

                this.typingTimer[userId] = setTimeout(() => {
                    this.$store.commit("user/updateUserInfo", {
                        user: typingInfo.user,
                        typing_info: {}
                    });
                }, 2000);
            });
    },

    methods: {
        getUsers() {
            // setTimeout(() => {
            this.$store.dispatch("user/getUsers");
            // }, 3000);
        }
    }
};
</script>

<style scoped></style>
