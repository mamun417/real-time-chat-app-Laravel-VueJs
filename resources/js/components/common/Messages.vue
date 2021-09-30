<template>
    <div>
        <div class="chat-history" v-chat-scroll>
            <ul v-if="userMessages.messages.length">
                <li
                    v-for="(message, index) in userMessages.messages"
                    :key="index"
                    class="clearfix"
                >
                    <div
                        v-if="userMessages.user.id !== message.user.id"
                        class="message-data align-right"
                    >
                        <span class="message-data-time">
                            {{ $dateFormat(message.created_at) }}
                        </span>
                        &nbsp; &nbsp;
                        <span class="message-data-name">
                            {{ message.user.name }}
                        </span>
                        <i class="fa fa-circle me"></i>

                        <message-action-btn
                            :receiver="userMessages.user"
                            :message="message"
                            @removedMessage="getMessages(userMessages.user.id)"
                        />
                    </div>

                    <div v-else class="message-data">
                        <span class="message-data-name">
                            <i class="fa fa-circle online"></i>
                            {{ message.user.name }}
                        </span>
                        <span class="message-data-time">
                            {{ $dateFormat(message.created_at) }}
                        </span>

                        <message-action-btn
                            :receiver="userMessages.user"
                            :message="message"
                            @removedMessage="getMessages(userMessages.user.id)"
                        />
                    </div>

                    <div
                        class="message"
                        :class="
                            userMessages.user.id === message.user.id
                                ? 'my-message'
                                : 'other-message float-right'
                        "
                    >
                        {{ message.message }}
                    </div>
                </li>
            </ul>

            <div v-else class="text-center">
                No chat history
            </div>
        </div>

        <div class="chat-message clearfix">
            <div v-if="isTypingToMe(userMessages.user.id)">
                {{ getTypingToMeData(userMessages.user.id).user.name }} is
                typing...
                <div>
                    {{ getTypingToMeData(userMessages.user.id).message }}
                </div>
            </div>

            <textarea
                @keyup="typing"
                @keydown.enter.prevent="sendMessage"
                v-model="msg"
                id="message-to-send"
                placeholder="Type your message"
            ></textarea>

            <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
            <i class="fa fa-file-image-o"></i>

            <button>Send</button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import MessageActionBtn from "./MessageActionBtn";

export default {
    name: "Messages",
    components: { MessageActionBtn },
    data() {
        return {
            msg: ""
        };
    },

    computed: {
        ...mapGetters({
            users: "user/getUsers",
            userMessages: "user/getMessages"
        })
    },

    mounted() {
        console.log("get messages by conv_id");
    },

    methods: {
        isTypingToMe(userId) {
            const findUser = this.users[userId];

            return findUser.typing_info && findUser.typing_info.message;
        },

        getMessages(userId) {
            this.$store.dispatch("user/getMessages", userId);
        },

        sendMessage() {
            const msg = this.msg.trim();

            if (!msg) return;

            axios
                .post("send-message", {
                    message: msg,
                    user_id: this.userMessages.user.id
                })
                .then(res => {
                    this.msg = "";

                    this.getMessages(this.userMessages.user.id);
                })
                .catch(err => {
                    console.log(err);
                });
        },

        typing() {
            if (!this.msg) return;

            Echo.private(`send-message.${this.userMessages.user.id}`).whisper(
                "typing",
                {
                    user: this.$authUser,
                    message: this.msg
                }
            );
        },

        getTypingToMeData(userId) {
            return this.users[userId].typing_info;
        }
    }
};
</script>

<style scoped></style>
