<template>
    <table-edit :path_param="path_param" :model="model">

        <div class="row">
            <div class="col-md-6">
                <FormKit label="Amount" id="amount" type="text" validation="required" />
                <FormKit label="Phone" id="phone" type="text" validation="required" />
                <FormKit label="Reference" id="reference" type="text" validation="required" />
                <FormKit label="Description" id="description" type="text" validation="required" />
            </div>
            <div class="col-md-6">
                <FormKit label="Gateway" id="gateway_id" type="text" validation="required" />
                <FormKit label="Completed" id="completed" type="text" validation="required" />
                <FormKit label="Published" id="published" type="switch" validation="required" />
            </div>
        </div>
    </table-edit>
</template>

<script>
export default {
    components: {
        TableEdit: window.$func.fetchComponent("components/common/TableEdit.vue")
    },
    data () {
        return {
            id: null,
            path_param: ["mpesa", "simulate"],
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
