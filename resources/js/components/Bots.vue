<template>
    <div class="card">
        <div class="card-header">Bots</div>
        <div class="card-body">
            <table v-if="bots" class="table">
                <tbody>
                <tr v-for="(bot, index) in bots" :key=index>
                    <td>{{ bot.name }}</td>
                    <td>{{ bot.type }}</td>
                    <td>
                        <button @click="deleteBot(bot.id, index)" type="button" class="close btn-delete"
                                title="Delete bot" aria-label="Delete bot">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <form v-on:submit.prevent="addBot">
                <input type="text" class="form-control" name="name" required placeholder="Name"
                       v-model="bot.name">
                <ul class="validity">
                    <li v-for="error in errors.name" class="text-danger">
                        {{ error }}
                    </li>
                </ul>
                <input type="text" class="form-control" name="token" required placeholder="Token"
                       v-model="bot.token">
                <ul class="validity">
                    <li v-for="error in errors.token" class="text-danger">
                        {{ error }}
                    </li>
                </ul>
                <select v-model="bot.type" required class="form-control">
                    <option disabled value="">Choose messenger..</option>
                    <option value="telegram">Telegram</option>
                    <option value="viber">Viber</option>
                </select>
                <button type="submit" class="btn-primary">Add bot</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                bot: {
                    name: '',
                    token: '',
                    type: ''
                },
                bots: [],
                errors: {},
            }
        },
        methods: {
            addBot() {
                this.errors = {};
                axios.post('api/v1/bots', this.bot)
                    .then(({data}) => {
                        this.bots = [...this.bots, data];
                        this.bot.name = '';
                        this.bot.token = '';
                        this.bot.type = '';
                    })
                    .catch(error => {
                        if (!error.response.data) {
                            alert('An error occurred.');
                            return;
                        }
                        var resp = error.response.data;
                        if (resp.errors) {
                            this.errors = resp.errors;
                        } else if (resp.message) {
                            alert(resp.message);
                        }
                    })
            },
            deleteBot(id, index) {
                axios.delete('api/v1/bots/' + id).then(({data}) => {
                    this.$delete(this.bots, index);
                }).catch(function (resp) {
                    alert("An error occurred while deleting your bot.");
                });
            }
        },
        created() {
            axios.get('api/v1/bots').then(({data}) => {
                this.bots = data;
            }).catch(function (resp) {
                alert("An error occurred while fetching your bots.");
            });
        }
    }
</script>

<style scoped>
    .btn {
        float: right;
    }

    .btn-delete {
        line-height: 0.9;
    }

    .form-control {
        margin-bottom: 10px;
    }
</style>
