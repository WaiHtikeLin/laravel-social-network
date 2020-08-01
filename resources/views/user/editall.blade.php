
<p><span>Email&nbsp;</span>
  <a href="#" id="add_email">Add</a>
</p>
<div id="add_email_group">
  <input type="email" name="" value="" id="add_email_field">
  <p id="email_error"></p>
  <button type="button" name="button" id="add_email_button">OK</button>
</div>

<div class="" id="emails">

</div>

<p><span>Phone&nbsp;</span>
  <a href="#" id="add_phone">Add</a>
</p>

<div id="add_phone_group">
  <input type="phone" name="" value="" id="add_phone_field">
  <p id="phone_error"></p>
  <button type="button" name="button" id="add_phone_button">OK</button>
</div>

<div class="" id="ph_numbers">

</div>

<p><span>Website&nbsp;</span>
  <a href="#" id="add_website">Add</a>
</p>

<div id="add_site_group">
  <input type="text" name="" value="" id="add_site_field">
  <p id="site_error"></p>
  <button type="button" name="button" id="add_site_button">OK</button>
</div>

<div class="" id="websites">

</div>

<p><span>Education&nbsp;</span>
  <a href="{{ url('edit/profile/education') }}">Add</a>
</p>

<div class="" id="education">

</div>

<p><span>Work&nbsp;</span>
  <a href="{{ url('edit/profile/work') }}">Add</a>
</p>

<div class="" id="work">

</div>

<p><span>Address&nbsp;</span>
  <a href="{{ url('edit/profile/address') }}">Add</a>
</p>

<div class="" id="address">

</div>

<div id="for_about">

<div id="add_about_group">
  <textarea name="about_field" rows="4" cols="40" id="edit_about_field"></textarea>
  <button type="button" name="button" id="update_about_button">OK</button>
</div>
</div>

<div id="for_bio">

<div id="add_bio_group">
  <textarea name="bio_field" rows="4" cols="40" id="edit_bio_field"></textarea>
  <button type="button" name="button" id="update_bio_button">OK</button>
</div>
</div>

