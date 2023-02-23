
<template>
    <table-render :path_param="['mpesa', 'stkpush']" title="Mpesa Stkpush" :table_fields="table_fields">
        <template #header>
            <th-render>Amount</th-render>
            <th-render>Phone</th-render>
            <th-render>Reference</th-render>
            <th-render>Description</th-render>
            <th-render>Gateway</th-render>
            <th-render>Completed</th-render>
            <th-render>Successful</th-render>
        </template>

        <template #body="{ item }">
            <td>{{ item.amount }}</td>
            <td>{{ item.phone }}</td>
            <td>{{ item.reference }}</td>
            <td>{{ item.description }}</td>
            <td>{{ item.gateway_id }}</td>
            <td>{{ item.completed }}</td>
            <td>{{ item.successful }}</td>
        </template>

    </table-render>
</template>


<script>

export default {
    data() {
        return {
            table_fields: ['amount', 'phone', 'reference', 'description', 'gateway_id', 'completed', 'successful'],
        };
    }
};
</script>
