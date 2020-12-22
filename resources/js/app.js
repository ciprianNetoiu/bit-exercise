require('./bootstrap');
window.colorsCount=0;
window.totalMarbles = 0;
function insertColors(colorsNumber) {
    window.colorsCount=colorsNumber;
    window.totalMarbles=colorsNumber * colorsNumber;
    let newInputs='<div class="header-list-colors">List of colors -  nr of marbles: ' + window.totalMarbles + '</div>';
    for(let i=0; i<colorsNumber; i++) {
        newInputs+='<div class="form-group">  ' +
            '<label for="color">' + window.listOfCollors[i].name + '</label>' +
            '<input class="form-control the-colors" data-id="' + window.listOfCollors[i].id + '" id="color-' + window.listOfCollors[i].id + '" type="number" name="colors[' + window.listOfCollors[i].id + ']" value=""> ' +
            '</div>';
    }
    $('#color-list-box').html(newInputs);
    $('#submit-exercise').removeClass('hide');
}

$(document).ready(function() {
    $('input#color-number').on('change',function() {
        let colorsNumber=$(this).val();
        let errorMessage='';
        /** VALIDATE input from user */
        if(colorsNumber>10 || colorsNumber<=0) {
            errorMessage='Number must be between 1 and 10';
        }
        if(errorMessage.length) {
            $('#error-box').html(errorMessage);
        } else {
            $('#error-box').html('');
            insertColors(colorsNumber);
        }

        /**  END VALIDATION */

    });

    $(document).on('submit','#exercise-form',function(e) {
        e.preventDefault();
        let colors=[];
        let total=0;
        let errorMessage='';
        $('.the-colors').each(function() {
            colors.push({'colorId': $(this).attr('data-id'), 'value' : $(this).val()});
            total+=parseInt($(this).val());
            if(!$(this).val()) {
                errorMessage='please fill all color inputs';
            }
        });
        // console.log(total);
        // console.log(window.marbles);
        if(total!=window.totalMarbles) {
            errorMessage='incorrect total of marbles';
        }
        if(errorMessage) {
            $('#error-box').html(errorMessage);
        } else {
            let postUrl=$(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: postUrl, // This is what I have updated
                data: {
                    colorsCount: window.colorsCount,
                    colors: colors
                }
            }).done(function(msg) {
                $('#answer-box').html(msg);
            });
        }
    });

});



