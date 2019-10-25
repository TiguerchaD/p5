<?php
use function OpenFram\escape_to_html as h;
use function OpenFram\escape_to_json as j;

use function OpenFram\u;

?>
<div class="col-12">
    <a href="/admin/user-insert.html" class="btn btn-primary">Ajouter <i class="material-icons">add_circle</i></a>
    <p class="bg-light rounded float-right p-2 colored-shadow">Utilisateurs : <?php h($usersNumber) ?></p>

    <div id="posts-table" class="card"></div>

</div>

<script>
    var tabledata = <?php  j($dataTable) ?>
    //create Tabulator on DOM element with id "example-table"
    var table = new Tabulator("#posts-table", {
        width: "100%",
        autoResize: true,
        data: tabledata, //assign data to table
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 6,
        paginationSizeSelector: [3, 6, 8, 10],
        movableColumns: true,
        columns: [ //Define Table Columns
            {title: "Id", field: "id", width: 70},
            {title: "Nom", field: "firstName", minWidth: 120},
            {title: "Prénom", field: "lastName", width: 120},
            {title: "Pseudo", field: "userName", width: 120},
            {title: "Email", field: "email", width: 220},
            {title: "Role", field: "role", width: 120},
            {
                field: "viewLink",
                width: 40,
                headerSort: false,
                frozen: true,
                cssClass: "bg-light",
                formatter: function (cell, formatterParams, onRendered) { //plain text value
                    return "<a href='" + cell.getValue() + "'><i class=\"material-icons\">\n" + "pageview\n" + "</i></a>";
                }
            },

            {
                field: "editLink",
                width: 40,
                headerSort: false,
                frozen: true,
                cssClass: "bg-light",
                formatter: function (cell, formatterParams, onRendered) { //plain text value
                    return "<a href='" + cell.getValue() + "'><i class=\"material-icons\">\n" + "edit\n" + "</i></a>";
                }

            },
            {
                field: "deleteLink",
                width: 40,
                headerSort: false,
                frozen: true,
                cssClass: "bg-light",
                formatter: function (cell, formatterParams, onRendered) { //plain text value
                    return "<a href='" + cell.getValue() + "'><i class=\"material-icons\">\n" + "delete\n" + "</i></a>";
                }

            }

        ]
    });

</script>



