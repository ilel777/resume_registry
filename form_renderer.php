<script type="text/x-handlebars-template" id="form-template">
  <form method="post">
    <div class="form-group row">
      <label for="firstname" class="col-md-2 col-form-label">First Name</label>
      <div class="col-md-10">
        <input class="form-control" id="firstname" name="first_name" type="text" placeholder="First Name" value="{{'First Name'}}" />
      </div>
    </div>
    <div class="form-group row">
      <label for="lastname" class="col-md-2 col-form-label">Last Name</label>
      <div class="col-md-10">
        <input class="form-control" id="lastname" name="last_name" type="text" placeholder="Last Name" value="{{'Last Name'}}" />
      </div>
    </div>
    <div class="form-group row">
      <label for="email" class="col-md-2 col-form-label">Email</label>
      <div class="col-md-10">
        <input class="form-control" id="email" name="email" type="text" placeholder="Email" value="{{Email}}" />
      </div>
    </div>
    <div class="form-group row">
      <label for="headline" class="col-md-2 col-form-label">Headline</label>
      <div class="col-md-10">
        <input class="form-control" id="headline" name="headline" type="text" placeholder="Headline" value="{{Headline}}" />
      </div>
    </div>
    <div class="form-group row">
      <label for="summary" class="col-md-2 col-form-label">Summary</label>
      <div class="col-md-10">
	<textarea class="form-control" cols="30" id="summary" name="summary" rows="10">{{Summary}}</textarea>
      </div>
    </div>
    <div id="education_fields" class="form-group row container">
      <div class="form-group row">
	<label class="col-md-2 col-form-label" for="educations">Education:</label>
	<button id="addEdu" type="button" class="btn btn-info btn-sm">+</button>
      </div>
      {{#each Education}}
      {{#education this @index}}
      {{/education}}
      {{/each}}
    </div>
    <div id="position_fields" class="form-group row container">
      <div class="form-group row">
	<label class="col-md-2 col-form-label" for="positions">Position:</label>
	<button id="addPos" type="button" class="btn btn-info btn-sm">+</button>
      </div>
    {{#if Position}}
      {{#each Position}}
      {{#position this @index}}
      {{/position}}
      {{/each}}
    {{/if}}
    </div>
    <div class="form-group row">
      <button type="submit" class="btn btn-info btn-sm">Submit</button>
      <button name="cancel" type="submit" class="btn btn-info btn-sm">Cancel</button>
      {{#if profile_id}}
      <input name="profile_id" type="hidden" value="{{profile_id}}"/>
      {{/if}}
    </div>
  </form>
</script>



<script type="text/x-handlebars-template" id="education-form-template">
    <div id="education_{{index}}" class="education form-group row">
      <label for="" class="col-md-2 col-form-label">Year</label>
      <div class="col-md-10" style="display: flex;">
	  <input class="form-control" id="edu_year{{index}}" name="edu_year{{index}}" type="text" placeholder="Education year" value="{{edu.year}}" />
	  <button type="button" class="delEdu btn btn-info btn-sm">-</button>
      </div>
      <label for="" class="col-md-2 col-form-label">School</label>
      <div class="col-md-10">
        <input class="form-control school_ui-autocomplete-input" id="edu_shool{{index}}" name="edu_shool{{index}}" type="text" placeholder="School name" value="{{edu.name}}" />
      </div>
    </div>
</script>

<script type="text/x-handlebars-template" id="position-form-template">
    <div id="position_{{index}}" class="position form-group row">
      <label for="" class="col-md-2 col-form-label">Year</label>
      <div class="col-md-10" style="display: flex;">
        <input class="form-control" id="pos_year{{index}}" name="pos_year{{index}}" type="text" placeholder="Position year" value="{{pos.year}}" />
	  <button type="button" class="delPos btn btn-info btn-sm">-</button>
      </div>
      <label for="" class="col-md-2 col-form-label">Position</label>
      <div class="col-md-10">
        <textarea class="form-control" id="desc{{index}}" name="desc{{index}}" placeholder="Position description">{{pos.description}}</textarea>
      </div>
    </div>
</script>

<script type="text/javascript">
 window.console && console.log('starting form rendering');

 var pos_counter = 0;
 var edu_counter = 0;
 //education form renderer
 edu_form = function(education, index){
   window.console && console.log(education);
   var template = Handlebars.compile($('#education-form-template').html());
   return template({edu :education, index: index+1});
 }
 Handlebars.registerHelper('education', edu_form);

 //position form renderer
 pos_form = function(position, index){
   window.console && console.log(position);
   var template = Handlebars.compile($('#position-form-template').html());
   return template({pos :position, index: index+1});
 }
 Handlebars.registerHelper('position', pos_form);

 //delete position form
 const del_pos = function(event){
   event.preventDefault();
   window.console && console.log(positions);
   positions.push($(this).parents('.position').attr('id').split('_')[1]);
   $(this).parents('.position').remove();
   return false;
 };

 //add position form
 const add_pos = function(event){
   event.preventDefault();
   window.console && console.log(positions);
   if(positions.length == 0){
     alert('max postion fields reached');
     return false;
   }
   var current_pos = positions.pop();
   $('#position_fields').append(pos_form({},current_pos));
   $('.delPos').click(del_pos);
   return false;
 };
 //delete education
    const del_education = function(){
	window.console && console.log($(this).parents('.education'));
	educations.push(($(this)).parents('.education').attr('id').split('_')[1]);
	$(this).parents('.education').remove();
	return false;
    }
 //add education
    const add_education = function(event){
      event.preventDefault();
	if(educations.length == 0){
	    alert('max education fields reached');
	    return false;
	}
	var current_edu = educations.pop();
      $('#education_fields').append(edu_form({},current_edu));
      $('.delEdu').click(del_education);
      $('.school_ui-autocomplete-input').autocomplete({source:'school.php'});
      return false;
    }
 //render profile form
 var to_edit = <?= isset($_GET['profile_id']) ? $_GET['profile_id'] : 'false' ?>;
 var raw_template = $('#form-template').html();
 var template = Handlebars.compile(raw_template);
 if(to_edit){
    $.getJSON('profile.php?profile_id=<?= isset($_GET['profile_id']) ? $_GET['profile_id'] : "" ?>', function(data){
      window.console && console.log(data);
      if(data.Position) pos_counter = data.Position.length;
      if(data.Education) edu_counter = data.Education.length;
      window.console && console.log(pos_counter);
      window.console && console.log(edu_counter);
      var rendered_form = template(data);
      $('#form').html(rendered_form);
      $('.delPos').click(del_pos);
      $('#addPos').click(add_pos);
      $('#addEdu').click(add_education);
      $('.delEdu').click(del_education);
      $('.school_ui-autocomplete-input').autocomplete({source:'school.php'});
    }).fail(function(){alert('getJSON fail');});
 }else{
    var data = {};
    var rendered_form = template(data);

    $('#form').html(rendered_form);
      $('#addPos').click(add_pos);
      $('#addEdu').click(add_education);
 }
    //Position's code
    var positions = [];
    for(var i=9; i>pos_counter; i--){
	positions.push(i);
    }

      /*
      $('#position_fields').append($(document.createElement('div')).attr({'id':'position_'+current_pos,'class':'position'}).append($('<p>Year: </p>').append($('<br>')).append($(document.createElement('input')).attr({'type':'text','name':'year'+current_pos})).append($(document.createElement('input')).attr({'class':'position','type':'button','value':'-'}).click(del_form))).append($(document.createElement('textarea')).attr({'name':'desc'+current_pos,'rows':'8','cols':'80'})));
      */


    //Education's code
    var educations = [];
    for(var i=9; i>edu_counter; i--){
	educations.push(i);
    }
</script>
