<template>
    <div class="container-chat clearfix">
        <left-bar />

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
import { updateUserInfo } from "../store/user/mutations";
import LeftBar from "./common/LeftBar";

export default {
    name: "ChatAppComponent",
    components: { LeftBar, ChatHeader, MessageActionBtn },
    data() {
        return {
            typingTimer: {},
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
        Echo.join("chat")
            .here(users => {
                console.log({ here: users });

                this.$store.commit("user/updateUserInfo", {
                    user_id: users[0].id,
                    online_status: true
                });

                // Object.keys(users).forEach(key => {
                //     const user = users[key];
                //
                //     this.$store.commit("user/updateUserInfo", {
                //         user_id: user.id,
                //         online_status: true
                //     });
                // });
            })
            .joining(user => {
                console.log({ join: user });

                this.$store.commit("user/updateUserInfo", {
                    user_id: user.id,
                    online_status: true
                });
            })
            .leaving(user => {
                console.log({ leave: user.name });

                this.$store.commit("user/updateUserInfo", {
                    user_id: user.id,
                    online_status: false
                });
            })
            .error(error => {
                console.error(error);
            });

        Echo.private(`send-message.${this.$authUser.id}`)
            .listen("MessageSentEvent", e => {
                // check the selected user send message to me
                if (e.message.from === this.userMessages.user.id) {
                    this.getMessages(e.message.from);

                    this.$store.commit("user/updateUserInfo", {
                        user_id: this.userMessages.user.id,
                        typing_info: {}
                    });
                }
            })
            .listenForWhisper("typing", typingInfo => {
                const userId = typingInfo.user.id;

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

        isTypingToMe(userId) {
            const findUser = this.users[userId];

            return findUser.typing_info && findUser.typing_info.message;
        },

        getTypingToMeData(userId) {
            return this.users[userId].typing_info;
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
        }
    }
};
</script>

<style scoped></style>
