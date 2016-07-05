function init() {
    setWidthAndHeight();
    $(document).keypress(function(event) {
        if (event.charCode == 122) {
            $('#myModal').modal({
                show: true
            });
            //$('#zip').focus();
        }
    });
    $(window).resize(function(event) {
        setWidthAndHeight();
        $('#zipForm').submit();
    });
    $("#zip").focus(function() {
        $(this).on("mouseup.a keyup.a", function(e) {
            $(this).off("mouseup.a keyup.a").select();
        });
    });

    $(document).on( "taphold", function( event ) {
      $('#myModal').modal({
          show: true
      });
    } );

}

function setWidthAndHeight() {
    $("#windowWidth").val(window.innerWidth);
    $("#windowHeight").val(window.innerHeight);
}
