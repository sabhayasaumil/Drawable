<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Drawable</title>
        <?php require_once 'template/commonJS.php'; ?>
        <script>
            //Dropzone.autoDiscover = false;
            Dropzone.autoDiscover = false;
            // or disable for specific dropzone:
            // Dropzone.options.myDropzone = false;
            $(function () {
                //$( "#progress" ).attr( "value", 50 );
                $("#progress").hide();
                // Now that the DOM is fully loaded, create the dropzone, and setup the
                // event listeners
                $("#dropzone").dropzone({
                    init: function () {
                        this.on("dragenter", function (event) {
                            $("#dropzone").css("opacity", "0.4");
                        }),
                                this.on("dragleave", function (event) {
                                    $("#dropzone").css("opacity", "1");
                                })
                                ,
                                this.on("dragend", function (event) {
                                    $("#dropzone").css("opacity", "1");
                                })
                                ,
                                this.on("sending", function (file, xhr, formData) {
                                    $("#uploadprogress").show();
                                }),
                                this.on("uploadprogress", function (file, progress, bytesSent) {
                                    $("#uploadprogress").attr("value", progress);
                                }),
                                this.on("success", function (file, response) {

                                    if (response != "success")
                                    {
                                        alert(response);
                                        file.previewElement.classList.add("dz-error");
                                        $(".dz").hide();
                                    } else
                                    {
                                        $("#dropzone").hide();
                                        $("#instruction").hide();
                                        $("#progress").show();
                                        $.ajax(
                                                {
                                                    url: 'androidTransform.php?key=<?php echo $key; ?>',
                                                    success: function (result)
                                                    {
                                                        setTimeout(download(), 1000);

                                                    }
                                                });
                                    }
                                })
                    },
                    maxFiles: 1,

                    dictInvalidFileType: "Invalid file type, Please Upload Zip File",
                    previewTemplate: document.querySelector('#preview-template').innerHTML,
                    url: "upload.php?type=Android&key=<?php echo $key; ?>",
                    paramName: "file", // The name that will be used to transfer the file
                    acceptedFiles: ".zip",
                    accept: function (file, done) {
                        done();
                    }
                });

                function download()
                {
                    $("#progress").hide();
                    $("#complete").show();
                    $(this).target = "_blank";
                    window.location = 'download.php?type=Android&key=<?php echo $key; ?>';

                    return false;
                }
            });
        </script>
    </head>

    <body background="images/6.png"  style=" background-attachment:fixed;">
        <?php require_once 'template/header.php'; ?>
        <div class="row-fluid section" id="portfolio">
            <div class="container"  style="border:2px solid black;min-height:535px"> 
                <div class="explain">
                    <h2 style="color:black;margin:1%">Android</h2>

                    <span style="float:left; margin-left:2%; margin-bottom:2%; margin-right:1%; width:44%;border-width:5px;border-style:dashed; text-align:center;min-height:225px;padding-top:225px;" id="dropzone">
                        <b>Drag and drop files here to upload.</b>
                        </br>
                        <progress value="0" max="100" style="margin:5%; width:90%; display:none" id="uploadprogress"></progress>
                    </span>
                    <span style="float:left; margin-left:2%; margin-bottom:2%; margin-right:1%; width:44%;border-width:5px;border-style:solid; text-align:left; height:426px; padding:12px" id="instruction">
                        <h4><b>Instructions</b></h4>
                        </br>
                        <h5>
                            - Zip Archive Folder containing all images inside it directly.
                            </br></br>
                            - Upload Zip.

                            </br></br>
                            - All the images inside the zip will be converted to all the sizes for android development- sizes will be XXXHDPI, XXHDPI, XHDPI, HDPI, MDPI and LDPI.
                            </br></br>
                            - If folder containing all images is named as drawable-xxxhdpi or drawable-xxhdpi and so on, image type will be considered based on the folder name and images will be upscaled and downscaled based on the folder name else images would be considered as XXXHDPI.
                            </br></br>
                            - Once the zip file is uploaded, wait for the server to process and your download will be ready.
                        </h5>
                    </span>

                    </span>
                    <div id="progress" style="display:none">
                        <span style="margin:2%; width:96%;">Processing Data (It may take few Minutes) - Download will shortly appear.</span>
                        </br>
                        <span style="margin:2%; width:96%;background:url(./images/blur_f.gif);height:16px; display: inline-block;"   ></span> 
                    </div> 
                    <div id="complete" style="display:none">
                        <span style="margin:2%; width:96%;">Processing Complete. If download doesn't start within moment <a href="download.php?type=Android&key=<?php echo $key; ?>" target="_blank">click here</a></span>
                    </div>
                </div>
            </div>
        </div>


        <div class="dz-preview dz-file-preview" id="preview-template" >
            <div class="dz-details">
                <div class="dz-filename"><span data-dz-name></span></div>
                <div class="dz-size" data-dz-size></div>
            </div>
            <div class="dz-success-mark"></div>
            <div class="dz-error-mark"></div>
            <div class="dz-error-message"><span data-dz-errormessage></span></div>
        </div>

        <?php require_once 'template/footer.php'; ?>
    </body>
</html>