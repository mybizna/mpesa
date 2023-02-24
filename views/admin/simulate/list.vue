
<template>
    <table-render :path_param="['mpesa', 'simulate']" title="Mpesa Simulate" :table_fields="table_fields">
        <template #header>
            <th-render>Amount</th-render>
            <th-render>Phone</th-render>
            <th-render>Reference</th-render>
            <th-render>Gateway Id</th-render>
            <th-render>Completed</th-render>
            <th-render>Successful</th-render>
        </template>

        <template #body="{ item }">
            <td>{{ item.amount }}</td>
            <td>{{ item.phone }}</td>
            <td>{{ item.reference }}</td>
            <td>{{ item.gateway_id }}</td>
            <td class="text-center">
                <btn-status :status="item.completed"></btn-status>
            </td>
            <td class="text-center">
                <btn-status :status="item.successful"></btn-status>
            </td>
        </template>

    </table-render>
</template>


<script>

export default {
    data() {
        return {
            table_fields: ['amount', 'phone', 'reference', 'gateway_id', 'completed', 'successful'],

        };
    }
};
</script>
