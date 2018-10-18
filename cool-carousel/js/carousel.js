window.onload = function(){
    var
        carousel = document.querySelector('.carousel'),
        figure = carousel.querySelector('figure'),
        numImages = figure.childElementCount,
        theta =  2 * Math.PI / numImages,
        currImage = 0,
        previous = document.getElementById('prev'),
        next = document.getElementById('next')
    ;

    previous.addEventListener('click', function() {
        currImage--;
        figure.style.transform = `rotateY(${currImage * -theta}rad)`;
    });
    
    next.addEventListener('click', function() {
        currImage++;
        figure.style.transform = `rotateY(${currImage * -theta}rad)`;
    });
    
};
