function winwidth() {
  if (window.innerWidth)
    return window.innerWidth;
  if (document.clientElement && document.documentElement.clientWidth)
    return document.documentElement.clientWidth;
  if (document.body && document.body.clientWidth)
    return document.body.clientWidth;
  return 0;
}

function winheight() {
  if (window.innerHeight)
    return window.innerHeight;
  if (document.documentElement && document.documentElement.clientHeight)
    return document.documentElement.clientHeight;
  if (document.body && document.body.clientHeight)
    return document.body.clientHeight;
  if (document.documentElement && document.documentElement.offsetHeight)
    return document.documentElement.offsetHeight;
  return 0;
}

var task = new Array(10);
for ( i = 0; i < task.length; i++)
  task[i] = null;

var block = false;
var tops = new Array();
var taskstatus = new Array();
var imgs = new Array();
var imgsloaded = false;

function pngfix() {
  var arVersion = navigator.appVersion.split("MSIE");
  if (arVersion.length == 1) {
    window.clearInterval(pngfixinterval);
    return;
  }
  var version = parseFloat(arVersion[1]);
  if (version < 5.5) {
    window.clearInterval(pngfixinterval);
    return;
  }
  if (version >= 7) {
    window.clearInterval(pngfixinterval);
    return;
  }
  if (!document.body)
    return;
  if (!document.body.filters) {
    window.clearInterval(pngfixinterval);
    return;
  }
  var needed = 0;
  for (var i = 0; i < document.images.length; i++) {
    var img = document.images[i];
    var imgName = img.src.toUpperCase();
    if (imgName.substring(imgName.length - 3, imgName.length) == "PNG") {
      needed++;
      if ((img.width == 0) && (img.height == 0))
        continue;
      var imgID = (img.id) ? "id='" + img.id + "' " : "";
      var imgClass = (img.className) ? "class='" + img.className + "' " : "";
      var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
      var imgStyle = "display:inline-block;" + img.style.cssText;
      if (img.align == "left")
        imgStyle = "float:left;" + imgStyle;
      if (img.align == "right")
        imgStyle = "float:right;" + imgStyle;
      if (img.parentElement.href)
        imgStyle = "cursor:hand;" + imgStyle;
      var strNewHTML = "<span " + imgID + imgClass + imgTitle + " style=\"padding:0px;margin:0px;" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";" + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader" + "(src=\'" + img.src + "\');\"><" + "/span>";
      img.outerHTML = strNewHTML;
      i = i - 1;
      needed--;
    }
  }
  pngfixsteps++;
  if ((pngfixsteps > 50) && (needed == 0))
    window.clearInterval(pngfixinterval);
  return;
}

var pngfixsteps = 0;
var pngfixinterval = window.setInterval("pngfix()", 75);

function wait4image(imgname) {
  imgs.push(imgname);
}

function taskpush(what) {
  var oldblock = block;
  block = true;
  //alert(task[2]);
  var inserted = false;
  var i = 0;
  /*
   var notasks=0;
   //document.getElementById("log").innerHTML="";
   for (i=0;i<task.length;i++) {
   if ((task[i]!=null)){
   document.getElementById("log").innerHTML+=task[i]["id"]+"="+task[i]["ist"]+"   ,";
   notasks++;
   }
   }
   document.getElementById("log").innerHTML+=notasks+"<br />";
   */
  for ( i = 0; i < task.length; i++) {
    if ((task[i] != null) && (task[i]["id"] == what["id"])) {
      what["ist"] = task[i]["ist"];
      task[i] = what;
      inserted = true;
      break;
    }
  }
  if (inserted == false) {
    //	document.getElementById("log").innerHTML+="NEW!<br />";

    for ( i = 0; i < task.length; i++) {
      if (task[i] == null) {
        task[i] = what;
        inserted = true;
        break;
        //alert(i);
      }
    }
  }
  block = oldblock;
}

function getTask(which) {
  for ( i = 0; i < task.length; i++) {
    if ((task[i] != null) && (task[i]["id"] == which)) {
      return task[i];
    }
  }
  return null;
}

function taskmanager() {
  if (block == true)
    return;
  block = true;
  var i = 0;
  if (imgsloaded == false) {
    imgsloaded = true;
    for ( i = 0; i < imgs.length; i++) {
      if (document.getElementById(imgs[i]))
        imgsloaded = document.getElementById(imgs[i]).complete;
    }
    block = false;
    return;
  }
  /*
   var notasks=0;
   document.getElementById("log1").innerHTML="";
   for (i=0;i<task.length;i++) {
   if ((task[i]!=null)){
   document.getElementById("log1").innerHTML+=task[i]["id"]+"="+task[i]["ist"]+"   ,";
   notasks++;
   }
   }
   document.getElementById("log1").innerHTML+=notasks+"<br />";
   */
  for ( i = 0; i < task.length; i++) {
    if (task[i] != null) {
      var doit = true;
      if (task[i]["wait"]) {
        if (task[i]["wait"] > 0) {
          task[i]["wait"]--;
          doit = false;
        }
      }
      if (doit) {
        var ret = task[i]["function"](task[i]);
        if ((ret == null) && (task[i]["after"]))
          task[i]["after"](task[i]);
        task[i] = ret;
      }
    }
  }
  block = false;
}

var aktiv = window.setInterval("taskmanager()", 100);
/*
 Timer Handler
 */
function resizediv(what) {
  if (!what["elem"])
    return what;
  //	if ((winwidth()!=what["winwidth"])||(winheight()!=what["winheight"])||(what["elem"].scrollHeight!=what["scrollheight"])) {

  what["winwidth"] = winwidth();
  what["winheight"] = winheight();
  if (what["scrollheight"] == 0)
    what["scrollheight"] = what["elem"].scrollHeight;
  if (what["subwidth"] != -1) {
    var w = (winwidth() - what["subwidth"]);
    if (what["subelemwidth"]) {
      for (var i = 0; i < what["subelemwidth"].length; i++)
        w -= what["subelemwidth"][i].scrollWidth;
    }
    //alert(w);
    if (what["elem"].scrollWidth > w)
      what["elem"].style.width = w + "px";
  }
  if (what["subheight"] != -1) {
    var h = winheight() - what["subheight"];
    if (what["scrollheight"] < h)
      what["elem"].style.height = h + "px";
    else
      what["elem"].style.height = what["scrollheight"] + "px";
  }
  //	}
  return what;
}

function vscroll(what) {
  var val = parseInt(what["endval"] * (what["ist"]) + what["startval"] * (1 - what["ist"]));
  var clips = "rect(0px 1000px " + val + "px 0px)";
  if (what["elem"][0]) {
    for (var i = 0; i < what["elem"].length; i++) {
      what["elem"][i].style.clip = clips;
    }
  } else
    what["elem"].style.clip = clips;
  if (what["elem2"])
    what["elem2"].style.clip = clips;
  if ((what["dir"] == -1) && (what["ist"] <= 0))
    return null;
  if ((what["dir"] == 1) && (what["ist"] >= 1)) {
    clips = "rect(0px 1000px 1000px 0px)";
    if (what["elem"][0]) {
      for (var i = 0; i < what["elem"].length; i++) {
        what["elem"][i].style.clip = clips;
      }
    } else
      what["elem"].style.clip = clips;
    return null;
  }
  what["ist"] += what["dir"] * (1 / what["steps"]);
  if ((what["dir"] == -1) && (what["ist"] < 0))
    what["ist"] = 0;
  if ((what["dir"] == 1) && (what["ist"] >= 1)) {
    clips = "rect(0px 1000px 1000px 0px)";
    if (what["elem"][0]) {
      for (var i = 0; i < what["elem"].length; i++) {
        what["elem"][i].style.clip = clips;
      }
    } else
      what["elem"].style.clip = clips;
    return null;
  }
  return what;
}

function hscroll(what) {
  var val = parseInt(what["endval"] * (what["ist"]) + what["startval"] * (1 - what["ist"]));
  var clips = "rect(0px  " + val + "px 1000px 0px)";
  if (what["elem"][0]) {
    for (var i = 0; i < what["elem"].length; i++) {
      what["elem"][i].style.clip = clips;
    }
  } else
    what["elem"].style.clip = clips;
  if (what["elem2"])
    what["elem2"].style.clip = clips;
  if ((what["dir"] == -1) && (what["ist"] <= 0))
    return null;
  if ((what["dir"] == 1) && (what["ist"] >= 1))
    return null;
  what["ist"] += what["dir"] * (1 / what["steps"]);
  if ((what["dir"] == -1) && (what["ist"] < 0))
    what["ist"] = 0;
  if ((what["dir"] == 1) && (what["ist"] >= 1))
    what["ist"] = 1;
  return what;
}

function vopen(what) {
  it = what["elem"];
  what["val"] = parseInt(what["endval"] * (what["ist"]) + what["startval"] * (1 - what["ist"]));
  clips = "rect(0px 1000px " + what["val"] + "px 0px)";
  it.style.clip = clips;
  it.style.height = what["val"] + "px";
  it.parentNode.style.top = (what["top"] - what["val"]) + "px";
  if ((what["dir"] == -1) && (what["ist"] <= 0))
    return null;
  if ((what["dir"] == 1) && (what["ist"] >= 1))
    return null;
  what["ist"] += what["dir"] * (1 / what["steps"]);
  if ((what["dir"] == -1) && (what["ist"] < 0))
    what["ist"] = 0;
  if ((what["dir"] == 1) && (what["ist"] >= 1))
    what["ist"] = 1;
  return what;
}

function relatedlink(what) {
  it = what["elem"];
  what["val"] = parseInt(what["endval"] * (what["ist"]) + what["startval"] * (1 - what["ist"]));
  it.style.height = what["val"] + "px";
  if ((what["dir"] == -1) && (what["ist"] <= 0))
    return null;
  if ((what["dir"] == 1) && (what["ist"] >= 1))
    return null;
  what["ist"] += what["dir"] * (1 / what["steps"]);
  if ((what["dir"] == -1) && (what["ist"] < 0))
    what["ist"] = 0;
  if ((what["dir"] == 1) && (what["ist"] >= 1))
    what["ist"] = 1;
  return what;
}

function fade(what) {
  var it = what["elem"];
  var ist = parseInt(what["ist"] * 100 / what["steps"]);
  if (what["elem"][0]) {
    for (var i = 0; i < what["elem"].length; i++) {
      it[i].style.mozOpacity = ist / 100;
      it[i].style.opacity = ist / 100;
      it[i].style.filter = "alpha(opacity=" + ist + ")";
      it[i].style.khtmlOpacity = ist / 100;
      //			if (ist==0) it[i].style.display="none";
      //			if (ist!=0) it[i].style.display="block";
    }
  } else {
    it.style.mozOpacity = ist / 100;
    it.style.opacity = ist / 100;
    it.style.filter = "alpha(opacity=" + ist + ")";
    it.style.khtmlOpacity = ist / 100;
    //		if (ist==0) it.style.display="none";
    //		if (ist!=0) it.style.display="block";
  }
  if ((what["dir"] == -1) && (what["ist"] <= 0))
    return null;
  if ((what["dir"] == 1) && (what["ist"] >= what["steps"]))
    return null;
  what["ist"] += what["dir"];
  if ((what["dir"] == -1) && (what["ist"] < 0))
    what["ist"] = 0;
  if ((what["dir"] == 1) && (what["ist"] >= what["steps"]))
    what["ist"] = what["steps"];
  return what;
}

var actflyer = 0;
var flyerchange = false;
function flyer(what) {
  if (!flyerchange) {
    what["ist"] = 0;
    return what;
  }
  what["ist"] += (1 / what["steps"]);
  if (what["ist"] >= 1) {
    changeflyer(-1)
    what["ist"] = 0;
  }
  return what;
}

var slideshowschange = new Array();
function slideshow(what) {
  if (!slideshowschange[what["showname"]]) {
    what["ist"] = 0;
    return what;
  }
  what["ist"] += (1 / what["steps"]);
  if (what["ist"] >= 1) {
    var nextslide = what["actslide"] + 1;
    if (nextslide >= what["countslides"])
      nextslide = 0;
    if (what["actslide"] != nextslide) {
      //			setred(actflyer);
      if (document.getElementById(what["showname"] + "pos" + what["actslide"]))
        document.getElementById(what["showname"] + "pos" + what["actslide"]).className = "slideinactive";
      var mytask = new Array();
      mytask["function"] = fade;
      mytask["id"] = what["showname"] + "slide" + what["actslide"];
      mytask["steps"] = what["fade"];
      mytask["ist"] = what["fade"];
      mytask["dir"] = -1;
      mytask["elem"] = document.getElementById(what["showname"] + "slide" + what["actslide"]);
      if (mytask["elem"]) {
        document.getElementById(what["showname"] + "slide" + what["actslide"]).style.zIndex = 14;
        taskpush(mytask);
      }
      what["actslide"] = nextslide;
      if (document.getElementById(what["showname"] + "pos" + what["actslide"]))
        document.getElementById(what["showname"] + "pos" + what["actslide"]).className = "slideactive";
      var mytask = new Array();
      mytask["function"] = fade;
      mytask["id"] = what["showname"] + "slide" + what["actslide"];
      mytask["steps"] = what["fade"];
      mytask["ist"] = 0;
      mytask["dir"] = 1;
      mytask["elem"] = document.getElementById(what["showname"] + "slide" + what["actslide"]);
      if (mytask["elem"]) {
        document.getElementById(what["showname"] + "slide" + what["actslide"]).style.zIndex = 15;
        taskpush(mytask);
      }
      //	document.getElementById("log1").innerHTML=actflyer+"<br />";
    }
    what["ist"] = 0;
  }
  return what;
}

function changeslide(which, towhat) {
  what = getTask("slideshow" + which);
  var lasti = 0;
  var nextslide = what["actslide"] + 1;
  if (nextslide > what["countslides"])
    nextslide = 0;
  if (towhat >= 0)
    nextslide = towhat;
  if (what["actslide"] != nextslide) {
    //			setred(actflyer);
    if (document.getElementById(what["showname"] + "pos" + what["actslide"]))
      document.getElementById(what["showname"] + "pos" + what["actslide"]).className = "slideinactive";
    var mytask = new Array();
    mytask["function"] = fade;
    mytask["id"] = what["showname"] + "slide" + what["actslide"];
    mytask["steps"] = what["fade"];
    mytask["ist"] = what["fade"];
    mytask["dir"] = -1;
    mytask["elem"] = document.getElementById(what["showname"] + "slide" + what["actslide"]);
    if (mytask["elem"]) {
      document.getElementById(what["showname"] + "slide" + what["actslide"]).style.zIndex = 14;
      taskpush(mytask);
    }
    what["actslide"] = nextslide;
    if (document.getElementById(what["showname"] + "pos" + what["actslide"]))
      document.getElementById(what["showname"] + "pos" + what["actslide"]).className = "slideactive";
    var mytask = new Array();
    mytask["function"] = fade;
    mytask["id"] = what["showname"] + "slide" + what["actslide"];
    mytask["steps"] = what["fade"];
    mytask["ist"] = 0;
    mytask["dir"] = 1;
    mytask["elem"] = document.getElementById(what["showname"] + "slide" + what["actslide"]);
    if (mytask["elem"]) {
      document.getElementById(what["showname"] + "slide" + what["actslide"]).style.zIndex = 15;
      taskpush(mytask);
    }
    //	document.getElementById("log1").innerHTML=actflyer+"<br />";
  }
  what["ist"] = 0;
  taskpush(what);
}

function sideslider(what) {
  if (!slideshowschange[what["showname"]]) {
    what["ist"] = 0;
    return what;
  }
  what["ist"] += (1 / what["steps"]);
  if (what["ist"] >= 1) {
    var nextslide = what["actslide"] + 1;
    if (nextslide >= what["countslides"])
      nextslide = 0;
    if (what["actslide"] != nextslide) {
      //			setred(actflyer);
      var mytask = new Array();
      /*
       mytask["function"]=hscroll;
       mytask["id"]=what["showname"]+"slide"+what["actslide"];
       mytask["steps"]=10;
       mytask["ist"]=1;
       mytask["dir"]=-1;
       mytask["elem"]=document.getElementById(what["showname"]+"slide"+what["actslide"]);
       taskpush(mytask);
       */
      for (var i = 0; i < what["countslides"]; i++)
        document.getElementById(what["showname"] + "slide" + i).style.zIndex = 13;
      document.getElementById(what["showname"] + "slide" + what["actslide"]).style.zIndex = 14;
      what["actslide"] = nextslide;
      var mytask = new Array();
      mytask["function"] = hscroll;
      mytask["startval"] = 0;
      mytask["endval"] = document.getElementById(what["showname"] + "slide" + what["actslide"]).offsetWidth;
      mytask["id"] = what["showname"] + "slide" + what["actslide"];
      mytask["steps"] = 5;
      mytask["ist"] = 0;
      mytask["dir"] = 1;
      mytask["elem"] = document.getElementById(what["showname"] + "slide" + what["actslide"]);
      document.getElementById(what["showname"] + "slide" + what["actslide"]).style.clip = "rect(0px 0px 1000px 0px)";
      document.getElementById(what["showname"] + "slide" + what["actslide"]).style.display = "block";
      document.getElementById(what["showname"] + "slide" + what["actslide"]).style.zIndex = 15;
      taskpush(mytask);
      //	document.getElementById("log1").innerHTML=actflyer+"<br />";
    }
    what["ist"] = 0;
  }
  return what;
}

/*
 Timer Handler Starter
 */
function navipush(what, how, dir, steps, e, where) {
  var mytask = new Array();
  mytask["function"] = vscroll;
  mytask["id"] = what;
  var it = document.getElementById(what);
  mytask["elem"] = it;
  mytask["startval"] = 0;
  mytask["endval"] = it.offsetHeight;
  if (how == 1) {
    mytask["steps"] = 5;
    mytask["ist"] = 0;
    mytask["dir"] = 1;
    it.style.zIndex = 15;
  }
  if (how == 2) {
    mytask["steps"] = 2;
    mytask["ist"] = 1;
    mytask["dir"] = -1;
    it.style.zIndex = 14;
  }
  taskpush(mytask);
}

function startvscroll(what, dir, steps) {
  var mytask = new Array();
  mytask["function"] = vscroll;
  mytask["id"] = what;
  mytask["steps"] = steps;
  var it = document.getElementById(what);
  mytask["elem"] = it;
  mytask["startval"] = 0;
  mytask["endval"] = it.offsetHeight;
  mytask["dir"] = dir;
  if (dir == 1) {
    mytask["ist"] = 0;
    //		it.style.zIndex=15;
  }
  if (dir == -1) {
    mytask["ist"] = 1;
    //		it.style.zIndex=14;
  }
  taskpush(mytask);
}

function newsboxpush(how, e, where) {
  var mytask = new Array();
  mytask["function"] = vopen;
  mytask["id"] = "news";
  mytask["steps"] = 8;
  var it = document.getElementById("newscontent");
  mytask["elem"] = it;
  mytask["startval"] = 0;
  mytask["endval"] = 75;
  if (how == 1) {
    mytask["ist"] = 0;
    mytask["dir"] = 1;
    mytask["top"] = 529;
  }
  if (how == 2) {
    mytask["ist"] = 1;
    mytask["dir"] = -1;
    mytask["top"] = 529;
  }
  taskpush(mytask);
}

/*
 Timer After Handler Ends
 */
function afterbgfader(what) {
  var mytask = new Array();
  mytask["function"] = fade;
  mytask["id"] = "flyercontrolfader";
  mytask["steps"] = 10;
  mytask["dir"] = 1;
  mytask["ist"] = 0;
  mytask["elem"] = new Array();
  for (var i = 0; i < 6; i++) {
    if (document.getElementById("flyercntrl" + i))
      mytask["elem"].push(document.getElementById("flyercntrl" + i));
  }
  taskpush(mytask);
  if (document.getElementById("flyer0")) {
    var mytask = new Array();
    mytask["function"] = vscroll;
    mytask["id"] = "flyer0";
    mytask["startval"] = 0;
    mytask["endval"] = document.getElementById("flyer0").scrollHeight;
    mytask["dir"] = 1;
    mytask["ist"] = 0;
    mytask["steps"] = 10;

    mytask["elem"] = new Array();
    for (var i = 0; i < 6; i++) {
      if (document.getElementById("flyer" + i))
        mytask["elem"].push(document.getElementById("flyer" + i));
    }
    //document.getElementById("flyer1"),document.getElementById("flyer2"));
    taskpush(mytask);
    var mytask = new Array();
    mytask["function"] = flyer;
    mytask["id"] = "flyer";
    mytask["dir"] = 1;
    mytask["ist"] = 0;
    mytask["steps"] = 80;
    taskpush(mytask);
    flyerchange = true;
  }
}

function openinterimsdiv(what) {
  if (document.getElementById("interimsdiv")) {
    var mytask = new Array();
    mytask["function"] = vscroll;
    mytask["id"] = "interimsdiv";
    mytask["startval"] = 0;
    mytask["elem"] = document.getElementById("interimsdiv")
    mytask["endval"] = document.getElementById("interimsdiv").scrollHeight + 2;
    mytask["dir"] = 1;
    mytask["ist"] = 0;
    mytask["steps"] = 10;
    taskpush(mytask);
  }
}

/*
 Service functions
 */

function changeflyer(towhat) {
  var lasti = 0;
  var nextflyer = 0;
  for (var i = 0; i < 6; i++) {
    if (document.getElementById("flyer" + i)) {
      lasti = i;
    }
  }
  if (actflyer == lasti)
    nextflyer = 0;
  else
    nextflyer = actflyer + 1;
  if (towhat >= 0)
    nextflyer = towhat;
  if ((nextflyer <= lasti) && (actflyer != nextflyer)) {
    //			setred(actflyer);
    var mytask = new Array();
    mytask["function"] = fade;
    mytask["id"] = "flyer" + actflyer;
    mytask["steps"] = 10;
    mytask["ist"] = 1;
    mytask["dir"] = -1;
    mytask["elem"] = document.getElementById("flyer" + actflyer);
    document.getElementById("flyer" + actflyer).style.zIndex = 14;
    taskpush(mytask);
    actflyer = nextflyer;
    var mytask = new Array();
    mytask["function"] = fade;
    mytask["id"] = "flyer" + actflyer;
    mytask["steps"] = 10;
    mytask["ist"] = 0;
    mytask["dir"] = 1;
    mytask["elem"] = document.getElementById("flyer" + actflyer);
    document.getElementById("flyer" + actflyer).style.zIndex = 15;
    taskpush(mytask);
    setred(actflyer);
    //	document.getElementById("log1").innerHTML=actflyer+"<br />";
  }
}

var actrellink = -1;
function changerelatedlink(towhat) {
  var lasti = 0;
  if (actrellink != towhat) {
    //			setred(actflyer);
    if (actrellink != -1) {
      var mytask = new Array();
      var it = document.getElementById("rellink" + actrellink);
      mytask["function"] = relatedlink;
      mytask["id"] = "rellink" + actrellink;
      mytask["steps"] = 10;
      mytask["ist"] = 1;
      mytask["startval"] = 1;
      mytask["endval"] = it.scrollHeight;
      mytask["dir"] = -1;
      mytask["elem"] = it;
      taskpush(mytask);
    }
    actrellink = towhat;
    var mytask = new Array();
    var it = document.getElementById("rellink" + actrellink);
    mytask["function"] = relatedlink;
    mytask["id"] = "rellink" + actrellink;
    mytask["steps"] = 5;
    mytask["ist"] = 0;
    mytask["startval"] = 1;
    mytask["endval"] = it.scrollHeight;
    mytask["dir"] = 1;
    mytask["elem"] = it;
    taskpush(mytask);
  }
}

function txtscroll(arr, par, how) {
  narr = par.parentNode.parentNode.childNodes;
  txtscr = 0;
  opendiv = "";
  closediv = "";
  scrcnt = 0;
  scract = 0;
  arrspans = new Array();
  for (var i = 0; i < narr.length; i++) {
    if (narr[i].className == "txtscroll") {
      txtscr++;
      scrcnt++;
      if (("" == narr[i].style.clip) || ("rect(0px, 1000px, 0px, 0px)" == narr[i].style.clip) || ("rect(0px 1000px 0px 0px)" == narr[i].style.clip)) {
        if ((how == 1) && ("" == opendiv) && ("" != closediv)) {
          opendiv = narr[i];
          scract = scrcnt;
        }
        if ((how == 2) && ("" == closediv)) {
          opendiv = narr[i];
          scract = scrcnt;
        }
      } else {
        closediv = narr[i];
      }
    }
    if ((narr[i].className) && (narr[i].className.indexOf("txtscrollarrows") != -1)) {
      arrchilds = narr[i].childNodes;
      for (var j = 0; j < arrchilds.length; j++) {
        if ((arrchilds[j].tagName) && (arrchilds[j].tagName.toLowerCase() == "div"))
          arrspans.push(arrchilds[j]);
      }
    }
  }
  if (("" != closediv) && ("" != opendiv)) {
    var mytask = new Array();
    mytask["function"] = vscroll;
    mytask["id"] = "txtscrollup";
    mytask["startval"] = 0;
    mytask["endval"] = 321;
    mytask["dir"] = -1;
    mytask["ist"] = 1;
    mytask["steps"] = 7;
    mytask["elem"] = closediv;
    taskpush(mytask);
    var mytask = new Array();
    mytask["wait"] = 5;
    mytask["function"] = vscroll;
    mytask["id"] = "txtscrolldown";
    mytask["startval"] = 0;
    mytask["endval"] = 321;
    mytask["dir"] = 1;
    mytask["ist"] = 0;
    mytask["steps"] = 7;
    mytask["elem"] = opendiv;
    taskpush(mytask);
    //		taskpush_e(closediv,2,'v');
    //		document.getElementById("scrollcounter").innerHTML=scract+" / "+scrcnt;
    //		window.setTimeout("taskpush_e(opendiv,1,'v')",1000);
  }
  if (scract > 1)
    arrspans[0].style.display = "inline";
  else
    arrspans[0].style.display = "none";
  if (scract != scrcnt)
    arrspans[1].style.display = "inline";
  else
    arrspans[1].style.display = "none";
}

