<?php 
/*
 * Template Name: Registeration
 */
get_header();
?>
<link href="https://rawgithub.com/hayageek/jquery-upload-file/master/css/uploadfile.css" rel="stylesheet">
<link href="https://rawgithub.com/hayageek/jquery-upload-file/master/css/uploadfile.custom.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="https://rawgithub.com/hayageek/jquery-upload-file/master/js/jquery.uploadfile.min.js"></script>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
<form action="" id="add-camp-form" method="POST">
     <input type="hidden" name="addCampsite_hidden" value="Y">
    <div class="col-sm-3 col-xs-12 lable_login">Country  <span style="color:#F00;">*</span></div>
    <div class="col-sm-9 col-xs-12 "><input id="country" name="country" required="required" readonly="readonly" type="text" class="form-control" value="United States" /></div>
  
  <div class="clear20"></div>
 <div class="col-sm-3 col-xs-12 lable_login">Name of Campsite  <span style="color:#F00;">*</span></div>
  <div class="col-sm-9 col-xs-12 "><input id="park" required="required" name="camp" type="text" class="form-control" /></div>

   
  <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Campsite Address</div>
   <div class="col-sm-9 col-xs-12 "><input name="address" class="form-control" type="text"/></div>
    <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Camp Phone <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input  name="phone" required="required" class="form-control" type="number" />
  e.g., 555-000-5555 or 5550005555
  </div>
      <div class="clear20"></div>

   <div class="col-sm-3 col-xs-12 lable_login">Type of Campsite<span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><select id="camp_type" class="form-control" required="required" name="type">
       <option value="car"> Car Camping</option>Backcountry
       <option value="hike"> Hike In</option>
        <option value="rvs"> RVs Only</option>
       </select></div>
   
   <div class="clear20"></div>
   <div class="col-sm-3 col-xs-12 lable_login">Price per Night <span style="color:#F00;">*</span></div>
   <div class="col-sm-9 col-xs-12 "><input id="price" required="required" placeholder="0" name="price" class="form-control" type="number" min="0"/></div>
        </select></div>
   
    </form>
<div class="clear20"></div>
<div class="clear20"></div>
<div id="fileuploader">Upload</div>
<div id="extrabutton" class="ajax-file-upload-green">Start Upload</div>
<div id="status"></div>
<script>
$(document).ready(function()
{
	$("#fileuploader").uploadFile({
	url:"<?php echo get_site_url()?>/login-2",
        multiple:true,
        showPreview: true,
        previewHeight: "70px",
        previewWidth: "70px",
	fileName:"myfile"
	});
});

    
//    
//    var count=0;
//	var extraObj = $("#customupload1").uploadFile({
//	url:"<?php echo get_site_url()?>/login-2",
//	fileName:"myfile",
//        multiple: true,
//        showPreview:true,
//        previewHeight: "100px",
//        previewWidth: "100px",
//        showDone: true,
//        showProgress: true,
//	showFileCounter:false,
//        autoSubmit:false,
//        allowDuplicates: false,
//        createThumbnail: true,
//        dynamicFormData: function()
//        {
//            //var data ="XYZ=1&ABCD=2";
//            var data = $('form').serializeArray();
//            return data;        
//        },
//       //formData: $('form').serializeArray(),
//        extraHTML:function()
//        {
//                var html = "<b>File Tags:</b><input type='text' name='tags' value='' />";
//                        return html;    		
//        },
//	customProgressBar: function(obj,s)
//        {
//                this.statusbar = $("<div class='custom-statusbar'></div>");
//    this.preview = $("<img class='custom-preview' />").width(s.previewWidth).height(s.previewHeight).appendTo(this.statusbar).hide();
//    this.filename = $("<div class='custom-filename'></div>").appendTo(this.statusbar).hide();
//    this.progressDiv = $("<div class='custom-progress'>").appendTo(this.statusbar).hide();
//    this.progressbar = $("<div class='custom-bar'></div>").appendTo(this.progressDiv);
//    this.abort = $("<div>" + s.abortStr + "</div>").appendTo(this.statusbar).hide();
//    this.cancel = $("<div>" + s.cancelStr + "</div>").appendTo(this.statusbar).hide();
//    this.done = $("<div>" + s.doneStr + "</div>").appendTo(this.statusbar).hide();
//    this.download = $("<div>" + s.downloadStr + "</div>").appendTo(this.statusbar).hide();
//    this.del = $("<div>" + s.deletelStr + "</div>").appendTo(this.statusbar).hide();
//
//    this.abort.addClass("custom-red");
//    this.done.addClass("custom-green");
//                this.download.addClass("custom-green");            
//    this.cancel.addClass("custom-red");
//    this.del.addClass("custom-red");
//    if(count++ %2 ==0)
//            this.statusbar.addClass("even");
//    else
//                this.statusbar.addClass("odd"); 
//                return this;
//
//        }
//	}); 
//    $("#extrabutton").click(function()
//    {
//        extraObj.startUpload();
//    });
    
    
//    var extraObj = $("#mulitplefileuploader").uploadFile({
//    url:"<?php echo get_site_url()?>/login-2",
//    fileName:"myfile",
//    acceptFiles:"image/*",
//    showPreview:true,
//    previewHeight: "100px",
//    previewWidth: "100px",
//    showDone: true,
//    showProgress: true,
//    extraHTML:function()
//    {
//            var html = "<div><b>File Tags:</b><input type='text' name='tags' value='' /> <br/>";
//                    html += "</div>";
//                    return html;    		
//    },
//    autoSubmit:false
//    });
//    $("#extrabutton").click(function()
//    {
//
//            extraObj.startUpload();
//    });
</script>
<!--
<style>
#dragandrophandler
{
border:2px dotted #0B85A1;
width:400px;
color:#92AAB0;
text-align:left;vertical-align:middle;
padding:10px 10px 10 10px;
margin-bottom:10px;
font-size:200%;
}s
.progressBar {
    width: 200px;
    height: 22px;
    border: 1px solid #ddd;
    border-radius: 5px; 
    overflow: hidden;
    display:inline-block;
    margin:0px 10px 5px 5px;
    vertical-align:top;
}
 
