$(".loader").css("opacity", 0), setTimeout(function() {
            $(".loader").hide()
        }, 600),
$(document).ready(function(){
    $("#toggle1").click(function(){
        $(".toggle1").show();
        $(".toggle2").hide();
        $(".toggle2").addClass("hidden");
        $(".toggle1").removeClass("hidden");
        $(".monthly.billing").addClass("black");
        $(".annual.billing").removeClass("black");
    });
    $("#toggle2").click(function(){
        $(".toggle2").show();
        $(".toggle1").hide();
        $(".toggle1").addClass("hidden");
        $(".toggle2").removeClass("hidden");
        $(".annual.billing").addClass("black");
        $(".monthly.billing").removeClass("black");
    });
});
    
$(document).ready(function(){
 
  // hide our element on page load
    $('.anim1').css('opacity', 0);
    $('.anim2').css('opacity', 0);
    $('.anim3').css('opacity', 0);
    $('.anim4').css('opacity', 0);
    $('.anim4').css('opacity', 0);
    $('.anim5').css('opacity', 0);
    $('.anim6').css('opacity', 0);
    $('.anim7').css('opacity', 0);
    $('.anim8').css('opacity', 0);
    $('.anim9').css('opacity', 0);
    $('.anim10').css('opacity', 0);
    $('.anim11').css('opacity', 0);
    $('.anim12').css('opacity', 0);
});


  function waypointClassExamples() {
    $('#new-operator, #options-only, #handler-only').waypoint({
      handler: function(direction) {
        notify(this.element.id + ' hit')
      }
    })
    $('#handler-first').waypoint(function(direction) {
      notify(this.element.id + ' hit 25% from the top of window')
    }, {
      offset: '25%'
    })
    $('#adapter-property-example').waypoint(function(direction) {
      notify('Using jQuery adapter: ' + !!this.adapter.$element)
    }, {
      offset: '25%'
    })
    $('#context-property-example').waypoint(function(direction) {
      notify('Context: ' + this.context.element)
    }, {
      offset: '25%'
    })
    $('#element-property-example').waypoint(function(direction) {
      notify('Waypoint element id: ' + this.element.id)
    }, {
      offset: '25%'
    })
    $('#group-property-example').waypoint(function(direction) {
      notify('Group: ' + this.group.name)
    }, {
      offset: '25%'
    })
    $('#options-property-example').waypoint(function(direction) {
      notify('Offset option: ' + this.options.offset)
    }, {
      offset: '50%'
    })
    $('#trigger-point-example').waypoint(function(direction) {
      notify('Trigger point: ' + this.triggerPoint)
    }, {
      offset: 'bottom-in-view'
    })
  }
