<html>
    <head>
        <title>Testing JPNN</title>
        <link rel="stylesheet" href="https://appindocoll.com/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <a class="btn btn-sm btn-primary" href="<?php echo base_url("Welcome/export"); ?>">Export ke Excel</a><br><br>
        <div class="container-fluid">
            <form id="filter_published" action="">
                <div class="row">
                    <div class="col-5">
                        <label for="">Start Date</label>
                        <input type="date" class="form-control" name="start_date" value="<?=date('Y-m-d')?>" id="start_date" >   
                    </div>
                    <div class="col-5">
                        <label for="">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="<?=date('Y-m-d')?>" id="end_date" >   
                    </div>
                    <div class="col-2">
                        <label for="">Filter</label><br>
                        <button class="btn btn-sm btn-primary" type="button" id="filter">Filter</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-12">
                    <div class="table">
                        <table class="table table-striped" id="datatable-youtube">
                            <thead>
                                <tr>
                                    <!-- <th>Action</th> -->
                                    <th>Title</th>
                                    <th>Youtube ID</th>
                                    <th>Description</th>
                                    <th>thumbnail</th>
                                    <th>publishedAt</th>
                                    <th>Tags</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
    <script src="https://appindocoll.com/assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="https://appindocoll.com/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://appindocoll.com/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="https://appindocoll.com/assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="https://appindocoll.com/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="https://appindocoll.com/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="https://appindocoll.com/assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="https://appindocoll.com/assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
</body>
</html>

<script>
    const key       = "AIzaSyAvB4iQM3cRx3zBiUBFxVq1bdKrx2LjrmQ";
    const url       = `https://www.googleapis.com/youtube/v3/search?key=${key}`;
    const base_url  = "<?php echo base_url(); ?>";

    function dataTableYoutube(start_date, end_date){
        $("#datatable-youtube").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": base_url + "Welcome/apiDatatable",
                "type": "POST",
                "data": {
                    start_date: start_date,
                    end_date: end_date
                }
            },
            "columns": [
                // {"data": "action"},
                {"data": "title"},
                {"data": "youtubeID"},
                {"data": "desciption"},
                {"data": "thumbnail"},
                {"data": "publishedAt"},
                {"data": "tags"}
            ]
        });
    }

    function exportExcel(){
        var start_date = $("#start_date").val();
        var end_date   = $("#end_date").val();
        $.ajax({
            url: base_url + "Welcome/export",
            type: "POST",
            data: {
                start_date: start_date,
                end_date: end_date
            },
            processData: false,
            contentType: false,
            success: function(data){
                console.log(data);
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = `data.xls`;
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            },
            error: function(data){
                console.log(data);
            }
        });
    }

    $('#filter').on('click', function(e) {
        e.preventDefault();
        let start_date = $('#start_date').val();
        let end_date = $('#end_date').val();
        $("#datatable-youtube").DataTable().destroy();
        dataTableYoutube(start_date, end_date);
    });

    $(document).ready(function(e) {

        dataTableYoutube("", "");

    })
    
</script>