<html>
    <head>
        <title>Testing JPNN</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="table">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Title</th>
                                    <th>Youtube ID</th>
                                    <th>Description</th>
                                    <th>thumbnail</th>
                                    <th>publishedAt</th>
                                    <th>Tags</th>
                                </tr>
                            </thead>
                            <tbody id="list_all_youtube">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
</body>
</html>

<script>
    const key       = "AIzaSyAvB4iQM3cRx3zBiUBFxVq1bdKrx2LjrmQ";
    const url       = `https://www.googleapis.com/youtube/v3/search?key=${key}`;
    const base_url  = "<?php echo base_url(); ?>";
    // const base_url  = "http://localhost/latihan-ci/";
    function getAllYoutube(){
        $.ajax({
            url: url + "&channelId=UCJ7s4VfoFpFXLNJWTyPvl0g&part=snippet&maxResults=10&order=date&type=video",
            type: "GET",
            dataType: "json",
            async: true,
            success: (result) => {
                let html = "";
                result.items.forEach(item => {
                    // console.log(item)
                    html += `
                        <tr>
                            <td>
                                <button class="btn btn-primary" id="btn-download${item.id.videoId}" onclick="getYoutube('${item.id.videoId}')">Get Datas</button>
                            </td>
                            <td>${item.snippet.channelTitle}</td>
                            <td>${item.id.videoId}</td>
                            <td>${item.snippet.description}</td>
                            <td><img src="${item.snippet.thumbnails.medium.url}" alt=""></td>
                            <td>${item.snippet.publishedAt}</td>
                            <td>${item.etag}</td>
                        </tr>
                    `;
                });
                $("#list_all_youtube").html(html);

            },
            error: (err) => {
                console.log(err)
            }
        })
    }

    function getYoutube(id){
        $.ajax({
            url: url + "&part=snippet&q="+id,
            type: "GET",
            dataType: "json",
            async: true,
            success: (result) => {
                // console.log(result.items[0].id.videoId)
                let data     = result.items[0].snippet;
                let postData = {
                    title: data.title,
                    desciption: data.description,
                    thumbnail: data.thumbnails.medium.url,
                    publishedAt: data.publishedAt,
                    tags: result.items[0].etag,
                    youtubeID: result.items[0].id.videoId
                } 
                $.ajax({
                    url: base_url + "Welcome/ProcessInsertYoutube",
                    type: "POST",
                    data: postData,
                    dataType: "json",
                    success: (result) => {
                        console.log(result)
                        alert('berhasil insert');
                    },
                    error: (err) => {
                        console.log(err)
                        alert('gagal insert');
                    }
                })
            },
            error: (err) => {
                console.log(err)
            }
        })
    }

    $(function() {
        // alert(1)
        getAllYoutube();
    })
</script>