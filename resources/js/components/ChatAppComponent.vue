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

            <div class="pl-4 text-white">
                <h5>{{ $authUser.name }}</h5>
            </div>

            <ul class="list">
                <li
                    @click="getMessages(user.id)"
                    v-for="(user, id) in users"
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
                                id < 9 ? '0' : ''
                            }${id > 8 ? 10 : id}.jpg`
                        "
                        alt="avatar"
                    />
                    <div class="about">
                        <div class="name">{{ $_.upperFirst(user.name) }}</div>
                        <div class="status">
                            <div
                                v-if="
                                    users[user.id].typing_info &&
                                        users[user.id].typing_info.message
                                "
                            >
                                Typing...
                            </div>
                            <div v-else>
                                <i
                                    class="fa fa-circle"
                                    :class="id % 2 === 0 ? 'online' : 'offline'"
                                ></i>
                                online
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <!--<div v-for="user in users">{{ user.name }}</div>-->

        <div class="chat" v-if="userMessages.user">
            <!--<pre>{{ users[userMessages.user.id] }}</pre>-->
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
                <div v-if="typingInfo.user">
                    {{ typingInfo.user.name }} is typing...
                    <div>{{ typingInfo.message }}</div>
                </div>
                <textarea
                    @keyup="typing"
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
import { mapGetters, mapMutations, mapState } from "vuex";
import MessageActionBtn from "./common/MessageActionBtn";
import ChatHeader from "./common/ChatHeader";
import { updateUserInfo } from "../store/user/mutations";

export default {
    name: "ChatAppComponent",
    components: { ChatHeader, MessageActionBtn },
    data() {
        return {
            typingTimer: {},
            typingInfo: {},
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

        Echo.private(`send-message.${this.$authUser.id}`)
            .listen("MessageSentEvent", e => {
                // check the selected user send message to me
                if (e.message.from === this.userMessages.user.id) {
                    this.getMessages(e.message.from);

                    this.typingInfo = {};
                }
            })
            .listenForWhisper("typing", typingInfo => {
                const userId = typingInfo.user_id;

                this.$store.commit("user/updateUserInfo", {
                    user_id: userId,
                    typing_info: typingInfo
                });

                clearTimeout(this.typingTimer[userId]);

                this.typingTimer[userId] = setTimeout(() => {
                    this.$store.commit("user/updateUserInfo", {
                        user_id: userId,
                        typing_info: {}
                    });
                }, 2000);
            });
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
        },

        typing() {
            if (!this.msg) return;

            Echo.private(`send-message.${this.userMessages.user.id}`).whisper(
                "typing",
                {
                    user_id: this.$authUser.id,
                    message: this.msg
                }
            );
        }
    }
};
</script>

<style scoped></style>
