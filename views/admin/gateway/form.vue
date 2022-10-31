<template>
    <table-edit :path_param="path_param" :model="model">
        <div class="row">
            <div class="col-md-6">
                <FormKit label="Title" id="title" type="text" validation="required" />
                <FormKit label="Slug" id="slug" type="text" validation="required" />
                <FormKit label="Key" id="key" type="text" validation="required" />
                <FormKit label="Secret" id="secret" type="text" validation="required" />
                <FormKit label="Ledger Id" id="ledger_id" type="text" validation="required" />
            </div>
            <div class="col-md-6">
                <FormKit label="Till Bill No" id="till_bill_no" type="text" validation="required" />
                <FormKit label="Pass Key" id="passkey" type="text" validation="required" />
                <FormKit label="Short Code" id="shortcode" type="text" validation="required" />
                <FormKit label="Default" id="default" type="switch" validation="required" />
                <FormKit label="Published" id="published" type="switch" validation="required" />
            </div>
            <div class="col-md-12">
                <FormKit label="Description" id="description" type="text" validation="required" />
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
            path_param: ["mpesasasasa", "gateway"],
            model: {
                id: "",
                title: "",
                slug: "",
                key: "",
                secret: "",
                till_bill_no: "",
                passkey: "",
                shortcode: "",
                ledger_id: "",
                passkey: "",
                description: "",
                published: "",
                default: "",
            },

        };
    }
};
</script>
