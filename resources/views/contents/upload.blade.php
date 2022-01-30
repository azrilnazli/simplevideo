<link href="/css/uploadfile.css" rel="stylesheet">
<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.uploadfile.js"></script>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Upload</h1>
</div>

<div id="fileuploader">Upload</div>

<script>
    $(document).ready(function()
    {
        $("#fileuploader").uploadFile({
        url:"/store_video",
        fileName:"video",
        acceptFiles:"video/*",
        sequential:true,
        sequentialCount:1
        });
    });
</script>
  