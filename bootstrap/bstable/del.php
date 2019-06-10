<!DOCTYPE html>
<html>
<head>
    <title>Enable/disable delete button on click checkbox</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bootstrap-table/src/bootstrap-table.css">
    <link rel="stylesheet" href="assets/examples.css">
    <script src="assets/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/bootstrap-table/src/bootstrap-table.js"></script>
    <script src="ga.js"></script>


    <script src="assets/bootstrap-table/src/extensions/editable/bootstrap-table-editable.js"></script>
    <script src="//rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/js/bootstrap-editable.js"></script>
</head>
<body>
    <div class="container">
        <h1>Enable/disable delete button on click checkbox</h1>
        <button id="remove" class="btn btn-danger" disabled>Delete</button>
        <table id="table"
               data-toggle="table"
               data-url="data1.json"
               data-toolbar="#remove"
               data-pagination="true"
               data-show-export="true">
            <thead>
            <tr>
                <th data-field="state" data-checkbox="true">ID</th>
                <th data-field="id">ID</th>
                <th data-field="name">Item Name</th>
                <th data-field="price" data-editable="true">Item Price</th>
            </tr>
            </thead>
        </table>
    </div>
<script>
    var $table = $('#table'),
        $remove = $('#remove');
    $(function () {
        $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
            $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
        });
        $remove.click(function () {
            var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
                return row.id
            });
            $table.bootstrapTable('remove', {
                field: 'id',
                values: ids
            });
            $remove.prop('disabled', true);
        });
    });
</script>
</body>
</html>