var formObject = {
	allowed : ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
	fileContainerId : 'fileList',
	init : function() {
		"use strict";
		this.initUpload();
		this.removeUpload($('.removeItem'));
		this.submitClick($('.submit'));
		this.submitReturn($('input'));
		this.submitForm($('#formContact'));
	},
	templateItem : function(thisExtension, thisOldName, thisNewName, thisSize) {
		"use strict";
		if (thisExtension !== '' && thisOldName !== '' && thisNewName !== '') {
			var thisItem = '<div class="uploadItem" ';
			thisItem += 'data-new-name="' + thisNewName + '" ';
			thisItem += 'data-old-name="' + thisOldName + '">';
			thisItem += '<span class="removeItem">Remove</span>';
			thisItem += '<div class="uploadIcon ';
			thisItem += thisExtension;
			thisItem += '"></div>';
			thisItem += '<span>' + thisOldName + ' (' + thisSize + ')</span>';
			thisItem += '</div>';
			return thisItem;
		}
	},
	removeUpload : function(obj) {
		"use strict";
		obj.live('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var thisButton = $(this);
			var thisParent = thisButton.closest('.uploadItem');
			var thisName = thisParent.attr('data-new-name');
			$.post('/mod/remove.php', { name : thisName }, function(data) {
				thisParent.fadeOut(200, function() {
					$(this).remove();
				});
			}, 'json');
		});
	},
	initUpload : function() {
		"use strict";
		$('#fileUpload').uploadify({
			'swf' : '/js/uploadify/uploadify.swf',
			'uploader' : '/mod/upload.php',
			'fileObjName' : 'ssdUploadFile',
			'buttonText' : 'Select file(s)',
			'fileTypeExts' : '*.' + formObject.allowed.join('; *.') + ';',
			'width' : 100,
			'height' : 28,
			'removeTimeout' : 0,
			'removeCompleted' : true,
			'onUploadSuccess' : function(file, data, response) {
				if (data) {
					data = $.parseJSON(data);
					if (!data.error) {
						var thisDelay = setTimeout(function() {
							var thisItem = formObject.templateItem(
								data.ext, 
								data.nameOriginal,
								data.name,
								data.sizeReadable
							);
							$('#' + formObject.fileContainerId).append(thisItem);
						}, 500);
					}
				}
			}
		});
	},
	submitClick : function(obj) {
		"use strict";
		obj.live('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var thisTarget = $(this).attr('data-target');
			$('#' + thisTarget).submit();
		});
	},
	submitReturn : function(obj) {
		"use strict";
		obj.live('keypress', function(e) {
			var thisCode = e.keyCode ? e.keyCode : e.which;
			if (thisCode == 13) {
				e.preventDefault();
				e.stopPropagation();
				$(this).closest('form').submit();
			}
		});
	},
	emailValidation : function(email) {
		"use strict";
		var emailPattern = /^[a-zA-Z0-9._\-]+@[a-zA-Z0-9]+([.\-]?[a-zA-Z0-9]+)?([\.]{1}[a-zA-Z]{2,4}){1,4}$/;
		return emailPattern.test(email);
	},
	isValid : function(thisFields) {
		"use strict";
		if (thisFields.length > 0) {
			
			var thisErrors = [];
			
			$.each(thisFields, function(thisIndex, thisElement) {
				
				var thisValue = thisElement.value;
				var thisName = thisElement.name;
				var thisTitle = thisElement.title;
				var thisType = thisElement.type;
				
				if ($(this).hasClass('required')) {
				
					switch(thisType) {
						
						case 'text':
						case 'textarea':
						case 'select-one':
						if (thisValue === '') {
							$('.' + thisName).append('<div class="warning">' + thisTitle + '</div>');
							thisErrors.push(thisName);
						}
						break;
						
						case 'email':
						if (!formObject.emailValidation(thisValue)) {
							$('.' + thisName).append('<div class="warning">' + thisTitle + '</div>');
							thisErrors.push(thisName);
						}
						break;
						
					}
				
				}
				
			});
			
			if (thisErrors.length > 0) {
				return false;
			} else {
				return true;
			}
			
		} else {
			return false;
		}
	},
	validate : function(validation) {
		"use strict";
		$.each(validation, function(k, v) {
			if ($('.' + k).length > 0) {
				$('.' + k).append('<div class="warning">' + v + '</div>');
			}
		});
	},
	reset : function(thisForm) {
		"use strict";
		thisForm[0].reset();
	},
	submitForm : function(form) {
		"use strict";
		form.live('submit', function(e) {
			
			e.preventDefault();
			e.stopPropagation();
			
			var thisForm = $(this);
			
			thisForm.find('.warning').remove();
			
			var thisFields = thisForm.find(':input');
			
			if (thisFields.length > 0 && formObject.isValid(thisFields)) {
			
				var thisArray = thisForm.serializeArray();
				
				var thisFiles = $('#' + formObject.fileContainerId).children('.uploadItem');
				
				var thisFilesArray = [];
				
				if (thisFiles.length > 0) {
					$.each(thisFiles, function() {
						var thisArrayPair = { 
							'newName' : $(this).attr('data-new-name'),
							'oldName' : $(this).attr('data-old-name') 
						};
						thisFilesArray.push(thisArrayPair);
					});
				}
				
				$.ajax({
					type : 'POST',
					dataType : 'json',
					url : '/mod/send.php',
					data : { fields : thisArray, files : thisFilesArray },
					success : function(data) {
						if (data) {
							if (!data.error) {
								thisForm.parent('#formWrapper').fadeOut(200, function() {
									formObject.reset(thisForm);
									$(this).replaceWith($(data.message).hide().fadeIn(200, function() {
										
										var thisObj = $(this);
										var thisDelay = setTimeout(function() {
											thisObj.fadeOut(200, function() {
												location.reload();
											});
										}, 5000);
											
									}));
								});
							} else if (data.validation) {
								formObject.validate(data.validation);
							}
						}
					}
				});
			
			}
			
		});		
	}
};