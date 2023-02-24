
<template>
    <table-render :path_param="['mpesa', 'payment']" title="Mpesa Payment" :table_fields="table_fields">
        <template #header>
            <th-render>First Name</th-render>
            <th-render>Middle Name</th-render>
            <th-render>Last Name</th-render>
            <th-render>Trans Type</th-render>
            <th-render>Trans Id</th-render>
            <th-render>Bill Ref Number</th-render>
            <th-render>Invoice Number</th-render>
            <th-render>Published</th-render>
        </template>
        
        <template #body="{ item }">
            <td>{{ item.first_name }}</td>
            <td>{{ item.middle_name }}</td>
            <td>{{ item.last_name }}</td>
            <td>{{ item.trans_type }}</td>
            <td>{{ item.trans_id }}</td>
            <td>{{ item.bill_ref_number }}</td>
            <td>{{ item.invoice_number }}</td>
            <td class="text-center">
                <btn-status :status="item.published"></btn-status>
            </td>
        </template>

</table-render>
</template>


<script>

export default {
    data() {
        return {
            table_fields: [
                'first_name','middle_name','last_name','trans_type','trans_id','bill_ref_number',
            'invoice_number','published'
            ],
          
        };
    }
};
</script>
