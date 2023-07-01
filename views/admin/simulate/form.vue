<template>
    <edit-render :path_param="['mpesa', 'simulate']" :model="model">

        <div class="row">
            <div class="col-md-6">
                <FormKit v-model="model.id" label="Id" id="id" type="hidden" validation="required" />
                <FormKit v-model="model.amount" label="Amount" id="amount" type="text" validation="required" />
                <FormKit v-model="model.phone" label="Phone" id="phone" type="text" validation="required" />
                <FormKit v-model="model.reference" label="Reference" id="reference" type="text" validation="required" />

            </div>
            <div class="col-md-6">
                <FormKit v-model="model.gateway_id" label="Gateway" id="gateway_id" type="recordpicker"
                    validation="required" comp_url="mpesa/admin/gateway/list.vue" :setting="setting.gateway_id"
                    inner-class="$reset formkit-inner" wrapper-class="$reset formkit-wrapper" />
                <FormKit v-model="model.completed" label="Completed" id="completed" type="switch" validation="required" />
                <FormKit v-model="model.published" label="Published" id="published" type="switch" validation="required" />
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
                gateway_id: {
                    path_param: ["mpesa", "gateway"],
                    fields: ['name'],
                    template: '[name]',
                },
            },
            model: {
                id: "",
                amount: "",
                phone: "",
                reference: "",
                description: "",
                gateway_id: "",
                completed: "",
                successful: "",
            },

        };
    }
};
</script>