.progressBar div {
    height: 100%;
    color: #fff;
    text-align: right;
    line-height: 22px; /* same as #progressBar height if we want text middle aligned */
    width: 0;
    background-color: #0ba1b5; border-radius: 3px; 
}
.statusbar
{
    border-top:1px solid #A9CCD1;
    min-height:25px;
    width:700px;
    padding:10px 10px 0px 10px;
    vertical-align:top;
}
.statusbar:nth-child(odd){
    background:#EBEFF0;
}
.filename
{
display:inline-block;
vertical-align:top;
width:250px;
}
.filesize
{
display:inline-block;
vertical-align:top;
color:#30693D;
width:100px;
margin-left:10px;
margin-right:5px;
}
.abort{
    background-color:#A8352F;
    -moz-border-radius:4px;
    -webkit-border-radius:4px;
    border-radius:4px;display:inline-block;
    color:#fff;
    font-family:arial;font-size:13px;font-weight:normal;
    padding:4px 15px;
    cursor:pointer;
    vertical-align:top
    }
    </style>
<div id="dragandrophandler">Drag & Drop Files Here</div>
<br><br>
<div id="status1"></div>
<script>
   function sendFileToServer(formData,status)
{
    var uploadURL ="<?php echo get_site_url()?>/login-2"; //Upload URL
    var extraData ={}; //Extra Data.
    var jqXHR=$.ajax({
            xhr: function() {
            var xhrobj = $.ajaxSettings.xhr();
            if (xhrobj.upload) {
                    xhrobj.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        //Set progress
                        status.setProgress(percent);
                    }, false);
                }
            return xhrobj;
        },
    url: uploadURL,
    type: "POST",
    contentType:false,
    processData: false,
        cache: false,
        data: formData,
        success: function(data){
            status.setProgress(100);
 
            $("#status1").append("File upload Done<br>");         
        }
    }); 
 
    status.setAbort(jqXHR);
}
 
