<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Drawable</title>
        <?php require_once 'template/commonJS.php'; ?>
        <script>

            var dimen;
            var dim;

            var width;
            var height;
            var aspect;

            var ppiwidth;
            var ppiheight;

            var ppi_w;
            var ppi_h;
            var ppi;

            //Dropzone.autoDiscover = false;
            Dropzone.autoDiscover = false;
            // or disable for specific dropzone:
            // Dropzone.options.myDropzone = false;
            var sizes = "";
            $(function () {
                //$( "#progress" ).attr( "value", 50 );
                $("#progress").hide();
                $("#ImageDetail").hide();
                //$("#myDropzone").hide();
                // Now that the DOM is fully loaded, create the dropzone, and setup the
                // event listeners

                $("#dropzone").dropzone({
                    init: function () {
                        this.on("dragenter", function (event) {
                            $("#dropzone").css("opacity", "0.4");
                        }),
                                this.on("dragleave", function (event) {
                                    $("#dropzone").css("opacity", "1");
                                }),
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

                                    if (response.indexOf("success") == -1)
                                    {
                                        alert(response);
                                        file.previewElement.classList.add("dz-error");
                                        $(".dz").hide();
                                    } else
                                    {
                                        $("#dropzone").hide();
                                        $("#instruction").hide();
                                        dimen = response.split("-");
                                        dim = dimen[1].split("x");

                                        width = dim[0];
                                        height = dim[1];
                                        aspect = width / height;

                                        ppiwidth = aspect * 10;
                                        ppiheight = 10;

                                        ppi_w = width / ppiwidth;
                                        ppi_h = height / ppiheight;
                                        ppi = (ppi_h + ppi_w) / 2;

                                        $("#width").val(width);
                                        $("#height").val(height);
                                        $("#ppiwidth").val(ppiwidth.toFixed(2));
                                        $("#ppiheight").val(10);
                                        $("#ppi").val(ppi.toFixed(2));
                                        $("#ImageDetail").show();
                                    }
                                })
                    },
                    maxFiles: 1,

                    dictInvalidFileType: "Invalid file type, Please Upload Zip File",
                    previewTemplate: document.querySelector('#preview-template').innerHTML,
                    url: "./upload.php?type=Image&key=<?php echo $key; ?>",
                    paramName: "file", // The name that will be used to transfer the file
                    acceptedFiles: ".jpg,.png",
                    accept: function (file, done) {
                        done();
                    }
                });

                function download()
                {
                    $("#progress").hide();
                    $("#complete").show();
                    $(this).target = "_blank";
                    window.location = 'download.php?type=Image&key=<?php echo $key; ?>';

                    return false;

                }

                function setppi()
                {
                    ppi_w = width / ppiwidth;
                    ppi_h = height / ppiheight;
                    ppi = (ppi_h + ppi_w) / 2;
                    $("#ppi").val(ppi.toFixed(2));

                }
                function setdimen()
                {
                    width = ppiwidth * ppi;
                    height = ppiheight * ppi;

                    $("#width").val(width.toFixed(0));
                    $("#height").val(height.toFixed(0));

                }

                $("#width").change(function () {
                    if ($("#width").val() == "" || $("#width").val() == 0)
                        $("#width").val(width);
                    else
                    {
                        width = $("#width").val();
                        if ($("#ratio").is(':checked'))
                        {
                            height = width / aspect;
                            $("#height").val(height.toFixed(0));
                            setppi();
                        } else
                        {
                            aspect = width / height;
                            ppiwidth = ppiheight * aspect;
                            $("#ppiwidth").val(ppiwidth.toFixed(2));
                        }
                    }
                });

                $("#height").change(function () {
                    if ($("#height").val() == "" || $("#height").val() == 0)
                        $("#height").val(height);
                    else
                    {
                        height = $("#height").val();
                        if ($("#ratio").is(':checked'))
                        {
                            width = height * aspect;
                            $("#width").val(width.toFixed(0));
                            setppi();
                        } else
                        {
                            aspect = width / height;
                            ppiheight = ppiwidth / aspect;
                            $("#ppiheight").val(ppiheight.toFixed(2));
                        }
                    }
                });

                $("#ppiwidth").change(function () {
                    if ($("#ppiwidth").val() == "" || $("#ppiwidth").val() == 0)
                        $("#ppiwidth").val(ppiwidth);
                    else
                    {
                        ppiwidth = $("#ppiwidth").val();
                        if ($("#ratio").is(':checked'))
                        {
                            ppiheight = ppiwidth / aspect;
                            $("#ppiheight").val(ppiheight.toFixed(2));
                            setdimen();
                        } else
                        {
                            aspect = ppiwidth / ppiheight;
                            width = height * aspect;
                            $("#width").val(width.toFixed(0));
                        }
                    }
                });

                $("#ppiheight").change(function () {
                    if ($("#ppiheight").val() == "" || $("#ppiheight").val() == 0)
                        $("#ppiheight").val(ppiheight);
                    else
                    {
                        ppiheight = $("#ppiheight").val();
                        if ($("#ratio").is(':checked'))
                        {
                            ppiwidth = ppiheight * aspect;
                            $("#ppiwidth").val(ppiwidth.toFixed(2));
                            setdimen();
                        } else
                        {
                            aspect = ppiwidth / ppiheight;
                            height = width / aspect;
                            $("#height").val(height.toFixed(0));
                        }
                    }
                });


                $("#ppi").change(function () {
                    if ($("#ppi").val() == "" || $("#ppi").val() == 0)
                        $("#ppi").val(ppi);
                    else
                    {
                        ppi = $("#ppi").val();
                        setdimen();
                    }
                });


                $(".int").keyup(function () {
                    if (!($(this).val().match(/^\d+$/)))
                    {
                        $(this).val($(this).val().slice(0, -1));
                    }
                });

                $(".int").keypress(function () {
                    if (!($(this).val().match(/^\d+$/)))
                    {
                        $(this).val($(this).val().slice(0, -1));
                    }
                });

                $(".dec").keyup(function () {
                    if (!($(this).val().match(/^\d+[\.]?\d{0,2}$/)))
                    {
                        $(this).val($(this).val().slice(0, -1));
                    }
                });
                $(".dec").keyup(function () {
                    if (!($(this).val().match(/^\d+[\.]?\d{0,2}$/)))
                    {
                        $(this).val($(this).val().slice(0, -1));
                    }
                });

                $("#convert").click(function () {
                    $("#ImageDetail").hide();
                    $("#progress").show();

                    $.ajax(
                            {
                                type: 'POST',
                                url: 'imageTransform.php?key=<?php echo $key; ?>',
                                data: {"width": width, "height": height, "type": $('input[name=img_type]:checked').val()},
                                success: function (result)
                                {
                                    setTimeout(download(), 1000);

                                }
                            });
                });



            });
        </script>
    </head>

    <body background="images/6.png"  style=" background-attachment:fixed;">
        <?php require_once 'template/header.php'; ?>
        <div class="row-fluid section" id="portfolio">
            <div class="container"  style="border:2px solid black;min-height:535px"> 
                <div class="explain">
                    <h2 style="color:black;margin:1%">Image</h2>

                    <span style="float:left; margin-left:2%; margin-bottom:2%; margin-right:1%; width:44%;border-width:5px;border-style:dashed; text-align:center;min-height:225px;padding-top:225px;" id="dropzone">
                        <b>Drag and drop files here to upload.</b>
                        </br>
                        <progress value="0" max="100" style="margin:5%; width:90%; display:none" id="uploadprogress"></progress>
                    </span>
                    <span style="float:left; margin-left:2%; margin-bottom:2%; margin-right:1%; width:44%;border-width:5px;border-style:solid; text-align:left; height:426px; padding:12px; " id="instruction">
                        <h4><b>Instructions</b></h4>
                        </br>
                        <h5>
                            - Upload Image.
                            </br></br>
                            - Select resolution.

                            </br></br>
                            - And you download will be ready.
                        </h5>
                    </span>

                    <div id = "ImageDetail" style="margin:1%;display: none;display:none">
                        <span style = "margin : 2% ; width:46%; float: left;">
                            <h3>
                                PPI Bases Resize
                            </h3>
                            </br>
                            <h4>
                                <table cellpadding=6%>
                                    <tr>
                                        <td>
                                            PPI:
                                        </td>
                                        <td>
                                            <input type="text" id="ppi" class="dec"/> PPI
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Width:
                                        </td>
                                        <td>
                                            <input type="text" id="ppiwidth" class="dec"> inch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Height:
                                        </td>
                                        <td>
                                            <input type="text" id="ppiheight" class="dec" /> inch
                                        </td>
                                    </tr>
                                </table>
                            </h4>
                        </span>
                        <span style = "margin : 2% ; width:46%;float: left;">
                            <h3>
                                &nbsp;Resolution
                            </h3>
                            </br>
                            <h4>
                                <table cellpadding=6%>
                                    <tr>
                                        <td>
                                            Width:
                                        </td>
                                        <td>
                                            <input type="text" id="width" class="int"> Pixels
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Height:
                                        </td>
                                        <td>
                                            <input type="text" id="height" class="int" /> Pixels
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align = "right">
                                            <input type="checkbox" id="ratio" checked>
                                        </td>
                                        <td>
                                            :Maintain aspect Ratio
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align = "right">
                                            <input type="radio" value="jpg" name="img_type" checked="checked"/>
                                        </td>
                                        <td>
                                            :Download in jpg format
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align = "right">
                                            <input type="radio" value="png" name="img_type" />
                                        </td>
                                        <td>
                                            :Download in png format
                                        </td>
                                    </tr>
                                </table>
                            </h4>
                        </span>

                        </br>
                        <button width=100% id='convert' style="width:100%"> <b>Resize and Download</b> </button>

                    </div>
                    <div id="progress" style="display:none">
                        <span style="margin:2%; width:96%;">Processing Data (It may take few Minutes) - Download will shortly appear.</span>
                        </br>
                        <span style="margin:2%; width:96%;background:url(./images/blur_f.gif);height:16px; display: inline-block;"   ></span> 
                    </div> 
                    <div id="complete" style="display:none">
                        <span style="margin:2%; width:96%;">Processing Complete. If download doesn't start within moment <a href="./download.php?type=Image&key=<?php echo $key; ?>" target="_blank">click here</a></span>
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