<template>
    <div class="chat-header d-flex justify-content-between">
        <div>
            <img
                src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/195612/chat_avatar_01_green.jpg"
                alt="avatar"
            />

            <div class="chat-about">
                <div class="chat-with">
                    Chat with
                    {{ userMessages.user.name }}
                    <small>({{ userMessages.user.email }})</small>
                </div>
                <div class="chat-num-messages">
                    already {{ userMessages.messages.length }} messages
                </div>
            </div>
        </div>

        <div class="">
            <i
                class="fa fa-ellipsis-v text-secondary"
                style="padding: 0 10px"
                type="button"
            ></i>
        </div>
    </div>
</template>

<script>
export default {
    name: "ChatHeader",
    props: {
        userMessages: {}
    }
};
</script>

<style scoped></style>
