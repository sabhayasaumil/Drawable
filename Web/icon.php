<?php
$key = time();
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$key = $key . substr(str_shuffle($chars), 0, 6);
?>
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
            var sizes = "";
            $(function () {
                //$( "#progress" ).attr( "value", 50 );
                $("#progress").hide();
                $("#IconSize").hide();
                // Now that the DOM is fully loaded, create the dropzone, and setup the
                // event listeners

                $("#dropzone").dropzone({
                    init: function () {
                        this.on("dragenter", function(event) {
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

                                    if (response != "success")
                                    {
                                        alert(response);
                                        file.previewElement.classList.add("dz-error");
                                        $(".dz").hide();
                                    } else
                                    {
                                        $("#dropzone").hide();
                                        $("#instruction").hide();
                                        $("#IconSize").show();
                                    }
                                })
                    },
                    maxFiles: 1,

                    dictInvalidFileType: "Invalid file type, Please Upload Zip File",
                    previewTemplate: document.querySelector('#preview-template').innerHTML,
                    url: "upload.php?type=Icon&key=<?php echo $key; ?>",
                    paramName: "file", // The name that will be used to transfer the file
                    acceptedFiles: ".png",
                    accept: function (file, done) {
                        done();
                    }
                });

                $("#iOS").click(function () {
                    $(".iOS").prop('checked', true);
                });

                $("#iPhone").click(function () {
                    $(".iOS").prop('checked', false);
                    $(".iPhone").prop('checked', true);
                });

                $("#iPad").click(function () {
                    $(".iOS").prop('checked', false);
                    $(".iPad").prop('checked', true);
                });

                $("#iOSnone").click(function () {
                    $(".iOS").prop('checked', false);
                });

                $("#iwatch").click(function () {
                    $(".iwatch").prop('checked', true);
                });

                $("#iwatchnone").click(function () {
                    $(".iwatch").prop('checked', false);
                });

                $("#android").click(function () {
                    $(".android").prop('checked', true);
                });

                $("#androidnone").click(function () {
                    $(".android").prop('checked', false);
                });

                $("#all").click(function () {
                    $('.iOS').each(function () {
                        sizes = sizes + "#iOS-" + $(this).val();
                    });
                    $('.iwatch').each(function () {
                        sizes = sizes + "#iWatch-" + $(this).val();
                    });
                    $('.android').each(function () {
                        sizes = sizes + "#Android-" + $(this).val();
                    });
                    Transform();

                });

                $("#selected").click(function () {
                    $('.iOS').each(function () {
                        if ($(this).is(':checked'))
                            sizes = sizes + "#iOS-" + $(this).val();
                    });
                    $('.iwatch').each(function () {
                        if ($(this).is(':checked'))
                            sizes = sizes + "#iWatch-" + $(this).val();
                    });
                    $('.android').each(function () {
                        if ($(this).is(':checked'))
                            sizes = sizes + "#Android-" + $(this).val();
                    });
                    if (sizes == "")
                        alert("No size Selected");
                    else
                        Transform();
                });
                
                function Transform()
                {
                    $("#IconSize").hide();
                    $("#progress").show();

                    $.ajax(
                            {
                                type: 'POST',
                                url: 'iconTransform.php?key=<?php echo $key; ?>',
                                data: {"sizes": sizes},
                                success: function (result)
                                {
                                    setTimeout(download(), 1000);

                                }
                            });
                }
                function download()
                {

                    $("#progress").hide();
                    $("#complete").show();
                    $(this).target = "_blank";
                    window.location = 'download.php?type=Icon&key=<?php echo $key; ?>';

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
                    <h2 style="color:black;margin:1%">Icon</h2>

                    <span style="float:left; margin-left:2%; margin-bottom:2%; margin-right:1%; width:44%;border-width:5px;border-style:dashed; text-align:center;min-height:225px;padding-top:225px;" id="dropzone">
                        <b>Drag and drop files here to upload.</b>
                        </br>
                        <progress value="0" max="100" style="margin:5%; width:90%; display:none" id="uploadprogress"></progress>
                    </span>
                    <span style="float:left; margin-left:2%; margin-bottom:2%; margin-right:1%; width:44%;border-width:5px;border-style:solid; text-align:left; height:426px; padding:12px" id="instruction">
                        <h4><b>Instructions</b></h4>
                        </br>
                        <h5>
                            - Upload your Icon. 1024 x 1024 resolution is recommended for upload.
                            </br></br>
                            - Select Sizes of Icon you want.
                            </br></br>
                            - And your download will be ready.
								</br></br>
								</br></br>
                            - NOTE: Icon that are not standard(square) in resolution will strectched when processed.
                        </h5>
                    </span>



                    <div id = "IconSize" style="margin:1%;display: none;">
                        <span style = "margin : 2% ; width:46%; float: left;">
                            <h3>
                                iOS - iPhone/iPad
                            </h3>
                            </br>
                            <h4>
                                <span id = "iOS">
                                    <a> all </a>
                                </span>
                                |
                                <span id = "iPhone">
                                    <a> iPhone </a>
                                </span>
                                |
                                <span id = "iPad">
                                    <a> iPad </a>
                                </span>
                                |
                                <span id = "iOSnone">
                                    <a> reset </a>
                                </span>

                                <br />
                                <br />
                                <table cellpadding=8%>
                                    <tr style = "margin:2%"><td>
                                            <input type = "checkbox" value="29" class ="iOS iPhone" /> 29x29 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="40" class ="iOS iPhone" /> 40x40 Pixels
                                        </td>
                                    </tr >
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="50" class ="iOS iPad" /> 50x50 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="57" class ="iOS iPhone" /> 57x57 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="58" class ="iOS iPhone" /> 58x58 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="72" class ="iOS iPad" /> 72x72 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="76" class ="iOS iPad" /> 76x76 Pixels
                                        </td>
                                    </tr style = "margin:2%">
                                    <tr>
                                        <td>
                                            <input type = "checkbox" value="80" class ="iOS iPhone" /> 80x80 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="100" class ="iOS iPad" /> 100x100 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="114" class ="iOS iPhone" /> 114x114 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="120" class ="iOS iPhone" /> 120x120 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="144" class ="iOS iPad" /> 144x144 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="152" class ="iOS iPad" /> 152x152 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="180" class ="iOS iPhone" /> 180x180 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="512" class ="iOS iPhone" /> 512x512 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="1024" class ="iOS iPhone" /> 1024x1024 Pixels
                                        </td>
                                    </tr>
                                </table>
                            </h4>
                        </span>
                        <span style = "margin : 2% ; width:46%;float: left;">
                            <h3>
                                iOS - Watch 
                            </h3>
                            </br>
                            <h4>
                                <span id = "iwatch">
                                    <a> all </a>
                                </span>
                                |
                                <span id = "iwatchnone">
                                    <a> reset </a>
                                </span>

                                <br />
                                <br />
                                <table cellpadding=6%>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="48" class ="iwatch" /> 48x48 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="55" class ="iwatch" /> 55x55 Pixels
                                        </td>
                                    </tr >
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="80" class ="iwatch" /> 80x80 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="88" class ="iwatch" /> 88x88 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="172" class ="iwatch" /> 172x172 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="196" class ="iwatch" /> 196x196 Pixels
                                        </td>
                                    </tr>
                                </table>
                            </h4>
                            </br>
                            <h3>
                                Android
                            </h3>
                            </br>
                            <h4>
                                <span id = "android">
                                    <a> all </a>
                                </span>
                                |
                                <span id = "androidnone">
                                    <a> reset </a>
                                </span>

                                <br />
                                <br />
                                <table cellpadding=6%>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="36" class ="android" /> 36x36 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="48" class ="android" /> 48x48 Pixels
                                        </td>
                                    </tr >
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="72" class ="android" /> 72x72 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="96" class ="android" /> 96x96 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="144" class ="android" /> 144x144 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="192" class ="android" /> 192x192 Pixels
                                        </td>
                                    </tr>
                                    <tr style = "margin:2%">
                                        <td>
                                            <input type = "checkbox" value="512" class ="android" /> 512x512 Pixels
                                        </td>
                                    </tr>
                                </table>
                            </h4>

                        </span>
                        <div width="100%" style = "margin-left: auto; margin-right: auto;">
                            <button width=30% id='all'> Download All </button>
                            <button width=30% id='selected'> Download Selected </button>
                        </div>
                    </div>
                    <div id="progress" style="display:none">
                        <span style="margin:2%; width:96%;">Processing Data (It may take few Minutes) - Download will shortly appear.</span>
                        </br>
                        <span style="margin:2%; width:96%;background:url(./images/blur_f.gif);height:16px; display: inline-block;"   ></span> 
                    </div> 
                    <div id="complete" style="display:none">
                        <span style="margin:2%; width:96%;">Processing Complete. If download doesn't start within moment <a href="download.php?type=Icon&key=<?php echo $key; ?>" target="_blank">click here</a></span>
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