<template>
    <edit-render :path_param="['mpesa', 'gateway']" :model="model">
        <div class="row">
            <div class="col-md-6">
                <FormKit v-model="model.id" label="Id" id="id" type="hidden" validation="required" />
                <FormKit v-model="model.title" label="Title" id="title" type="text" validation="required" />
                <FormKit v-model="model.slug" label="Slug" id="slug" type="text" validation="required" />
                <FormKit v-model="model.business_shortcode" label="Business Shortcode" id="business_shortcode" type="text"
                    validation="required" />
                <FormKit v-model="model.phone_number" label="Phone Number" id="phone_number" type="text"
                    validation="required" />
                <FormKit v-model="model.consumer_key" label="Consumer Key" id="consumer_key" type="text"
                    validation="required" />
                <FormKit v-model="model.consumer_secret" label="Consumer Secret" id="consumer_secret" type="text"
                    validation="required" />
            </div>
            <div class="col-md-6">
                <FormKit v-model="model.initiator_name" label="Initiator Name" id="initiator_name" type="text"
                    validation="required" />
                <FormKit v-model="model.initiator_password" label="Initiator Password" id="initiator_password" type="text"
                    validation="required" />

                <FormKit v-model="model.type" label="Type" id="type" type="text" validation="required" />
                <FormKit v-model="model.passkey" label="Pass Key" id="passkey" type="text" validation="required" />
                <FormKit v-model="model.default" label="Default" id="default" type="switch" validation="required" />
                <FormKit v-model="model.published" label="Published" id="published" type="switch" validation="required" />
                <FormKit v-model="model.sandbox" label="Sandbox" id="sandbox" type="switch" validation="required" />

            </div>

            <div class="col-md-6">
                <FormKit v-model="model.ledger_id" label="Ledger Id" id="ledger_id" type="recordpicker"
                    validation="required" comp_url="account/admin/ledger/list.vue" :setting="setting.ledger_id"
                    inner-class="$reset formkit-inner" wrapper-class="$reset formkit-wrapper" />
            </div>
            <div class="col-md-6">
                <FormKit v-model="model.currency_id" label="Currency Id" id="currency_id" type="recordpicker"
                    validation="required" comp_url="core/admin/currency/list.vue" :setting="setting.currency_id"
                    inner-class="$reset formkit-inner" wrapper-class="$reset formkit-wrapper" />
            </div>

            <div class="col-md-12">
                <FormKit v-model="model.description" label="Description" id="description" type="textarea"
                    validation="required" />
            </div>
        </div>

    </edit-render>
</template>

<script>
export default {

    data() {
        return {
            id: null,
            setting: {
                ledger_id: {
                    path_param: ["account", "ledger"],
                    fields: ['name'],
                    template: '[name]',
                },
                currency_id: {
                    path_param: ["core", "currency"],
                    fields: ['name'],
                    template: '[name]',
                },
            },
            model: {
                id: "",
                title: "",
                slug: "",
                consumer_key: "",
                consumer_secret: "",
                initiator_name: "",
                initiator_password: "",
                party_a: "",
                party_b: "",
                type: "",
                business_shortcode: "",
                ledger_id: "",
                currency_id: "",
                passkey: "",
                method: "",
                phone_number: "",
                description: "",
                published: "",
                default: "",
                sandbox: "",
            },


        };
    }
};
</script>