<script type="text/javascript">




  $(document).ready(function() {

    function createEditBlock(value,type)
    {

      let editbutton=$('<button>').html('Edit').click(function(event) {
        $(this).parent().hide();
        $("#add_"+type+"_group").show();
        $("#edit_"+type+"_field").html(value);
        $("#edit_"+type+"_field").focus();

      });

      let removebutton=$('<button>').html('Remove').click(function(event) {
        let parent=$(this).parent();
        let field=type.charAt(0).toUpperCase()+type.slice(1);
        $.ajax({
          url: '/delete/'+type,
          type: 'delete',
          success : function()
          {
            parent.replaceWith(createAddBlock(field));
          }
        })
        .done(function() {
          console.log("success");
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });

      });

      return $('<div>').append($('<p>').html(value)).append(editbutton).append(removebutton);
    }

    function createAddBlock(type)
    {
      let addButton=$('<button>').html('Add').click(function(event) {
        $(this).parent().hide();
        $(this).parent().siblings().show();


      });

      return $('<p>').append($('<span>').html(type)).append(addButton);
    }

    @isset($info->about)
    $("#for_about").prepend(createEditBlock('{{ $info->about }}','about'));

    @else

    $("#for_about").prepend(createAddBlock('About'));

    @endisset

    @isset($info->bio)
    $("#for_bio").prepend(createEditBlock('{{ $info->bio }}','bio'));

    @else

    $("#for_bio").prepend(createAddBlock('Bio'));

    @endisset

    $("#add_email_group").hide();
    $("#add_phone_group").hide();
    $("#add_site_group").hide();
    $("#add_about_group").hide();
    $("#add_bio_group").hide();


    $("#update_about_button").click(function(event) {
      let value=$(this).prev().val();
      let parent=$(this).parent();
      $.post('/add/about', {about: value}, function(data, textStatus, xhr) {
        parent.hide();
        $("#for_about").prepend(createEditBlock(value,'about'));

      });

    });

    $("#update_bio_button").click(function(event) {
      let value=$(this).prev().val();
      let parent=$(this).parent();
      $.post('/add/bio', {bio: value}, function(data, textStatus, xhr) {
        parent.hide();
        $("#for_bio").prepend(createEditBlock(value,'bio'));

      });

    });


    $("#add_email").click(function() {
      $("#add_email_group").show();
    });

    $("#add_phone").click(function() {
      $("#add_phone_group").show();
    });

    $("#add_website").click(function() {
      $("#add_site_group").show();
    });

    function showemails()
    {
    @isset($info->emails)
    let field;
    @foreach($info->emails as $id=>$email)
      field={
        name : '{{$email}}',
        id : {{$id}},
        type : 'email'

      };
      buidEditBlock(field);
    @endforeach
    @endisset
    }

    function showphones()
    {
    @isset($info->ph_numbers)
    let field;
    @foreach($info->ph_numbers as $id=>$phone)
      field={
        name : '{{$phone}}',
        id : {{$id}},
        type : 'ph_number'

      };
      buidEditBlock(field);
    @endforeach
    @endisset
    }

    function showsites()
    {
    @isset($info->websites)
    let field;

    @foreach($info->websites as $id=>$site)
      field={
        name : '{{$site}}',
        id : {{$id}},
        type : 'website'

      };
      buidEditBlock(field);

    @endforeach
    @endisset
    }

    function showedu()
    {
    @isset($info->education)
    let field;
    @foreach($info->education as $id=>$edu)
      field={
        name : '{{ "${edu['name']} ${edu['from']} ${edu['to']}" }}',
        id : {{$id}},
        type : 'redirect',
        field : 'education'

      };
      buidEditBlock(field);
    @endforeach
    @endisset
    }

    function showwork()
    {
    @isset($info->work)
    let field;
    @foreach($info->work as $id=>$work)
      field={
        name : '{{ "${work['name']} ${work['title']} ${work['from']} ${work['to']}" }}',
        id : {{$id}},
        type : 'redirect',
        field : 'work'

      };
      buidEditBlock(field);
    @endforeach
    @endisset
    }

    function showaddress()
    {
    @isset($info->address)
    let field;
    @foreach($info->address as $id=>$address)
      field={
        name : '{{ "${address['country']} ${address['city']} ${address['detail']} ${address['from']} ${address['to']}" }}',
        id : {{$id}},
        type : 'redirect',
        field : 'address'

      };
      buidEditBlock(field);
    @endforeach
    @endisset
    }

    showemails();
    showphones();
    showsites();
    showedu();
    showwork();
    showaddress();




    $("#add_email_button").click(function(event) {
      /* Act on the event */
      let value=$("#add_email_field").val();
      let parent=$(this).parent();
      $.ajax({
        url: '/add/email',
        type: 'POST',
        data: {field:value}
      })
      .done(function(type) {
        parent.hide();
        $("#email_error").html("");
        buidEditBlock(type);
      })
      .fail(function(jqXHR,text,msg) {
        let e=JSON.parse(jqXHR.responseText);
        $("#email_error").html(e.errors.field);
      });
    });

    $("#add_phone_button").click(function(event) {
      /* Act on the event */
      let value=$("#add_phone_field").val();
      let parent=$(this).parent();
      $.ajax({
        url: '/add/phone',
        type: 'POST',
        data: {field:value}
      })
      .done(function(type) {
        parent.hide();
        $("#phone_error").html("");
        buidEditBlock(type);
      })
      .fail(function(jqXHR,text,msg) {
        let e=JSON.parse(jqXHR.responseText);
        $("#phone_error").html(e.errors.field);
      });
    });

    $("#add_site_button").click(function(event) {
      /* Act on the event */
      let value=$("#add_site_field").val();
      let parent=$(this).parent();
      $.ajax({
        url: '/add/site',
        type: 'POST',
        data: {field:value}
      })
      .done(function(type) {
        parent.hide();
        $("#site_error").html("");
        buidEditBlock(type);
      })
      .fail(function(jqXHR,text,msg) {
        let e=JSON.parse(jqXHR.responseText);
        $("#site_error").html(e.errors.field);
      });

    });


    function buidEditBlock(type)
    {
      let newtype=$('<span>').data('id',type.id).html(type.name);
      let editlink=$('<a>',{
        href: '#'
      }).html('Edit').click(function()
        {
          if(type.type=='redirect')
            location.href='{{ url('edit') }}/'+type.field+'/'+type.id;
          $(this).parent().hide();
          let t=$(this).parent().next();
          t.show();
          t.children('input').val(type.name);
          t.children('input').focus();

        });

        let wrap=(type.type=='redirect')? type.field : type.type+'s';

        let deletelink=$('<a>',{
          href: '#'
        }).html('Delete').click(function()
          { let field=$(this).parent().parent();

            $.ajax(
              {
              url : '{{ url('delete') }}/'+wrap+'/'+type.id,
              method : 'DELETE',
              success : function(data)
                        { field.html('');
                          $.each(data, function(index, d) {
                            buidEditBlock(d);
                          });
              }          }
            );
          });
        let efield="#"+type.type+"_"+type.id+"_error";

        let inputType;

        switch (type.type) {
          case 'email': inputType='email';

            break;
        case 'ph_number': inputType='phone';

                break;



          default: inputType='text';

        }
        let editfield=$("<input>", {
          type:inputType}
        ).val(type.name);

        let updatelink=$('<a>',{
          href:'#'
        }).html('OK').click(function(event) {
          let value=$(this).prev().val();
          let edit=$(this).parent().prev();
          let ok=$(this).parent();

          $.ajax({
            url: '/edit/'+type.type+'/'+type.id,
            type: 'POST',
            data: {field:value}
          })
          .done(function(type) {
            ok.hide();
            $(efield).html('');
            edit.children('span').html(value);
            edit.show();
          })
          .fail(function(jqXHR,text,msg) {
            let e=JSON.parse(jqXHR.responseText);

            $(efield).html(e.errors.field);

          });


        });

        let errorfield=$('<p>',{
          id : type.type+"_"+type.id+"_error"
        });


        errorfield.prependTo('#'+wrap);
        $('<div>').append(editfield).append(updatelink).hide().prependTo('#'+wrap);
        $('<p>').append(newtype).append(editlink).append(deletelink).prependTo('#'+wrap);
    }
  });
</script>
