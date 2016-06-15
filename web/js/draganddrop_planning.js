/***
 * Gestion du drag and drop de la page Planning En Cours
 ***/


// target elements with the "draggable" class
interact('.draggable').draggable({
    // enable inertial throwing
    inertia: true,

    // keep the element within the area of it's parent
    restrict: {
        restriction: "parent",
        endOnly: true,
        elementRect: { 
            top: 0, 
            left: 0, 
            bottom: 1, 
            right: 1 
        }
    },

    // enable autoScroll
    autoScroll: true,

    // call this function on every dragmove event
    onmove: dragMoveListener,
    onstart : function(event) {
        // dropzone gets bigger
        $('.img-corbeille').attr('src','/TEDy/web/img/planning/waste-bin32.png');
        $('.corbeille').removeClass().addClass('col-md-12 corbeille bigger');
    },
    onend : function(event) {
        // dropzone gets thiner
        $('.img-corbeille').attr('src','/TEDy/web/img/planning/waste-bin24.png');
        $('.corbeille').removeClass().addClass('col-md-12 corbeille thiner');
    }
});

function dragMoveListener (event) {
    var target = event.target,
        // keep the dragged position in the data-x/data-y attributes
        x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
        y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

    // translate the element
    target.style.webkitTransform = target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';

    // update the posiion attributes
    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);
}

// this is used later in the resizing and gesture demos
window.dragMoveListener = dragMoveListener;



/* The dragging code for '.draggable' from the demo above
 * applies to this demo as well so it doesn't have to be repeated. 
 */

// enable draggables to be dropped into this
interact('.dropzone').dropzone({
    // Require a 75% element overlap for a drop to be possible
    overlap: 0.01,

    // listen for drop related events:
    ondropactivate: function (event) {
        // add active dropzone feedback
        event.target.classList.add('drop-active');
    },
    ondragenter: function (event) {
        var draggableElement = event.relatedTarget, dropzoneElement = event.target;

        // feedback the possibility of a drop
        dropzoneElement.classList.add('drop-target');
        draggableElement.classList.add('can-drop');

        
    },
    ondragleave: function (event) {
        // remove the drop feedback style
        event.target.classList.remove('drop-target');
        event.relatedTarget.classList.remove('can-drop');
    },
    ondrop: function (event) {
        console.log('Dropped');

        // draggable disapears
        //$('.draggable').css('display','none');
        var parent = event.relatedTarget.parentNode;
        parent.removeChild(event.relatedTarget);


        // style of new step
        var etapes = document.getElementsByClassName('etapes');
        if(typeof etapes[0] !== 'undefined') {
            etapes[0].classList.add('draggable');

            var etape = etapes[0].getElementsByClassName('etape');
            etape[0].classList.remove('col-md-offset-2');
            etape[0].classList.add('col-md-offset-1');
            etape[0].classList.remove('col-md-6');
            etape[0].classList.add('col-md-8');

            var color = etape[0].getElementsByClassName('col-color');
            color[0].classList.add('col-color-first');
            color[0].classList.remove('col-color');

            var img = etape[0].getElementsByClassName('img-etape');
            img[0].setAttribute('width','120');
            img[0].setAttribute('height','120');
        }
        else {
            var message = document.getElementById('message');
            message.classList.remove('message-ko');
            message.classList.add('message-ok');
            var inputDuree = document.getElementById('form_duree');
            var heure = document.getElementById('heure').innerHTML;
            var minute = document.getElementById('minute').innerHTML;
            var seconde = document.getElementById('seconde').innerHTML;
            inputDuree.setAttribute('value', heure+':'+minute+':'+seconde);
        }
        
    },
    ondropdeactivate: function (event) {
        // remove active dropzone feedback
        event.target.classList.remove('drop-active');
        event.target.classList.remove('drop-target');
    }
});