<template>
    <div class="dropdown float-right">
        <i
            class="fa fa-ellipsis-v dropdown-toggle small text-secondary"
            style="padding: 0 10px"
            type="button"
            id="dropdownMenu2"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        ></i>
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
            <a
                @click="removeMessage('remove_from_me')"
                class="dropdown-item"
                href="javascript:void(0)"
            >
                Remove from me
            </a>
            <a
                v-if="message.to === receiver.id"
                @click="removeMessage('remove_from_both')"
                class="dropdown-item"
                href="javascript:void(0)"
            >
                Remove from both
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: "MessageActionBtn",
    props: {
        receiver: {},
        message: {}
    },

    methods: {
        removeMessage(type) {
            axios
                .post(`remove-message/${this.message.id}`, {
                    [type]: true
                })
                .then(res => {
                    this.$emit("removedMessage", this.message);
                })
                .catch(err => {
                    console.log(err.response);
                });
        }
    }
};
</script>

<style scoped></style>
