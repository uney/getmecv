var cv = {};
$(function(){
	cv = {
		type: "",
		ParentID: "",
		addMoreContent: "",
		skillItemContent: "",
		init: function(ContainerID)
		{
			this.type     = ContainerID;       // for post data to server 
			this.ParentID = "#" + ContainerID; // not '#' pass in, so add "#" here
			this.addMoreContent = "";
 		},
		edit: function(prefix)
		{
			$(this.ParentID).find(".glyphicon-edit").addClass("hidden");
			$(this.ParentID).find(".glyphicon-save").removeClass("hidden");
			
			//input data field - such as : input, datepicker, textarea
			$(this.ParentID).find(".savedata").each(function() {
				//console.log(this);
				$(this).removeAttr('readonly');
				
				if( $(this).hasClass("needDatePicker") )
					$(this).datepicker({format: 'MM yyyy' , viewMode: "months", minViewMode: "months"});
			});
			// item move handler and delete handler
			$(this.ParentID).find('.move_handler_span').each(function() {
				$(this).css("visibility", "visible");
			});
			
			$(this.ParentID).find('.add_more_div').removeClass("hidden");
			
			//sortable
			if(prefix == "") {} 
			else { 
				var ct = document.getElementById(this.type);
				Sortable.create(ct, {
					draggable: ".item",  // Specifies which items inside the element should be draggable
					handle: '.glyphicon-resize-vertical',
					animation: 250,
					onUpdate: function (evt) {
					  [].forEach.call(evt.from.getElementsByClassName(prefix+'row'), function (el,index) {
						  el.setAttribute("list_position", index);
					  });
					},
					// dragging ended
					onEnd: function (/**Event*/evt) {
						evt.oldIndex;  // element's old index within parent
						evt.newIndex;  // element's new index within parent
				}});
			}
			
			if(this.ParentID === '#profile_intro')
				$(this.ParentID).find('#hide_pro_pic').removeClass("hidden");
			
			if(this.ParentID === '#skills')
			{
				$(this.ParentID).find('.add_more_item_div').removeClass("hidden");
				
				$(this.ParentID).find('.skill_in_display').addClass("hidden");
				$(this.ParentID).find('.skill_in_edit').removeClass("hidden");

				$(this.ParentID).find( ".skills-list" ).sortable({
					connectWith: '.skills-list ul',
					update: function( event, ui ) {
						$(this).find("li").each(function(index){
							console.log($(this))
							$(this).attr("list_position", index);
						});
					}
				}).disableSelection();

			}
			
			this.changeProgressHeight();
			if(this.ParentID === '#profile_intro')
				changeProfileIntroProgressHeight();
		},
		save: function(prefix)
		{
			$(this.ParentID).find(".glyphicon-edit").removeClass("hidden");
			$(this.ParentID).find(".glyphicon-save").addClass("hidden");
			$(this.ParentID).find(".savedata").each(function() {
				$(this).attr('readonly', true).removeAttr("style");
				
				if( $(this).hasClass("needDatePicjer") )
					$(this).datepicker("destroy");
			});
			
			$(this.ParentID).find('.move_handler_span').each(function() {
				$(this).css("visibility", "hidden");
			});
			
			$(this.ParentID).find('.add_more_div').addClass("hidden");
			
			if(this.ParentID === '#profile_intro')
			{
				$(this.ParentID).find('#hide_pro_pic').addClass("hidden");
				var hide = $(this.ParentID).find("input[name='hide_pro_pic']").prop("checked");
				
				if(hide)
				{
					$(this.ParentID).find('.profile-pic').css("background", "#249991");
					$(this.ParentID).find('#profile_curr_img').addClass("hidden");
				}
				else
				{
					$(this.ParentID).find('.profile-pic').css("background", "#fafafa");
					$(this.ParentID).find('#profile_curr_img').removeClass("hidden");
				}
			}
			
			if(this.ParentID === '#skills')
			{
				$(this.ParentID).find('.add_more_item_div').addClass("hidden");
				
				$(this.ParentID).find('.skill_in_display').removeClass("hidden");
				$(this.ParentID).find('.skill_in_edit').addClass("hidden");
				
				
				$(this.ParentID).find( ".skills-list" ).each(function() {
					$(this).find("li").each(function(index){
						var i = index+1;
						var bari = "progress-bar-"+i;
						var id = $(this).attr("id");
						
						var name = $(this).find("input[name='skName']").val();
						var mark = $(this).find("input[name='skMark']").val();
						
						if(id.indexOf("new_") !=-1)
							$(this).find(".progress-bar").addClass(bari);
						
						$(this).find(".progress-bar").attr("data-percent", (mark*10)+"%").css("width", (mark*10)+"%");
						$(this).find(".sr-only").html((mark*10)+"% Complete");
						$(this).find(".progress-type").html(name);
						$(this).find(".progress-completed").html((mark*10)+"%");
					});
				});
			}
			
			this.changeProgressHeight();
			
			this.addLoading();
			this.postData(prefix);
		},
		deleted: function(obj)
		{
			var id = obj.getAttribute("parent");
			$("#"+id).remove();

		},
		postData: function(prefix)
		{
			var arr = [];
			if(this.type == 'education')
				arr = this.saveEdu(prefix);
			else if(this.type == 'profile_intro')
				arr = this.saveIntro(prefix);
			else if(this.type == 'profile')
				arr = this.saveProfile(prefix);
			else if(this.type == 'works')
				arr = this.saveWorks(prefix);
			else if(this.type == 'awards')
				arr = this.saveAwards(prefix);
			else if(this.type == 'langs')
				arr = this.saveLangs(prefix);
			else if(this.type == 'skills')
				arr = this.saveSkills(prefix);

			var postData = {};
			postData["type"] = this.type;
			postData["skey"] = userObject.skey;
			postData["data"] = arr;
			// Ajax
			$.ajax({
			  type: "POST",
			  url: '/ws/saveData.php',
			  data: postData,
			  success: function(response){
				cv.removeLoading();
				  
				toastr.success('All data saved!');
				
				if(cv.type == 'skills')
				{
					console.log(response);
					for(var key in response)
					{
						if(key == "Header")
						{
							var item = response[key];
							for(var i in item)
							{
								var header = item[i];
								$("#"+header.old).attr("data-"+ prefix +"id", header.new).attr("id", prefix + header.new);
							}
						}
						else if(key == "Content")
						{
							var item = response[key];
							for(var i in item)
							{
								var content = item[i];
								$("#"+content.old).attr("data-id", content.new).attr("id", "skCid_" + content.new);
							}
						}
					}
					
				}
				else{
					if(response.length > 0)
					{
						for(var key in response)
						{
							var item = response[key];
							$("#"+item.old).attr("data-"+ prefix +"id", item.new).attr("id", prefix + item.new);
						}
					}
				}
			  },
			  failure: function(errMsg) {
				  alert(errMsg);
			  }
			});
		},
		addMore: function(prefix)  // prefix is used for add new div and display
		{
			var arr = $( '.' + prefix + '_row' ).toArray();
			var newID = arr.length;
			newID++;
			
			if( this.addMoreContent == "" )
			{
				$.get( '/cv/add_more_' +  prefix + '.html', function(data){
					cv.addMoreContent = data;
					//console.log(cv.addMoreContent)
					$.when( $(cv.ParentID).find('.add_more_div').before(cv.addMoreContent) ).done(function( obj ) {
						//console.log($(cv.ParentID).find("#new_insert"))
						$(cv.ParentID).find("#new_insert").attr("id", 'new_'+newID).attr( 'data-'+ prefix +'_id', 'new_'+newID)
							.attr("list_position", newID);
						
						$(cv.ParentID).find('#new_'+newID).find(".delete_btn").attr("parent", 'new_'+newID);
						
						if(cv.ParentID === '#skills')
						{
							$('#new_'+newID).find("ul").attr("id", 'skl_'+newID);
							$('#new_'+newID).find(".add_more_item_div").attr("onclick", "addSkillItem('skills', '#skl_" + newID +  "')");
						}
						
					});
					
					cv.edit();
				});
			}
			else
			{
				$.when( $(this.ParentID).find('.add_more_div').before(this.addMoreContent) ).done(function( obj ) {
					$(cv.ParentID).find("#new_insert").attr("id", 'new_'+newID).attr( 'data-'+ prefix +'_id', 'new_'+newID)
						.attr("list_position", newID);
					
					$(cv.ParentID).find('#new_'+newID).find(".delete_btn").attr("parent", 'new_'+newID);
					
					if(cv.ParentID === '#skills')
					{
						$('#new_'+newID).find("ul").attr("id", 'skl_'+newID);
						$('#new_'+newID).find(".add_more_item_div").attr("onclick", "addSkillItem('skills', '#skl_" + newID +  "')");
					}
				});
			}
			
			this.edit();
		},
		addSkillItem: function(ul_id)
		{
			var newID = $(ul_id).children("li").length;

			if( cv.skillItemContent == "" )
			{
				$.get( '/cv/add_more_skillitem.html', function(data){
					cv.skillItemContent = data;
 
					$.when( $("ul" + ul_id).append(data) ).done(function( obj ) {
						$("ul" + ul_id).find("#new_insert").attr("id", 'new_child_'+newID).attr( 'data-id', 'new_child_'+newID)
							.attr("list_position", newID);
					
						$('#new_child_'+newID).find(".delete_btn").attr("parent", 'new_child_'+newID);
					});

					cv.edit();
				});
			}
			else
			{
				$.when( $("ul" + ul_id).append(cv.skillItemContent) ).done(function( obj ) {
					 $(cv.ParentID).find("#new_insert").attr("id", 'new_'+newID).attr( 'data-id', 'new_'+newID)
							.attr("list_position", newID);
					
					$('#new_'+newID).find(".delete_btn").attr("parent", 'new_'+newID);
				});
			}
			cv.edit();
		},
		changeProgressHeight: function()
		{
			$(this.ParentID).find(".item").each(function() {
				var parentHeight = $(this).height();
				$(this).find('.timeline-progress').height(parentHeight);
				$(this).find('.full-height').height(parentHeight);
			});
		},
		addLoading: function()
		{
			addloading();
		},
		removeLoading: function()
		{
			removeloading();
		},
		saveEdu: function(prefix)
		{
			var eduArray = [];
			var arr = $( '.' + prefix +'row' ).toArray();
			for(var key in arr)
			{
				var item = arr[key];
				
				var id = item.id;
				var container = $("#" + id);
				var newEdu = { 
					'eid': container.attr("data-edu_id") ,
					'uid': userObject.uid,
					'school_name': container.find("input[name='school_name']").val(),
					'graduate_year': container.find("input[name='graduate_year']").val(),
					'program_name': "",
					'intro': container.find("textarea[name='intro']").val(),
					'type': "",
					'major': container.find("input[name='major']").val(),
					'list_position': container.attr("list_position")
				};
				eduArray.push(newEdu);
			}
			return eduArray;
		},
		saveIntro: function(prefix)
		{
			var introArray = [];
			var arr = $( '.' + prefix +'row' ).toArray();
			for(var key in arr)
			{
				var item = arr[key];
				
				var id = item.id;
				var container = $("#" + id);
				var newIntro = { 
					'uid': userObject.uid,
					'hide':  container.find("input[name='hide_pro_pic']").prop("checked") ? "1" : 0,
					'major': container.find("input[name='pMajor']").val(),
					'intro': container.find("textarea[name='pIntro']").val()
				};
				introArray.push(newIntro);
			}
			return introArray;
		},
		saveProfile: function(prefix)
		{
			var profileArray = [];
			var arr = $( '.' + prefix +'row' ).toArray();
			for(var key in arr)
			{
				var item = arr[key];
				
				var id = item.id;
				var container = $("#" + id);
				var newProfile = { 
					'uid': userObject.uid,
					'full_name': container.find("input[name='full_name']").val(),
					'email': container.find("input[name='email']").val(),
					'phone': container.find("input[name='phone']").val()
				};
				profileArray.push(newProfile);
			}
			return profileArray;
		},
		saveWorks: function(prefix)
		{
			var workArray = [];
			var arr = $( '.' + prefix +'row' ).toArray();
			for(var key in arr)
			{
				var item = arr[key];
				
				var id = item.id;
				var container = $("#" + id);
				var newWork = { 
					'wid': container.attr("data-work_id") ,
					'uid': userObject.uid,
					'position': container.find("input[name='wPosition']").val(),
					'name': container.find("input[name='wMajor']").val(),
					'start': container.find("#work_year_start").val(),
					'end': container.find("#work_year_end").val(),
					'intro': container.find("textarea[name='wIntro']").val(),
					'list_position': container.attr("list_position")
				};
				workArray.push(newWork);
			}
			return workArray;
		},
		saveAwards: function(prefix)
		{
			var awardArray = [];
			var arr = $( '.' + prefix +'row' ).toArray();
			for(var key in arr)
			{
				var item = arr[key];
				
				var id = item.id;
				var container = $("#" + id);
				var newAward = { 
					'aid': container.attr("data-award_id") ,
					'uid': userObject.uid,
					'name': container.find("input[name='aMajor']").val(),
					'intro': container.find("textarea[name='aIntro']").val(),
					'list_position': container.attr("list_position")
				};
				awardArray.push(newAward);
			}
			return awardArray;
		},
		saveLangs: function(prefix)
		{
			var langArray = [];
			var arr = $( '.' + prefix +'row' ).toArray();
			for(var key in arr)
			{
				var item = arr[key];
				
				var id = item.id;
				var container = $("#" + id);
				var newLang = { 
					'lid': container.attr("data-lang_id") ,
					'uid': userObject.uid,
					'major': container.find("input[name='lMajor']").val(),
					'lv': container.find("input[name='langlv']").val(),
					'list_position': container.attr("list_position")
				};
				langArray.push(newLang);
			}
			return langArray;
		},
		saveSkills: function(prefix)
		{
			var skillArray = [];
			var arr = $( '.' + prefix +'row' ).toArray();
			
			for(var key in arr)
			{
				var item = arr[key];
				
				var id = item.id;
				var container = $("#" + id);
				
				var newSkill = {
					"Header"  : {},
					"Content" : []
				};
				newSkill["Header"]["skHid"] = container.attr("data-skill_id");
				newSkill["Header"]["skName"] = container.find("input[name='sMajor']").val();
				newSkill["Header"]["skListPos"] = container.attr("list_position");
				
				container.find("ul.skills-list li").each(function(index) {
					var newSkillitem = {};
					newSkillitem['sid'] = $(this).attr("data-id");
					newSkillitem['skill_name'] = $(this).find("input[name='skName']").val();
					newSkillitem['skill_mark'] = $(this).find("input[name='skMark']").val();
					newSkillitem['list_position'] = $(this).attr("list_position");
					
					newSkill["Content"][index] = newSkillitem;
				});
				skillArray.push(newSkill);
			}
			return skillArray;
		}
	};
	
	
});