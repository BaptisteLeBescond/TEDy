/***
 * Gestion du drag and drop de la page Contrat En Cours
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
        //casesJetons = document.getElementById('casesJetons');
        casesJetons = document.getElementById('casesEtapes');
        jeton = casesJetons.getElementsByClassName('drop-jeton');
        for (var i = 0 ; i < jeton.length ; i++) {
            if($(jeton[i]).hasClass('fini')) {
                continue;
            }
            else {
                jeton[i].classList.add('dropzone');
                break;
            }
        };
    },
    onend : function(event) {
        var jetonLast = document.getElementsByClassName('last');
        if($(jetonLast[0]).hasClass('fini')) {
            $('#contratFiniModal').modal('show');
        }
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
        for (var i = 0 ; i < jeton.length ; i++) {
             if($(jeton[i]).hasClass('dropzone')) {
                jeton[i].classList.remove('dropzone');
                jeton[i].classList.add('fini');
                newJeton = document.getElementById('new-jeton');
                newJeton.classList.remove('draggable');

                imgJeton = document.getElementById('img-jeton');
                imgJeton.removeChild(newJeton);
                $(newJeton).attr('id','old-jeton');
                break;
            }
        };
        console.log('Dropped');
    },
    ondropdeactivate: function (event) {
        // remove active dropzone feedback
        event.target.classList.remove('drop-active');
        event.target.classList.remove('drop-target');
    }
});