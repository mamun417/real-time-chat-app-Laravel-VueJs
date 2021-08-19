<template>
    <div class="container-chat clearfix">
        <div class="people-list" id="people-list">
            <div class="search">
                <input
                    type="text"
                    placeholder="search..."
                    class="people-list-search"
                />
                <!--<i class="fa fa-search text-white"></i>-->
            </div>
            <ul class="list">
                <li
                    @click="getMessages(user.id)"
                    v-for="(user, index) in users"
                    :key="user.id"
                    class="clearfix"
                    :class="
                        userMessages.user && userMessages.user.id === user.id
                            ? 'active-chat'
                            : ''
                    "
                >
                    <img
                        :src="
                            `https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_${
                                index < 9 ? '0' : ''
                            }${index > 9 ? 10 : index + 1}.jpg`
                        "
                        alt="avatar"
                    />
                    <div class="about">
                        <div class="name">{{ $_.upperFirst(user.name) }}</div>
                        <div class="status">
                            <i
                                class="fa fa-circle"
                                :class="index % 2 === 0 ? 'online' : 'offline'"
                            ></i>
                            online
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="chat" v-if="userMessages.user">
            <chat-header :user-messages="userMessages" />

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
                                @removedMessage="
                                    getMessages(userMessages.user.id)
                                "
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
                                @removedMessage="
                                    getMessages(userMessages.user.id)
                                "
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
            <!-- end chat-history -->

            <div class="chat-message clearfix">
                <textarea
                    @keydown.enter.prevent="sendMessage"
                    v-model="msg"
                    name="message-to-send"
                    id="message-to-send"
                    placeholder="Type your message"
                    rows="1"
                ></textarea>

                <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
                <i class="fa fa-file-image-o"></i>

                <button>Send</button>
            </div>
            <!-- end chat-message -->
        </div>
        <!-- end chat -->
    </div>
    <!-- end container -->
</template>

<script>
import { mapGetters } from "vuex";
import MessageActionBtn from "./common/MessageActionBtn";
import ChatHeader from "./common/ChatHeader";

export default {
    name: "ChatAppComponent",
    components: { ChatHeader, MessageActionBtn },
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
        this.getUsers();

        Echo.private(`send-message.${this.$authUser.id}`).listen(
            "MessageSentEvent",
            e => {
                this.getMessages(e.message.from);
            }
        );
    },

    methods: {
        getUsers() {
            this.$store.dispatch("user/getUsers");
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
        }
    }
};
</script>

<style scoped></style>