var rowCount=0;
function createStatusbar(obj)
{
     rowCount++;
     var row="odd";
     if(rowCount %2 ==0) row ="even";
     this.statusbar = $("<div class='statusbar "+row+"'></div>");
     this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
     this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
     this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
     this.abort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
     obj.after(this.statusbar);
 
    this.setFileNameSize = function(name,size)
    {
        var sizeStr="";
        var sizeKB = size/1024;
        if(parseInt(sizeKB) > 1024)
        {
            var sizeMB = sizeKB/1024;
            sizeStr = sizeMB.toFixed(2)+" MB";
        }
        else
        {
            sizeStr = sizeKB.toFixed(2)+" KB";
        }
 
        this.filename.html(name);
        this.size.html(sizeStr);
    }
    this.setProgress = function(progress)
    {       
        var progressBarWidth =progress*this.progressBar.width()/ 100;  
        this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
        if(parseInt(progress) >= 100)
        {
            this.abort.hide();
        }
    }
    this.setAbort = function(jqxhr)
    {
        var sb = this.statusbar;
        this.abort.click(function()
        {
            jqxhr.abort();
            sb.hide();
        });
    }
}
function handleFileUpload(files,obj)
{
   for (var i = 0; i < files.length; i++) 
   {
        var fd = new FormData();
        fd.append('file', files[i]);
 
        var status = new createStatusbar(obj); //Using this we can set progress.
        status.setFileNameSize(files[i].name,files[i].size);
        sendFileToServer(fd,status);
 
   }
}
$(document).ready(function()
{
var obj = $("#dragandrophandler");
obj.on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', '2px solid #0B85A1');
});
obj.on('dragover', function (e) 
{
     e.stopPropagation();
     e.preventDefault();
});
obj.on('drop', function (e) 
{
 
     $(this).css('border', '2px dotted #0B85A1');
     e.preventDefault();
     var files = e.originalEvent.dataTransfer.files;
 
     //We need to send dropped files to Server
     handleFileUpload(files,obj);
});
$(document).on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
});
$(document).on('dragover', function (e) 
{
  e.stopPropagation();
  e.preventDefault();
  obj.css('border', '2px dotted #0B85A1');
});
$(document).on('drop', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
});
 
});
    </script>-->


<!--<style>
    #selectedFiles img {
        max-width: 200px;
        max-height: 200px;
        float: left;
        margin-bottom:10px;
    }
</style>

<form id="myForm" method="post" name="myForm">

        Files: <input type="file" id="files" name="file[]" multiple="multiple"><br/>

        <div id="selectedFiles"></div>
<input id="country" name="country" required="required" readonly="readonly" type="text" class="form-control" value="United States" />
<input id="country" name="country1" required="required" readonly="readonly" type="text" class="form-control" value="United States" />
        <input type="submit">
    </form>


<script>
    var selDiv = "";
    var storedFiles = [];
    
    $(document).ready(function() {
        $("#files").on("change", handleFileSelect);
        
        selDiv = $("#selectedFiles"); 
        $("#myForm").on("submit", handleForm);
        
        $("body").on("click", ".selFile", removeFile);
    });
        
    function handleFileSelect(e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);
        filesArr.forEach(function(f) {          

            if(!f.type.match("image.*")) {
                return;
            }
            storedFiles.push(f);
            
            var reader = new FileReader();
            reader.onload = function (e) {
                var html = "<div><img src=\"" + e.target.result + "\" data-file='"+f.name+"' class='selFile' title='Click to remove'>" + f.name + "<br clear=\"left\"/></div>";
                selDiv.append(html);
                
            }
            reader.readAsDataURL(f); 
        });
        
    }
        
    function handleForm(e) {
        e.preventDefault();
        var form = document.forms.namedItem("myForm");
        var data = new FormData(form);
        
        var len = storedFiles.length;
        
        for(var i=0; i< len; i++) {
            data.append('files[]', storedFiles[i]); 
            
        }
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'login-2', true);
        
        xhr.onload = function(e) {
            if(this.status == 200) {
                console.log(e.currentTarget.responseText);  
                alert(e.currentTarget.responseText + ' items uploaded.');
            }
        }
        xhr.send(data);
        
    }
        
    function removeFile(e) {
        var file = $(this).data("file");
        for(var i=0;i<storedFiles.length;i++) {
            if(storedFiles[i].name === file) {
                storedFiles.splice(i,1);
                break;
            }
        }
        $(this).parent().remove();
    }
    </script>-->

<?php get_footer();?>