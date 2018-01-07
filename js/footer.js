
	if(sessionStorage.getItem("session_contrast")){
		$("body").addClass("CONTRAST");
		$("link").prepend("<link class='link_css_contrast' rel='stylesheet' href='../css/contrast.css'>");
		sessionStorage.setItem("session_contrast",1);
		$(".btn_contrast").text("Disabilita contrasto");
		//alert("set");
	}else{
		$("body").removeClass("CONTRAST");
		$(".link_css_contrast").remove();
		sessionStorage.clear();
		$(".btn_contrast").text("Abilita contrasto");
		//alert("not set");
	}
	
	function enable_contrast(){
		
		if(!sessionStorage.getItem("session_contrast")){
			$("body").addClass("CONTRAST");
			$("link").prepend("<link class='link_css_contrast' rel='stylesheet' href='../css/contrast.css'>");
			sessionStorage.setItem("session_contrast",1);
			$(".btn_contrast").text("Disabilita contrasto");
			//alert("set");
		}else{
			$("body").removeClass("CONTRAST");
			$(".link_css_contrast").remove();
			sessionStorage.clear();
			$(".btn_contrast").text("Abilita contrasto");
			//alert("not set");
		}

	}